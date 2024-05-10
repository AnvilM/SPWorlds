<?php

namespace AnvilM\SPWorlds\Exceptions;

use Exception;

class PaymentException extends SPWorldsException
{
    public static function dataLength()
    {
        return new static("Максимальная длина доп. информации - 100 символов");
    }





    public static function itemNameLength()
    {
        return new static("Минимальная длина названия товара 3 символа, максимальная - 64");
    }





    public static function itemCount()
    {
        return new static("Минимальное количество товара 1, максимальное - 9999");
    }





    public static function itemPrice()
    {
        return new static("Минимальная цена за ед. товара 1, максимальная - 1728");
    }





    public static function itemCommentLength()
    {
        return new static("Минимальная длина комментария товара 3 символа, максимальная - 64");
    }
}
