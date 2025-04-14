<?php

header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    print('Результаты сохранены.');
  }

  include('form.html');
  exit();
}

// Проверяем ошибки.
$errors = FALSE;


if ( empty($_POST['name']) || !preg_match('/([A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+)/', $_POST['name'])) {
  print('ФИО не указаны!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['phone']) || !is_numeric($_POST['phone'])) {
  print('Номер телефона не указан!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['email']) ) {
  print('Почта не указана!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['date']) ) {
  print('Дата рождения не указана!<br/>');
  $errors = TRUE;
}
if ( !isset($_POST['sex']) || !in_array($_POST['sex'], array('male', 'female')) ) {
  print('Пол не указан!<br/>');
  $errors = TRUE;
}

if ( empty($_POST['langs']) ) {
  print('Языки программирования не выбраны!<br/>');
  $errors = TRUE;
}

if (empty($_POST['bio']) ) {
  print('Заполните биографию.<br/>');
  $errors = TRUE;
}

if(!isset($_POST['checkmark']) || $_POST['checkmark'] != 'on') {
  print('Отметьте чекбокс.<br/>');
  $errors = TRUE;
}

if ($errors) {
  exit();
}

// Сохранение в базу данных.

$user = 'u67354';
$pass = '3075308';
$db = new PDO('mysql:host=localhost;dbname=u67354', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
// Подготовленный запрос. Не именованные метки.
try {
    $stmt = $db->prepare('INSERT INTO form SET name = ?, phone = ?, email = ?, date = ?, sex = ?, bio = ?');
    $stmt -> execute(array( $_POST['name'], $_POST['phone'], $_POST['email'], $_POST['date'], $_POST['sex'], $_POST['bio'] ));
  
    $id = $db->lastInsertId();
    
    foreach ($_POST['langs'] as $lang) {
      $stmt = $db->prepare('INSERT INTO lang_table (id, lang) VALUES (?, ?)');
      $stmt->execute([$id, $lang]);
    }
  }
  catch(PDOException $e){
    print('Ошибка: ' . $e->getMessage());
    exit();
  }
  
  header('Location: ?save=1');
