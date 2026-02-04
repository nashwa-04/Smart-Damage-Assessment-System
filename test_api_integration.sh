#!/bin/bash

# ============================================================================
# Smart Damage Assessment System - API Integration Test Script
# ============================================================================
# This script performs a full integration test of the Laravel API endpoints
# using cURL commands with automatic token extraction and management.
# ============================================================================

# Configuration
BASE_URL="http://10.28.57.151:8000/api"
EMAIL="user@test.com"
PASSWORD="password"
TEST_IMAGE="damage_test.jpg"

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Global variables
TOKEN=""
REPORT_ID=""

# ============================================================================
# Helper Functions
# ============================================================================

print_header() {
    echo -e "\n${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}\n"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# ============================================================================
# Test Functions
# ============================================================================

# 1. Setup - Create dummy image
setup_test_image() {
    print_header "1. Setup - Creating Test Image"
    
    if [ -f "$TEST_IMAGE" ]; then
        print_info "Test image already exists, skipping creation"
        return 0
    fi
    
    # Create a simple 100x100 pixel JPEG using ImageMagick or convert command
    if command -v convert &> /dev/null; then
        convert -size 100x100 xc:blue "$TEST_IMAGE"
        print_success "Test image created using ImageMagick: $TEST_IMAGE"
    elif command -v magick &> /dev/null; then
        magick -size 100x100 xc:blue "$TEST_IMAGE"
        print_success "Test image created using ImageMagick (magick): $TEST_IMAGE"
    else
        # Fallback: Create a minimal valid JPEG file using base64
        echo "/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////" > "$TEST_IMAGE"
        print_info "Created minimal test image: $TEST_IMAGE"
    fi
}

# 2. Health Check
test_health_check() {
    print_header "2. Health Check"
    
    RESPONSE=$(curl -s -w "\n%{http_code}" "$BASE_URL/")
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "200" ]; then
        print_success "Health check passed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 0
    else
        print_error "Health check failed (HTTP $HTTP_CODE)"
        return 1
    fi
}

# 3. Login
test_login() {
    print_header "3. Login"
    
    RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "$BASE_URL/login" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "200" ]; then
        # Extract token using jq if available, otherwise use grep/sed
        if command -v jq &> /dev/null; then
            TOKEN=$(echo "$BODY" | jq -r '.token // .access_token // empty')
        else
            TOKEN=$(echo "$BODY" | grep -o '"token":"[^"]*"' | sed 's/"token":"//;s/"//')
            if [ -z "$TOKEN" ]; then
                TOKEN=$(echo "$BODY" | grep -o '"access_token":"[^"]*"' | sed 's/"access_token":"//;s/"//')
            fi
        fi
        
        if [ -n "$TOKEN" ]; then
            print_success "Login successful (HTTP $HTTP_CODE)"
            print_info "Token extracted: ${TOKEN:0:20}..."
            echo "Full response: $BODY"
            return 0
        else
            print_error "Login succeeded but no token found in response"
            echo "Response: $BODY"
            return 1
        fi
    else
        print_error "Login failed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 1
    fi
}

# 4. Get User Data (verify token)
test_get_user() {
    print_header "4. Get User Data"
    
    if [ -z "$TOKEN" ]; then
        print_error "No token available"
        return 1
    fi
    
    RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "$BASE_URL/user" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "200" ]; then
        print_success "Get user data successful (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 0
    else
        print_error "Get user data failed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 1
    fi
}

# 5. Create Report
test_create_report() {
    print_header "5. Create Report"
    
    if [ -z "$TOKEN" ]; then
        print_error "No token available"
        return 1
    fi
    
    if [ ! -f "$TEST_IMAGE" ]; then
        print_error "Test image not found: $TEST_IMAGE"
        return 1
    fi
    
    # Realistic Arabic data for damage report
    LATITUDE="33.5138"
    LONGITUDE="36.2765"
    RAW_LOCATION="دمشق - منطقة المزة - شارع الفيلات الغربية - بناء رقم 14"
    RAW_DESCRIPTION="يوجد تصدع كبير في الجدار الخارجي الشمالي للمبنى يمتد من الطابق الأول حتى الثالث، مع تساقط أجزاء من الشرفة. يرجى التقييم العاجل لاحتمالية السقوط."
    
    print_info "Sending report with Arabic data:"
    echo "  Location: $RAW_LOCATION"
    echo "  Coordinates: $LATITUDE, $LONGITUDE"
    echo "  Description: $RAW_DESCRIPTION"
    
    RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "$BASE_URL/reports" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json" \
        -F "image=@$TEST_IMAGE" \
        -F "latitude=$LATITUDE" \
        -F "longitude=$LONGITUDE" \
        -F "raw_location=$RAW_LOCATION" \
        -F "raw_description=$RAW_DESCRIPTION")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "201" ] || [ "$HTTP_CODE" = "200" ]; then
        # Extract report ID
        if command -v jq &> /dev/null; then
            REPORT_ID=$(echo "$BODY" | jq -r '.id // .data.id // empty')
        else
            REPORT_ID=$(echo "$BODY" | grep -o '"id":[0-9]*' | sed 's/"id"://')
        fi
        
        if [ -n "$REPORT_ID" ]; then
            print_success "Report created successfully (HTTP $HTTP_CODE)"
            print_info "Report ID: $REPORT_ID"
            echo "Full response: $BODY"
            return 0
        else
            print_success "Report created but couldn't extract ID (HTTP $HTTP_CODE)"
            echo "Response: $BODY"
            return 0
        fi
    else
        print_error "Create report failed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 1
    fi
}

# 6. List Reports
test_list_reports() {
    print_header "6. List Reports"
    
    if [ -z "$TOKEN" ]; then
        print_error "No token available"
        return 1
    fi
    
    RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "$BASE_URL/reports" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "200" ]; then
        print_success "List reports successful (HTTP $HTTP_CODE)"
        
        # Check if our report is in the list
        if [ -n "$REPORT_ID" ]; then
            if echo "$BODY" | grep -q "$REPORT_ID"; then
                print_success "Created report (ID: $REPORT_ID) found in list"
            else
                print_info "Report ID $REPORT_ID not found in list (may be in pagination)"
            fi
        fi
        
        echo "Response: $BODY"
        return 0
    else
        print_error "List reports failed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 1
    fi
}

# 7. Show Report Details
test_show_report() {
    print_header "7. Show Report Details"
    
    if [ -z "$TOKEN" ]; then
        print_error "No token available"
        return 1
    fi
    
    if [ -z "$REPORT_ID" ]; then
        print_error "No report ID available"
        return 1
    fi
    
    RESPONSE=$(curl -s -w "\n%{http_code}" -X GET "$BASE_URL/reports/$REPORT_ID" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "200" ]; then
        print_success "Show report successful (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 0
    else
        print_error "Show report failed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 1
    fi
}

# 8. Delete Report
test_delete_report() {
    print_header "8. Delete Report"
    
    if [ -z "$TOKEN" ]; then
        print_error "No token available"
        return 1
    fi
    
    if [ -z "$REPORT_ID" ]; then
        print_error "No report ID available"
        return 1
    fi
    
    RESPONSE=$(curl -s -w "\n%{http_code}" -X DELETE "$BASE_URL/reports/$REPORT_ID" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "204" ]; then
        print_success "Delete report successful (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 0
    else
        print_error "Delete report failed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 1
    fi
}

# 9. Logout
test_logout() {
    print_header "9. Logout"
    
    if [ -z "$TOKEN" ]; then
        print_error "No token available"
        return 1
    fi
    
    RESPONSE=$(curl -s -w "\n%{http_code}" -X POST "$BASE_URL/logout" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Accept: application/json")
    
    HTTP_CODE=$(echo "$RESPONSE" | tail -n1)
    BODY=$(echo "$RESPONSE" | sed '$d')
    
    if [ "$HTTP_CODE" = "200" ]; then
        print_success "Logout successful (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        TOKEN=""
        return 0
    else
        print_error "Logout failed (HTTP $HTTP_CODE)"
        echo "Response: $BODY"
        return 1
    fi
}

# ============================================================================
# Main Execution
# ============================================================================

main() {
    print_header "Smart Damage Assessment System - API Integration Test"
    echo "Base URL: $BASE_URL"
    echo "Test User: $EMAIL"
    echo "Timestamp: $(date '+%Y-%m-%d %H:%M:%S')"
    
    # Initialize counters
    TOTAL_TESTS=0
    PASSED_TESTS=0
    FAILED_TESTS=0
    
    # Array to store test results
    declare -a TEST_RESULTS
    
    # Run tests
    setup_test_image
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("Setup:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("Setup:✗")
    fi
    
    test_health_check
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("Health Check:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("Health Check:✗")
    fi
    
    test_login
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("Login:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("Login:✗")
    fi
    
    test_get_user
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("Get User:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("Get User:✗")
    fi
    
    test_create_report
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("Create Report:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("Create Report:✗")
    fi
    
    test_list_reports
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("List Reports:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("List Reports:✗")
    fi
    
    test_show_report
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("Show Report:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("Show Report:✗")
    fi
    
    test_delete_report
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("Delete Report:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("Delete Report:✗")
    fi
    
    test_logout
    ((TOTAL_TESTS++))
    if [ $? -eq 0 ]; then
        ((PASSED_TESTS++))
        TEST_RESULTS+=("Logout:✓")
    else
        ((FAILED_TESTS++))
        TEST_RESULTS+=("Logout:✗")
    fi
    
    # Print summary
    print_header "Test Execution Summary"
    echo "Total Tests: $TOTAL_TESTS"
    echo -e "${GREEN}Passed: $PASSED_TESTS${NC}"
    echo -e "${RED}Failed: $FAILED_TESTS${NC}"
    echo ""
    echo "Test Results:"
    for result in "${TEST_RESULTS[@]}"; do
        if [[ $result == *":✓"* ]]; then
            echo -e "${GREEN}  $result${NC}"
        else
            echo -e "${RED}  $result${NC}"
        fi
    done
    
    # Cleanup
    if [ -f "$TEST_IMAGE" ]; then
        print_info "Cleaning up test image..."
        rm "$TEST_IMAGE"
    fi
    
    # Exit with appropriate code
    if [ $FAILED_TESTS -eq 0 ]; then
        echo -e "\n${GREEN}All tests passed successfully!${NC}"
        exit 0
    else
        echo -e "\n${RED}Some tests failed. Please check the output above.${NC}"
        exit 1
    fi
}

# Run main function
main
