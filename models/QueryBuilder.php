<?php


namespace Models;

use PDO;

class QueryBuilder {

    private $pdo; // подключение к бд

    public function __construct( $pdo)
    {
        $this->pdo = $pdo;
    }

    // 1. Получение всех записей из таблицы
    /*
    * getAll( string $table ) : array
    */

    public function getAll($table)
    {
        $sql = "SELECT * FROM {$table}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Получение одной записи из таблицы по id
    /*
    * getOne( string $table, int $id ) : array
    */

    public function getOne($table, $id)
    {
        $sql = "SELECT * FROM {$table} WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Добавление записи в таблицу
    /*
    * make( string $table, array $data )
    */

    public function make($table,$data)
    {
        $keys = implode(', ', array_keys($data));
        $tags =":" . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$keys}) VALUE ({$tags})";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);
    }

    // 4. Изменение записи в таблице по id
    /*
    * update( string $table, array $data, int $id  )
    */

    public function update($table, $data, $id)
    {
        $keys = array_keys($data);
        $string = '';
        foreach($keys as $key) {
            $string .= $key . '=:' . $key . ',';
        }
        $keys = rtrim($string, ',');
        $data['id'] = $id;
        $sql = "UPDATE {$table} SET {$keys} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute($data);
    }

    // 5. Удаление записи в таблице по id
    /*
    * delete( string $table, int $id  )
    */

    public function delete($table, $id)
    {
        $sql = "DELETE FROM {$table} WHERE id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    // 6. Получение записей таблицы по ключевому значению
    /*
    * filter( string $table, string $field, string $key ) : array
    */

    public function filter ($table, $field, $key)
    {
        $sql = "SELECT * FROM {$table} WHERE {$field} = :{$field}";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(":{$field}", $key);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // 7. Поиск записи соответствующей заданным параметрам
    /*
    * findByParam( string $table, array $param ) : array
     * $operator - AND or OR
    */

    public function findByParam ($table, $param, $operator)
    {
        $keys = array_keys($param);
        $string = '';
        foreach($keys as $key) {
            $string .= $key . '=:' . $key . " $operator ";
        }
        $keys = rtrim($string, " $operator ");
        $sql = "SELECT * FROM {$table} WHERE {$keys}";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue("{$keys}", implode(',', $param));
        $statement->execute($param);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}