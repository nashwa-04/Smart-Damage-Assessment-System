# Web Routes Architecture Diagram

## Route Structure Overview

```mermaid
graph TD
    A[Web Routes] --> B[Public Routes]
    A --> C[Auth Routes]
    A --> D[User Routes]
    A --> E[Admin Routes]

    B --> B1[/]
    B --> B2[/login]
    B --> B3[/register]
    B --> B4[/forgot-password]
    B --> B5[/reset-password]

    C --> C1[/verify-email]
    C --> C2[/email/verification-notification]
    C --> C3[/confirm-password]
    C --> C4[/password]
    C --> C5[/logout]

    D --> D1[/dashboard]
    D --> D2[/profile]

    E --> E1[/admin/dashboard]
    E --> E2[/admin/map]
    E --> E3[/admin/reports]

    style B fill:#e1f5e1
    style C fill:#fff4e1
    style D fill:#e1e1ff
    style E fill:#ffe1e1
```

## Authentication Flow

```mermaid
graph LR
    A[Guest] -->|Visit /login| B[Login Form]
    A -->|Visit /register| C[Register Form]
    B -->|POST /login| D[Authenticate]
    C -->|POST /register| E[Create User]
    D -->|Success| F[User Dashboard]
    E -->|Success| F
    F -->|Visit /admin/dashboard| G[Admin Dashboard]
    F -->|Logout| A

    style A fill:#f0f0f0
    style F fill:#e1f5e1
    style G fill:#ffe1e1
```

## Middleware Protection

```mermaid
graph TD
    A[Request] --> B{Route Type}

    B -->|Public| C[No Middleware]
    B -->|Guest| D[guest Middleware]
    B -->|Auth| E[auth Middleware]
    B -->|Verified| F[auth + verified Middleware]

    C --> G[Allow Access]
    D --> H{Is Guest?}
    E --> I{Is Authenticated?}
    F --> J{Is Verified?}

    H -->|Yes| G
    H -->|No| K[Redirect to /dashboard]

    I -->|Yes| G
    I -->|No| L[Redirect to /login]

    J -->|Yes| G
    J -->|No| M[Redirect to /verify-email]

    style C fill:#e1f5e1
    style D fill:#fff4e1
    style E fill:#e1e1ff
    style F fill:#ffe1e1
    style G fill:#90EE90
    style K fill:#FFB6C1
    style L fill:#FFB6C1
    style M fill:#FFB6C1
```

## Route File Organization

```mermaid
graph LR
    A[bootstrap/app.php] --> B[web.php]
    A --> C[auth.php]
    A --> D[api.php]
    A --> E[console.php]

    B --> F[Public Routes]
    B --> G[User Routes]
    B --> H[Admin Routes]

    C --> I[Auth Routes]
    C --> J[Password Routes]
    C --> K[Verification Routes]

    style A fill:#FFD700
    style B fill:#87CEEB
    style C fill:#87CEEB
    style F fill:#e1f5e1
    style G fill:#e1e1ff
    style H fill:#ffe1e1
    style I fill:#fff4e1
    style J fill:#fff4e1
    style K fill:#fff4e1
```

## View-Controller Mapping

```mermaid
graph TD
    A[/] --> B[welcome.blade.php]
    A1[/login] --> C[login.blade.php]
    A2[/register] --> D[register.blade.php]
    A3[/dashboard] --> E[dashboard.blade.php]
    A4[/profile] --> F[profile/edit.blade.php]
    A5[/admin/dashboard] --> G[admin/dashboard.blade.php]
    A6[/admin/map] --> H[admin/map.blade.php]
    A7[/admin/reports] --> I[admin/reports.blade.php]

    C --> J[AuthenticatedSessionController]
    D --> K[RegisteredUserController]
    E --> L[Dashboard Function]
    F --> M[ProfileController]
    G --> N[Admin DashboardController]
    H --> N
    I --> N

    style B fill:#e1f5e1
    style C fill:#fff4e1
    style D fill:#fff4e1
    style E fill:#e1e1ff
    style F fill:#e1e1ff
    style G fill:#ffe1e1
    style H fill:#ffe1e1
    style I fill:#ffe1e1
```

## Implementation Priority

### Phase 1: Critical (Must Complete)

1. Load auth.php in bootstrap/app.php
2. Update web.php with admin routes
3. Create welcome.blade.php

### Phase 2: Important (Should Complete)

4. Verify all route names
5. Test route accessibility
6. Ensure middleware protection

### Phase 3: Enhancement (Nice to Have)

7. Add role-based middleware
8. Create route tests
9. Add route documentation to API docs
