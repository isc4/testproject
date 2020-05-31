#!/usr/bin/php
<?php

use Models\Connect;
use Models\QueryBuilder;
use Models\Statistic;

$db = new QueryBuilder(Connect::db());

$date = date("Y-m-d"); #Фильтруем массив только по текущему дню

$delete = $db->findByParam('logs', [
    "date" => $date,
    "type" => 'delete'
],
    'AND');


$countDelete = 0;
foreach ($delete as $value)
{
    if ($value['date'] == $date) {
        $countDelete += 1;
    }
}

$add = $db->findByParam('logs', [
    "type" => 'add',
    "date" => date("Y-m-d")
],
    'AND');
$countAdd = 0;
foreach ($add as $value)
{
    if ($value['date'] == $date) {
        $countAdd += 1;
    }
}


Statistic::aboutUsers('statistics', [
    'date' => $date,
    'delete' => $countDelete,
    'add' => $countAdd
]);

flash()->success("Статистика операций в группе пользователи за " . $date ." успешно оправлена");

header("Location: /");

?>