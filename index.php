<?php

require_once('config.php');
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
        $pdo = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage();
        die;
    }

    $query = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $query->execute(['email' => $email]);
    if (!$query) {
        echo 'Введите существующий Email!'; die;
    }

    $user = $query->fetch(PDO::FETCH_UNIQUE);
    if (!$user) {
        $query = $pdo->prepare("INSERT into users (user_name, phone, email) VALUES (:user_name, :phone, :email)");
        $query->execute(['user_name' => $user_name, 'phone' => $phone, 'email' => $email]);
        if (!$query) {
            echo 'Введите корректную информацию!'; die;
        }
    }

    $query = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $query->execute(['email' => $email]);
    if (!$query) {
        echo 'Введите существующий Email!'; die;
    }
    $user = $query->fetch(PDO::FETCH_UNIQUE);
    $user_id = $user['id'];

    $description = $comment ?? null;

    $query = $pdo->prepare("INSERT into orders (user_id, contacts, description) VALUES (:user_id, :contacts, :description)");
    $query->execute(['user_id' => $user_id, 'contacts' => $address, 'description' => $description]);
    if (!$query) {
        echo 'Введите корректную информацию!'; die;
    }
    $order_id = $pdo->lastInsertId();
    $query = $pdo-> prepare("SELECT count(*) FROM orders WHERE user_id = :user_id");
    $query->execute(['user_id' => $user_id]);
    $order_count = (int)$query->fetch(PDO::FETCH_COLUMN);

    $content = [];
    $content[] = 'Заказ № ' . $order_id . PHP_EOL . PHP_EOL;
    $content[] = 'Ваш заказ будет доставлен по адресу: ' . $address . PHP_EOL;
    $content[] = 'DarkBeefBurger за 500 рублей, 1 шт' . PHP_EOL . PHP_EOL;
    if ($order_count === 1) {
        $content[] = 'Спасибо - это ваш первый заказ!';
    } elseif ($order_count > 1) {
        $content[] = 'Спасибо! Это уже ваш ' . $order_count . '-й заказ!';
    }

    $dir_name = 'orders/' . date("Ymd");
    if(!is_dir($dir_name)){
        mkdir($dir_name, 0777, true);
    }
    $fileName = $dir_name . '/order_' . $order_id . '.txt';
    file_put_contents($fileName, implode('', $content));
}

$page_content = include_template('layout.php', []);

print($page_content);