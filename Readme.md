<p align="center"><img src="logo.svg" width="400" alt="Laravel Logo"></p>

<p align="center">
<img src="https://img.shields.io/badge/php-4.0.2-blue">
<img src="https://img.shields.io/badge/php-cUrl-green">
</p>

## Подробнее

PHP Библиотека, для работы с API SPWorlds

# Установка 

```bash
composer require anvilm/spworlds
```

# Использование

### Инициализация объекта класса

```php
use Anvilm\SPWorlds\API;

$SPWorlds = new API($id, $token);
```

### Платежи

Метод возвращает массив с ответом сервера и телом вашего запроса, для проверки подленности платежа

```php
...

$amount = 32; //Стоимость
$redirectUrl = 'https://some.url'; //Сюда будет перенаправлен пользователь
$webhookUrl = 'https://webhool.url'; //Сюда сервер отправит запрос после оплаты
$data = 'some data'; //Строка до 100 символов с любой информацией

$response = $SPWorlds->payment($amount, $redirectUrl, $webhookUrl, $data);
```

### Проверка платежей

Сервер отправит POST запрос на указанный в webhookUrl адрес, в хедере X-Body-Hash которого, будет закодированный в base64 SHA256 HMAC хеш тела запроса, ключ которого, токен карты.
```php
...

$Hash = getallheaders()['X-Body-Hash']; //Хеш, переданный с сервера
$token = '123'; //Токен карты
$body = $response['body']; //Тело запроса платежа, его возвращает метод payment.


$response = $SPWorlds->paymentVerify($Hash, $token, $body);
```

### Получить баланс карты

Метод возвращает баланс игрока

```php
...

$response = $SPWorlds->cardBalance();
```

### Перевод на карту

Метод возвращает ответ с сервера

```php
...

$receiver = '123'; //Номер карты получателя
$amount = 32; //Количество АРов
$comment = 'some comments'; //Коментарий к переводу, не обязательное поле

$response = $SPWorlds->cardTransaction($receiver, $amount, $comment);
```

### Получить ник игрока

Метод возвращает ник игрока, используя его DiscordID

```php
...

$DiscordID = '123'; //DiscordID игрока

$response = $SPWorlds->getUsername($DiscordID);
```