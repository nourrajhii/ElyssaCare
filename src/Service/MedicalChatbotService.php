<?php
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class MedicalChatbotService
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getResponse(string $question): string
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://openrouter.ai/api/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'openai/gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => "Tu es un chatbot médical."],
                    ['role' => 'user', 'content' => $question]
                ],
                'max_tokens' => 100,
            ],
        ]);

        $data = $response->toArray();

        return $data['choices'][0]['message']['content'] ?? "Je ne peux pas répondre à cette question.";
    }
}
