<?php

if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
    requireLogin();
}

$user = 'u67354';
$pass = '3075308';
$db = new PDO('mysql:host=localhost;dbname=u67354', $user, $pass, array(PDO::ATTR_PERSISTENT => true));


$stmt = $db->prepare("SELECT login, password FROM admin WHERE login = ?"); //login: admin, pass: admin
$stmt->execute([$_SERVER['PHP_AUTH_USER']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $validUser = $row['login'];
    $validPassHash = $row['password'];
} else {
    requireLogin();
}

if ($_SERVER['PHP_AUTH_USER'] != $validUser  || ($_SERVER['PHP_AUTH_PW']) != $validPassHash ) {
    requireLogin();
}
session_start();
function requireLogin() {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}

