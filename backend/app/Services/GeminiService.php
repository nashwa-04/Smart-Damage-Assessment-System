<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function analyzeDamage(array $reportData): array
    {
        try {
            $prompt = $this->buildPrompt($reportData);
            $parts = $this->buildParts($prompt, $reportData);

            $response = Http::timeout(120)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key={$this->apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => $parts
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'maxOutputTokens' => 2048,
                        'responseMimeType' => 'application/json',
                    ]
                ]
            );

            if ($response->failed()) {
                $response = Http::timeout(120)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$this->apiKey}",
                    [
                        'contents' => [
                            [
                                'parts' => $parts
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.2,
                            'responseMimeType' => 'application/json',
                        ]
                    ]
                );

                if ($response->failed()) {
                    Log::error('Gemini API error', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    throw new \Exception('AI analysis failed: HTTP ' . $response->status());
                }
            }

            return $this->parseResponse($response->json());

        } catch (\Exception $e) {
            Log::error('Gemini service error', [
                'error' => $e->getMessage(),
                'report_data_keys' => array_keys($reportData)
            ]);
            throw $e;
        }
    }

    protected function buildPrompt(array $data): string
    {
        $hasImage = !empty($data['image_path']) && file_exists(storage_path('app/public/' . $data['image_path']));
        $hasDescription = !empty($data['raw_description']);

        $imageInstruction = $hasImage
            ? "صورة مرفقة: نعم"
            : "صورة مرفقة: لا";

        return "أنت محكم خبير وصارم جداً في تقييم أضرار الأبنية والمنشآت في سوريا. مهمتك هي استبعاد أي تقرير لا يظهر دماراً إنشائياً حقيقياً للمباني.

=== القاعدة الذهبية للرفض القاطع (Strict Rejection Criteria) ===
يجب عليك تعيين is_relevant = false و damage_score = 0 و damage_category = \"rejected\" فوراً إذا كان التقرير يظهر:
1. أدوات منزلية، أواني، كاسات، أطباق، أو أي أغراض شخصية (حتى لو كانت مكسورة).
2. أشخاص، حيوانات، نباتات، أو مناظر طبيعية.
3. أثاث منزلي، سجاد، مفروشات، أو أجهزة كهربائية.
4. شوارع أو جدران سليمة تماماً لا تحتوي على تصدعات إنشائية عميقة أو ركام.
5. صور سيلفي أو صور لا علاقة لها بالهندسة الإنشائية.

**تذكر**: نحن نهتم فقط بدمار (الجدران، الأسقف، الأعمدة، الطرقات، الجسور، البنية التحتية). أي شيء آخر مرفوض تماماً.

=== قواعد التوثيق والتوحيد ===
1. الموقع: يجب أن يكون بصيغة \"المحافظة - المنطقة\" (مثال: \"دمشق - الميدان\").
2. التقييم الإنشائي (1-10):
   - 1-3: تشققات سطحية بسيطة في الجدران.
   - 4-6: تصدعات إنشائية متوسطة، سقوط أجزاء من الشرفات.
   - 7-8: انهيار جزئي في المبنى (سقوط سقف أو جدار حامل).
   - 9-10: انهيار كلي أو وشيك للمبنى.

=== البيانات المتاحة ===
- {$imageInstruction}
- وصف المستخدم: \"" . ($data['raw_description'] ?? 'لا يوجد وصف') . "\"
- الموقع المُدخل: \"" . ($data['raw_location'] ?? 'غير محدد') . "\"

=== المطلوب (JSON فقط) ===
أجب بالصيغة التالية حصراً:
{
  \"is_relevant\": boolean,
  \"normalized_location\": \"المحافظة - المنطقة\" (أو \"مرفوض\" إذا كان غير ملائم),
  \"damage_score\": رقم من 0 إلى 10 (0 في حال الرفض),
  \"damage_category\": \"rejected\" أو \"minor\" أو \"moderate\" أو \"severe\" أو \"critical\",
  \"analysis_text\": \"اكتب هنا سبب الرفض بالتفصيل (مثلاً: الصورة تظهر أدوات منزلية ولا تظهر دماراً إنشائياً) أو التحليل الإنشائي بالعربية\"
}";
    }

    protected function buildParts(string $prompt, array $data): array
    {
        $parts = [['text' => $prompt]];

        if (!empty($data['image_path'])) {
            $this->addImagePart($parts, $data['image_path']);
        }

        if (!empty($data['images']) && is_array($data['images'])) {
            foreach ($data['images'] as $imagePath) {
                if ($imagePath !== ($data['image_path'] ?? null)) {
                    $this->addImagePart($parts, $imagePath);
                }
            }
        }

        return $parts;
    }

    protected function addImagePart(array &$parts, string $imagePath): void
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        if (file_exists($fullPath)) {
            $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';
            $base64Data = base64_encode(file_get_contents($fullPath));

            $parts[] = [
                'inline_data' => [
                    'mime_type' => $mimeType,
                    'data' => $base64Data
                ]
            ];
        }
    }

    protected function parseResponse(array $data): array
    {
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        if (empty($text)) {
            return $this->defaultResult();
        }

        $json = null;
        if (preg_match('/\{.*\}/s', $text, $matches)) {
            $json = json_decode($matches[0], true);
        }
        
        if (!$json) {
            $json = json_decode($text, true);
        }

        if ($json && is_array($json)) {
            $isRelevant = (bool)($json['is_relevant'] ?? true);
            $score = isset($json['damage_score']) ? intval($json['damage_score']) : 5;
            
            if (!$isRelevant) {
                $score = 0;
            }
            
            $score = max(0, min(10, $score));
            $category = $json['damage_category'] ?? $this->scoreToCategory($score);
            
            if ($score == 0) $category = 'rejected';

            return [
                'normalized_location' => $json['normalized_location'] ?? 'غير محدد',
                'damage_score' => $score,
                'damage_level' => $category,
                'damage_category' => $category,
                'analysis_text' => $json['analysis_text'] ?? 'التحليل غير متوفر',
                'is_relevant' => $isRelevant
            ];
        }

        return $this->defaultResult();
    }

    protected function scoreToCategory(int $score): string
    {
        if ($score == 0) {
            return 'rejected';
        }
        if ($score <= 3) {
            return 'minor';
        }
        if ($score <= 6) {
            return 'moderate';
        }
        if ($score <= 8) {
            return 'severe';
        }
        return 'critical';
    }

    protected function defaultResult(): array
    {
        return [
            'normalized_location' => 'غير محدد',
            'damage_score' => 5,
            'damage_level' => 'moderate',
            'damage_category' => 'moderate',
            'analysis_text' => 'التحليل غير متوفر',
            'is_relevant' => true
        ];
    }
}
