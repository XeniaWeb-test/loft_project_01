<?php
require_once('config.php');
require_once('src/functions.php');

try {
    $pdo = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
} catch (PDOException $e) {
    echo $e->getMessage();
    die;
}
$users_list = [];
$query = $pdo->query("SELECT email, user_name, phone FROM users");
if (!$query) {
    print_r($pdo->errorInfo());
    die;
}
$users = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $user) {
    $users_list[] = implode(' ; ', $user);
}
$admin_users = implode('<br>', $users_list);

$orders_list = [];
$query = $pdo->query("SELECT o.id, o.dt_add, contacts, description, u.email FROM orders o LEFT JOIN users u ON u.id = user_id");
if (!$query) {
    print_r($pdo->errorInfo());
    die;
}
$orders = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($orders as $order) {
    $orders_list[] = implode(' ; ', $order);
}
$admin_orders = implode('<br>', $orders_list);

$page_content = include_template('lay_admin.php', [
    'content_users' => $admin_users,
    'content_orders' => $admin_orders,
]);

print($page_content);