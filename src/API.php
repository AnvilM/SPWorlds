<?php

namespace Anvilm\SPWorlds;

class API
{
    //Токен авторизации
    private $authorization;

    /**
     * Конструткор
     *
     * @param  mixed $id Id карты
     * @param  mixed $token Токен карты
     * @return void
     */
    public function __construct($id, $token)
    {

        $this->authorization = base64_encode("{$id}:{$token}");
    }


    /**
     * Конструктор cUrl запросов
     *
     * @param  mixed $url URL адрес
     * @param  mixed $authorization Токен авторизации
     * @param  mixed $method Метод запроса
     * @param  mixed $payload Тело запроса
     * @return string
     */
    public function cUrl(string $url, string $authorization, string $method, array $payload = []): string
    {
        $payload = json_encode($payload);

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization" => "Bearer {$authorization}"
        ]);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);


        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Оплата
     *
     * @param  mixed $amount Стоимость покупки в АРах
     * @param  mixed $redirectUrl URL страницы, на которую попадет пользователь после оплаты
     * @param  mixed $webhookUrl URL, куда наш сервер направит запрос, чтобы оповестить ваш сервер об успешной оплате
     * @param  mixed $data Строка до 100 символов, сюда можно поместить любые полезные данных.
     * @return array
     */
    public function payment(int $amount, string $redirectUrl, string $webhookUrl, string $data): array
    {
        //Генерация тела запроса
        $payload = [
            'amount' => $amount,
            'redirectUrl' => $redirectUrl,
            'webhookUrl' => $webhookUrl,
            'data' => $data
        ];

        //Отправка запроса
        $response = $this->cUrl(
            "https://spworlds.ru/api/public/payment",
            $this->authorization,
            'POST',
            $payload
        );

        $response = json_decode($response, true);
        $url = $response['url'];

        return [
            'url' => $url, //Ссылка на страницу оплаты
            'body' => $payload //Тело запроса,для верификации платежа
        ];
    }

    /**
     * Проверка баланса карты
     *
     * @return string
     */
    public function cardBalance()
    {
        //Отправка запроса
        $response = $this->cUrl(
            "https://spworlds.ru/api/public/card",
            $this->authorization,
            'GET'
        );

        $response = json_decode($response, true);

        $balance = $response['balance'];

        return $balance;
    }


    /**
     * Перевод по карте
     *
     * @param  string $receiver Номер карты получателя
     * @param  int $amount Количество аров для перевода
     * @param  string $comment Комментарий для перевода
     * @return string
     */
    public function cardTransaction(string $receiver, int $amount, string $comment = ''): string
    {
        //Генерация тела запроса
        $payload = [
            'receiver' => $receiver,
            'amount' => $amount,
            'comment' => $comment
        ];

        //Отправка запроса
        $response = $this->cUrl(
            'https://spworlds.ru/api/public/transactions',
            $this->authorization,
            'POST',
            $payload
        );

        return $response;
    }


    /**
     * Получить ник игрока
     *
     * @param  mixed $DiscordId DiscordId игрока
     * @return string 
     */
    public function getUsername($DiscordId)
    {
        //Отправка запроса
        $response = $this->cUrl(
            'https://spworlds.ru/api/public/users/' . $DiscordId,
            $this->authorization,
            'GET',
        );

        $response = json_decode($response, true);

        $username = $response['username'];

        return $username;
    }


    /**
     * Верефикация платежа
     *
     * @param  mixed $Hash Хеш из хедера ответа
     * @param  mixed $token Токен карты
     * @param  mixed $request Тело запроса платежа
     * @return bool
     */
    public static function paymentVerify($Hash, $token, $body)
    {
        //Декодирование хеша из base64
        $Hash = base64_decode($Hash);

        //Генерация json бъекта из тела запроса
        $body = json_encode($body);

        //Генерация хеша из тела запроса
        $Hmac = hash_hmac('sha256', $body, $token);

        //Проверка хеша
        return $Hash == $Hmac ? true : false;
    }
}
