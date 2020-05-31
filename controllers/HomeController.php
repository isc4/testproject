<?php


namespace Controllers;

use Exception;
use Google_Client;
use League;
use Models\Connect;
use Models\QueryBuilder;
use Aura\Filter\FilterFactory;
use Models\Statistic;
use \Tamtamchik\SimpleFlash\Flash;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet;
use Google\Spreadsheet\SpreadsheetService;

class HomeController
{
    public function homePage()
    {
        $db = new QueryBuilder(Connect::db());
        $users = $db->getAll('users');
        $templates = new League\Plates\Engine('../views');
        echo $templates->render('homepage.view', ['header' => 'Users', 'users' => $users]);

    }

    public function addUser()
    {
        $templates = new League\Plates\Engine('../views');
        echo $templates->render('adduser.view', ['header' => 'Add Users']);
    }

    public function deleteUser($vars)
    {
        $data = [
            "date" => date("Y-m-d"),
            "type" => 'delete'
        ];
        $db = new QueryBuilder(Connect::db());
        $db->delete('users', implode(',', $vars));
        $db->make('logs', $data); // Занесение в бд даты и типа операции над пользователем
        flash()->warning("Пользователь удален");

        // Занесение в google-таблицу даты и типа операции над пользователем
        Statistic::aboutUsers('statistics', [
            'date' => date_create('now')->format('Y-m-d'),
            'type' => 'delete',
        ]);

        header("Location: / ");
    }

    public function create()
    {
        $db = new QueryBuilder(Connect::db());

        $search = $db->findByParam('users', [
            "name" => $_POST['name'],
            "email" => $_POST['email']
        ],
        'OR');

        if (!$search) {
            $filter_factory = new FilterFactory();
            $filter = $filter_factory->newValueFilter();

            $name = $filter->validate($_POST['name'], 'alnum')
                && ! $filter->validate($_POST['name'], 'int')
                && $filter->validate($_POST['name'], 'strlenBetween', 3, 20)
                && $filter->sanitize($_POST['name'], 'string');

            $email = $filter->validate($_POST['email'], 'email');


            if (! $name) {
                flash()->error('Имя введено не корректно');
                header("Location: /add");
            } elseif(! $email){
                flash()->error("Email введен не корректно");
                header("Location: /add");
            } else {
                $data = [
                    "date" => date("Y-m-d"),
                    "type" => 'add'
                ];

                $db->make('users', $_POST);
                $db->make('logs', $data);
                flash()->success("Пользователь успешно добавлен");

                Statistic::aboutUsers('statistics', [
                    'date' => date_create('now')->format('Y-m-d'),
                    'type' => 'add',
                ]);

                header("Location: /");
            }

        } else {
            flash()->error("Пользователь с такими данными уже зарегистрирован");
            header("Location: /add");
        }



    }

    public function statistic()
    {
        require __DIR__ . '/send.php';

    }
}