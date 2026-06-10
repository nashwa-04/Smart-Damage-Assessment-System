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
            $lang = $this->detectLanguage($reportData);
            $reportData['_lang'] = $lang;
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

            return $this->parseResponse($response->json(), $reportData['_lang'] ?? 'ar');

        } catch (\Exception $e) {
            Log::error('Gemini service error', [
                'error' => $e->getMessage(),
                'report_data_keys' => array_keys($reportData)
            ]);

            $lang = $reportData['_lang'] ?? 'ar';
            if ($lang === 'en') {
                return [
                    'normalized_location' => 'Unknown',
                    'damage_score' => 5,
                    'damage_level' => 'moderate',
                    'damage_category' => 'moderate',
                    'analysis_text' => 'AI analysis unavailable',
                    'is_relevant' => true
                ];
            }

            throw $e;
        }
    }

    protected function detectLanguage(array $data): string
    {
        $text = trim(($data['raw_description'] ?? '') . ' ' . ($data['raw_location'] ?? ''));
        if (empty($text)) {
            return 'ar';
        }
        $arabicCount = preg_match_all('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}]/u', $text);
        $latinCount = preg_match_all('/[a-zA-Z]/', $text);
        return ($latinCount > $arabicCount) ? 'en' : 'ar';
    }

    protected function buildPrompt(array $data): string
    {
        $lang = $this->detectLanguage($data);
        $data['_lang'] = $lang;

        $hasImage = !empty($data['image_path']) && file_exists(storage_path('app/public/' . $data['image_path']));

        if ($lang === 'en') {
            $imageInstruction = $hasImage ? "Image attached: Yes" : "Image attached: No";

            return "You are a strict expert assessor of structural damage to buildings and infrastructure in Syria. Your task is to reject any report that does not show real structural damage to buildings.

=== Strict Rejection Criteria ===
You MUST set is_relevant = false, damage_score = 0, and damage_category = \"rejected\" immediately if the report shows:
1. Household items, utensils, cups, plates, or any personal belongings (even if broken).
2. People, animals, plants, or natural scenery.
3. Household furniture, carpets, upholstery, or electrical appliances.
4. Streets or walls that are completely intact with no deep structural cracks or rubble.
5. Selfie photos or images unrelated to structural engineering.

**Remember**: We ONLY care about damage to (walls, ceilings, columns, roads, bridges, infrastructure). Anything else is completely rejected.

=== Documentation Rules ===
1. Location: Must be in \"Governorate - Area\" format (example: \"Damascus - Al-Midan\").
2. Structural Assessment (1-10):
   - 1-3: Minor superficial cracks in walls.
   - 4-6: Moderate structural cracks, falling parts of balconies.
   - 7-8: Partial building collapse (roof or load-bearing wall failure).
   - 9-10: Total or imminent building collapse.

=== Available Data ===
- {$imageInstruction}
- User description: \"" . ($data['raw_description'] ?? 'No description') . "\"
- Entered location: \"" . ($data['raw_location'] ?? 'Unknown') . "\"

=== Required Output (JSON only) ===
Answer strictly in the following format:
{
  \"is_relevant\": boolean,
  \"normalized_location\": \"Governorate - Area\" (or \"Rejected\" if not applicable),
  \"damage_score\": number from 0 to 10 (0 if rejected),
  \"damage_category\": \"rejected\" or \"minor\" or \"moderate\" or \"severe\" or \"critical\",
  \"analysis_text\": \"Write the rejection reason in detail (e.g., The image shows household items and no structural damage) or the structural analysis IN ENGLISH\"
}";
        }

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

    protected function parseResponse(array $data, string $lang = 'ar'): array
    {
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        if (empty($text)) {
            return $this->defaultResult($lang);
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

            $defaultLocation = $lang === 'en' ? 'Unknown' : 'غير محدد';
            $defaultAnalysis = $lang === 'en' ? 'AI analysis unavailable' : 'التحليل غير متوفر';

            return [
                'normalized_location' => $json['normalized_location'] ?? $defaultLocation,
                'damage_score' => $score,
                'damage_level' => $category,
                'damage_category' => $category,
                'analysis_text' => $json['analysis_text'] ?? $defaultAnalysis,
                'is_relevant' => $isRelevant
            ];
        }

        return $this->defaultResult($lang);
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

    protected function defaultResult(string $lang = 'ar'): array
    {
        if ($lang === 'en') {
            return [
                'normalized_location' => 'Unknown',
                'damage_score' => 5,
                'damage_level' => 'moderate',
                'damage_category' => 'moderate',
                'analysis_text' => 'AI analysis unavailable',
                'is_relevant' => true
            ];
        }

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
