<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Результаты сохранены.';
  }

  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['phone'] = !empty($_COOKIE['phone_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['date'] = !empty($_COOKIE['date_error']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['langs'] = !empty($_COOKIE['langs_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['checkmark'] = !empty($_COOKIE['checkmark_error']);

  // Выдаем сообщения об ошибках.
  if ($errors['name'] == '1') {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">ФИО не указаны.</div>';
  }
  else if ($errors['name'] == '2') {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">Недопустимые символы!</div>';
  }
  
  if ($errors['phone'] == '1') {
    setcookie('phone_error', '', 100000);
    $messages[] = '<div class="error">Номер телефона не указан.</div>';
  }
  else if ($errors['phone'] == '2') {
    setcookie('phone_error', '', 100000);
    $messages[] = '<div class="error">Недопустимые символы!</div>';
  }
  
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Почта не указана.</div>';
  }

  if ($errors['date']) {
    setcookie('date_error', '', 100000);
    $messages[] = '<div class="error">Дата рождения не указана.</div>';
  }
  
  if ($errors['sex']) {
    setcookie('sex_error', '', 100000);
    $messages[] = '<div class="error">Пол не указан.</div>';
  }
  
  if ($errors['langs']) {
    setcookie('langs_error', '', 100000);
    $messages[] = '<div class="error">Языки программирования не выбраны.</div>';
  }
  
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию.</div>';
  }
  
  if ($errors['checkmark']) {
    setcookie('checkmark_error', '', 100000);
    $messages[] = '<div class="error">Отметьте чекбокс.</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['phone'] = empty($_COOKIE['phone_value']) ? '' : $_COOKIE['phone_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['date'] = empty($_COOKIE['date_value']) ? '' : $_COOKIE['date_value'];
  $values['sex'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];

  if (!empty($_COOKIE['langs_value'])) {
    $langs_value = json_decode($_COOKIE['langs_value']);
  }
  $values['langs'] = [];
  if (isset($langs_value) && is_array($langs_value)) {
      foreach ($langs_value as $lang) {
          if (!empty($langs[$lang])) {
              $values['langs'][$lang] = $lang;
          }
      }
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');

  exit();
}
else {
  $errors = FALSE;

  if (empty($_POST['name'])) {
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (!preg_match('/([А-Яа-я-]+\s[А-Яа-я-]+\s[А-Яа-я-]+)/', $_POST['name'])) {
    setcookie('name_error', '2', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  }
  
  if ( empty($_POST['phone']) ) {
    setcookie('phone_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (!is_numeric($_POST['phone'])) {
    setcookie('phone_error', '2', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);
  }
  
  if ( empty($_POST['email']) ) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }
  
  if ( empty($_POST['date']) ) {
    setcookie('date_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('date_value', $_POST['date'], time() + 30 * 24 * 60 * 60);
  }
  
  if ( !isset($_POST['sex']) ) {
    setcookie('sex_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('sex_value', $_POST['sex'], time() + 30 * 24 * 60 * 60);
  }
  
  $langs = array();
  foreach ($_POST['langs'] as $key => $value) {
      $langs[$key] = $value;
  }
  if (!sizeof($langs)) {
    setcookie('langs_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('langs_value', json_encode($langs), time() + 30 * 24 * 60 * 60);
  }
  
  if (empty($_POST['bio']) ) {
    print('Заполните биографию.<br/>');
    $errors = TRUE;
  }
  else {
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }
  
  if(!isset($_POST['checkmark']) || $_POST['checkmark'] != 'on') {
    setcookie('check_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  
  if ($errors) {
    header('Location: index.php');
    exit();
  }
  else {
    setcookie('name_error', '', 100000);
    setcookie('phone_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('date_error', '', 100000);
    setcookie('sex_error', '', 100000);
    setcookie('langs_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('check_error', '', 100000);
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
      setcookie('save', 'Ошибка! Результаты не сохранены.');
      exit();
    }
    
    header('Location: index.php');
}
