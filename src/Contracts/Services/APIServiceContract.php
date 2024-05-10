<?php

namespace AnvilM\SPWorlds\Contracts\Services;

interface APIServiceContract
{
    /**
     * Информация о карте
     *
     * @return string Информация о карте: вубхук, баланс
     */
    public function cardInfo(): string;





    /**
     * Никнейм и uuid игрока по discord id
     *
     * @param  string $discordId Discord id пользователя
     * @return string Никнейм и uuid игрока
     */
    public function getUsername(string $discordId): string;





    /**
     * Списко карт игрока по никнейму
     *
     * @param  string $username Никнейм игрока
     * @return string JSON массив карт
     */
    public function getCards(string $username): string;





    /**
     * Аккаунт владельца токена
     *
     * @return string Данные владельца токена
     */
    public function getOwner(): string;





    /**
     * Создание запроса на оплату
     *
     * @param  array $items Товары для оплаты
     * @param  string $redirectUrl URL, куда будет переадресован пользователь после оплаты
     * @param  string $webhookUrl URL, куда будет отправлен запрос с сервера после успешной оплаты
     * @param  string $data Любая полезная информация
     * @return string URL на страницу оплаты
     */
    public function payment(array $items, string $redirectUrl, string $webhookUrl, string $data): string;





    /**
     * Перевод на карту
     *
     * @param  string $receiver Номер карты получателя
     * @param  int $amount Количество АРов
     * @param  string $comment Комментарий для перевода
     * @return int Новый баланс карты
     */
    public function transaction(string $receiver, int $amount, string $comment): int;





    /**
     * Изменение вебхука карты
     *
     * @param  mixed $url URL Нового вебхука
     * @return string ID и новый вебхук карты
     */
    public function setWebhook(string $url): string;





    /**
     * Валидация вебхука
     *
     * @param  mixed $body Тело запроса
     * @param  mixed $hashHeader Поле X-Body-Hash хедера
     * @return bool
     */
    public function validateHash(string $body, string $hashHeader): bool;





    /**
     * Bearer токен
     *
     * @return string Токен
     */
    public function getAuthorization(): string;
}
