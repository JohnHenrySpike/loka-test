<?php
$GLOBALS['PDO'] = null;
function connect_db(){
    try {
        $GLOBALS['PDO'] = new PDO("mysql:host=db;dbname=test", 'user', '1qa@WS3ed');
    } catch (PDOException $e) {
        echo "\n". $e->getMessage() ."\n";
    }
}

function db_query(string $query)
{
    if (!isset($GLOBALS['PDO'])) connect_db();
    $pdo = $GLOBALS['PDO'];
    return $pdo->query($query)->rowCount();
}

function db_fetch(string $query)
{
    if (!isset($GLOBALS['PDO'])) connect_db();
    $pdo = $GLOBALS['PDO'];
    return $pdo->query($query)->fetchAll();
}

function dd($var)
{
    echo '<pre>'.print_r($var, true).'</pre>';
}