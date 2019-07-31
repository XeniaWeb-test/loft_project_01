<?php

require_once('src/functions.php');

if (!empty($_POST)) {
    if (!empty($_POST['name'])) {
        $user_name = $_POST['name'];
    }
    if (!empty($_POST['phone'])) {
        $phone = $_POST['phone'];
    }
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
    }
    if (!empty($_POST['street'])) {
        $street = 'ул. ' . $_POST['street'];
    }
    if (!empty($_POST['home'])) {
        $home = 'дом ' . $_POST['home'];
    }
    if (!empty($_POST['part'])) {
        $part = 'корпус ' . $_POST['part'];
    }
    if (!empty($_POST['appt'])) {
        $appt = 'кв. ' . $_POST['appt'];
    }
    if (!empty($_POST['floor'])) {
        $floor = 'Этаж: ' . $_POST['floor'];
    }
    if (!empty($_POST['comment'])) {
        $comment = $_POST['comment'];
    }
    if (!empty($_POST['payment'])) {
        $payment = $_POST['payment'];
    }
    if (!empty($_POST['callback'])) {
        $callback = $_POST['callback'];
    }

    $forAddress = [];
    if (isset($street)) {
        $forAddress[] = $street;
    }
    if (isset($home)) {
        $forAddress[] = $home;
    }
    if (isset($part)) {
        $forAddress[] = $part;
    }
    if (isset($appt)) {
        $forAddress[] = $appt;
    }
    if (isset($floor)) {
        $forAddress[] = $floor;
    }
    $address = implode(', ', $forAddress);

    try{
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=burgers", 'root');
    } catch (PDOException $e) {
        echo $e->getMessage();
        die;
    }

    $query = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $query->execute(['email' => $email]);
    if (!$query) {
        echo $pdo->errorInfo(); die;
    }

    $user = $query->fetch(PDO::FETCH_UNIQUE);
    if (!$user) {
        $query = $pdo->prepare("INSERT into users (user_name, phone, email) VALUES (:user_name, :phone, :email)");
        $query->execute(['user_name' => $user_name, 'phone' => $phone, 'email' => $email]);
        if (!$query) {
            echo $pdo->errorInfo(); die;
        }
    }
    $query = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $query->execute(['email' => $email]);
    if (!$query) {
        print_r($pdo->errorInfo()); die;
    }

    $user = $query->fetch(PDO::FETCH_UNIQUE);
    $user_id = $user['id'];
    $description = $comment ?? null;
    $query = $pdo->prepare("INSERT into orders (user_id, contacts, description) VALUES (:user_id, :contacts, :description)");
    $query->execute(['user_id' => $user_id, 'contacts' => $address, 'description' => $description]);
    if (!$query) {
        echo $pdo->errorInfo(); die;
    }





    // Фильтрация валидация данных - присутствует необходимые переменные - записать в переменную
    // получаем пользователя по емайл - получить id пользователя из базы
    // если юзера еще нет, то добавляем юзера в базу и тогда получаем id
    // добавляем заказ в табл ордер
    // отправляем письмо пользователю  - записать тело письма в файл

}

$page_content = include_template('layout.php', [

]);

print($page_content);