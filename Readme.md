<p align="center"><img src="logo.svg" width="400" alt="Laravel Logo"></p>

<p align="center">
<img src="https://img.shields.io/badge/PHP-8.1-blue">
</p>

# Подробнее

Laravel Библиотека, для работы с API SPWorlds

## Установка 

```bash
composer require anvilm/spworlds
```

## Использование

### Инициализация объекта класса

```php
use Anvilm\SPWorlds\API;

$SPWorlds = new API($id, $token);
```

### Информация о карте

Метод возвращает JSON строку с балансом карты и вебхуком

```php
$SPWorlds->APIService->cardInfo()
```

### Получение ника пользователя

Метод возвращает JSON строку с ником и UUID

```php
$discordId = 'discord_id'; //Discord id игрока

$SPWorlds->APIService->getUsername($discordId);
```

### Получение карт игрока

Метод возвращает JSON строку с массивом карт игрока: имя карты и номер

```php
$username = 'user_name'; //Ник игрока

$SPWorlds->APIService->getCards($username);
```

### Получение аккаунта владельца токена

Метод возвращает JSON строку с данными игрока:
Аккаунт: id, username, minecraftUUID, status, roles, city, cards, createdAt.
Город: id, name, description, x, z, isMayor
Карта: id, name, number, color

```php
$SPWorlds->APIService->getOwner();
```

### Оплата на вашем сайте

Метод возвращает JSON строку с сылкой на страницу оплаты.
После успешной оплаты сервер отправит POST запрос на webhookUrl

```php
//Массив предметов на покупку
$items = [
    [
        'name' => 'item_name', //Имя предмета
        'count' => '10', //Количество предметов
        'price' => '1', //Цена за штуку
        'comment' => 'some comment' //Комментарий
    ]
];

$redirectUrl = 'https://redirect.url'; //Ссылка для переадрессации пользователя
$webhookUrl = 'https://webhook.url'; //Вебхук

$data = 'some data'; //Любая информация

$SPWorlds->APIService->payment($items, $redirectUrl, $webhookUrl, $data);
```

### Банковские переводы

Метод возвращает JSON строку с новым балансом карты

```php
$receiver = 'receiver card'; //Номер карты получателя
$ammount = '10'; //Сумма
$comment = 'some comment'; //Комментарий к переводу

$SPWorlds->APIService->transaction($receiver, $ammount, $comment);
```

### Изменение вебхука карты

Метод возвращает JSON строку с id карты и новым вебхуком

```php
$webhook = 'https://webhook.url'; //Вебхук

$SPWorlds->APIService->setWebhook($webhook);
```

### Валидация оплаты

Метод возвращает true или false

```php
$body = $request->getContent(); //JSON тело запроса
$hashHeader = $request->header('X-Body-Hash'); //Хеш тела запроса

$SPWorlds->APIService->validateHash($body, $hashHeader);
```

### Токен авторизации

Метод возвращает Bearer токен

```php
$SPWorlds->APIService->getAuthorization();
```