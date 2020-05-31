<?php


namespace Models;
use PDO;

class Connect
{
    public static function db()
    {
        return new PDO("mysql:host=localhost; dbname=test; charset=utf8;", "root", "secret");
    }
}