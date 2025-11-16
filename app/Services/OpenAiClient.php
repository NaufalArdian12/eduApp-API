<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenAiClient
{
    public function chatJson(string $systemPrompt, string $userPrompt): array
    {
        $key = config('services.openai.key');

        if (!$key) {
            throw new RuntimeException('OPENAI_API_KEY is not configured');
        }

        $response = Http::withToken($key)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt,
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt,
                    ],
                ],
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'OpenAI error: ' . $response->status() . ' - ' . $response->body()
            );
        }

        $content = $response->json('choices.0.message.content');

        $data = json_decode($content, true);

        if (!is_array($data)) {
            throw new RuntimeException('Invalid JSON returned from OpenAI: ' . $content);
        }

        return $data;
    }
}
