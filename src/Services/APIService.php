<?php

namespace AnvilM\SPWorlds\Services;

use AnvilM\SPWorlds\Contracts\Services\APIServiceContract;
use AnvilM\SPWorlds\Exceptions\PaymentException;
use AnvilM\SPWorlds\Exceptions\SPWorldsException;
use Illuminate\Support\Facades\Http;

class APIService implements APIServiceContract
{
    private readonly string $authorization;
    private readonly string $apiUrl;





    public function __construct(string $authorization)
    {
        $this->authorization = $authorization;
        $this->apiUrl = config('SPWorlds.API_URL');
    }





    public function cardInfo(): string
    {
        $response = Http::withHeader('Authorization', 'Bearer ' . $this->authorization)
            ->get($this->apiUrl . 'public/card');

        return $response->body();
    }





    public function getUsername(string $discordId): string
    {
        $response = Http::withHeader('Authorization', 'Bearer ' . $this->authorization)
            ->get($this->apiUrl . 'public/users/' . $discordId);

        return $response->body();
    }





    public function getCards(string $username): string
    {
        $response = Http::withHeader('Authorization', 'Bearer ' . $this->authorization)
            ->get($this->apiUrl . 'public/accounts/' . $username . '/cards');

        return $response->body();
    }





    public function getOwner(): string
    {
        $response = Http::withHeader('Authorization', 'Bearer ' . $this->authorization)
            ->get($this->apiUrl . 'public/accounts/me');

        return $response->body();
    }





    public function payment(array $items, string $redirectUrl, string $webhookUrl, string $data): string
    {
        $this->validatePaymentItems($items);

        if (strlen($data) > 100)
        {
            throw PaymentException::dataLength();
        }

        $response = Http::withHeader('Authorization', 'Bearer ' . $this->authorization)
            ->post($this->apiUrl . 'public/payments', [
                'items' => $items,
                'redirectUrl' => $redirectUrl,
                'webhookUrl' => $webhookUrl,
                'data' => $data
            ]);

        return $response->body();
    }





    private function validatePaymentItems(array $items)
    {
        foreach ($items as $item)
        {
            if (strlen($item['name']) < 3 || strlen($item['name']) > 64)
            {
                throw PaymentException::itemNameLength();
            }

            if ($item['count'] < 1 || $item['count'] > 9999)
            {
                throw PaymentException::itemCount();
            }

            if ($item['price'] < 1 || $item['price'] > 1728)
            {
                throw PaymentException::itemPrice();
            }

            if ($item['comment'] != '' && (strlen($item['comment']) < 3 || strlen($item['count']) > 64))
            {
                throw PaymentException::itemCommentLength();
            }
        }
    }





    public function transaction(string $receiver, int $amount, string $comment): int
    {
        $response = Http::withHeader('Authorization', 'Bearer ' . $this->authorization)
            ->post($this->apiUrl . 'public/transactions', [
                'receiver' => $receiver,
                'amount' => $amount,
                'comment' => $comment
            ]);

        return (int) $response->body();
    }





    public function setWebhook(string $url): string
    {
        $response = Http::withHeader('Authorization', 'Bearer ' . $this->authorization)
            ->put($this->apiUrl . 'public/card/webhook', [
                'url' => $url
            ]);

        return $response->body();
    }





    public function validateHash(string $body, string $hashHeader): bool
    {
        $hmac = hash_hmac('sha256', $body, $this->authorization);

        return $hmac == $hashHeader;
    }





    public function getAuthorization(): string
    {
        return $this->authorization;
    }
}
