
<?php

header('Content-Type: text/html; charset=UTF-8');

$db_user = 'u67354';
$db_pass = '3075308';
$db = new PDO('mysql:host=localhost;dbname=u67354', $db_user, $db_pass, array(PDO::ATTR_PERSISTENT => true));

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $messages = array();
  $errors = array();
  $values = array();
  $langs = array();
  
  // SAVE PARAMETER
  if (!empty($_COOKIE['save'])) {

    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);

    $messages[] = $_COOKIE['save'];

    if (!empty($_COOKIE['pass'])) {
      $messages['savelogin'] = sprintf('<div> Вы можете&nbsp;<a href="login.php">войти</a>&nbsp;с логином&nbsp;<strong>%s</strong>&nbsp;и паролем&nbsp;<strong>%s</strong>&nbsp;для изменения данных. </div>',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }

  $errors['name'] = empty($_COOKIE['name_error']) ? '' : $_COOKIE['name_error'];
  $errors['phone'] = empty($_COOKIE['phone_error']) ? '' : $_COOKIE['phone_error'];
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['date'] = !empty($_COOKIE['date_error']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['langs'] = !empty($_COOKIE['langs_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['checkmark'] = !empty($_COOKIE['checkmark_error']);

  // Выдаем сообщения об ошибках.
  if ($errors['name'] == '1') {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">ФИО не указаны. &nbsp;</div>';
  }
  else if ($errors['name'] == '2') {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">Недопустимые символы! &nbsp;</div>';
  }
  
  if ($errors['phone'] == '1') {
    setcookie('phone_error', '', 100000);
    $messages[] = '<div class="error">Номер телефона не указан. &nbsp;</div>';
  }
  else if ($errors['phone'] == '2') {
    setcookie('phone_error', '', 100000);
    $messages[] = '<div class="error">Недопустимые символы! &nbsp;</div>';
  }
  
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Почта не указана. &nbsp;</div>';
  }

  if ($errors['date']) {
    setcookie('date_error', '', 100000);
    $messages[] = '<div class="error">Дата рождения не указана. &nbsp;</div>';
  }
  
  if ($errors['sex']) {
    setcookie('sex_error', '', 100000);
    $messages[] = '<div class="error">Пол не указан. &nbsp;</div>';
  }
  
  if ($errors['langs']) {
    setcookie('langs_error', '', 100000);
    $messages[] = '<div class="error">Языки программирования не выбраны. &nbsp;</div>';
  }
  
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию. &nbsp;</div>';
  }
  
  if ($errors['checkmark']) {
    setcookie('checkmark_error', '', 100000);
    $messages[] = '<div class="error">Отметьте чекбокс. &nbsp;</div>';
  }
  
  $langs['Pascal'] = 'Pascal';
  $langs['C'] = 'C';
  $langs['C++'] = 'C++';
  $langs['JavaScript'] = 'JavaScript';
  $langs['PHP'] = 'PHP';
  $langs['Python'] = 'Python';
  $langs['Java'] = 'Java';
  $langs['Haskel'] = 'Haskel';
  $langs['Clojure'] = 'Clojure';
  $langs['Prolog'] = 'Prolog';
  $langs['Scala'] = 'Scala';

  // Складываем предыдущие значения полей в массив, если есть.
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

  if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    if (!empty($_GET['quit'])) {
      $_SESSION = array();
      if (ini_get("session.use_cookies")) {
          $params = session_get_cookie_params();
          setcookie(session_name(), '', time() - 42000,
              $params["path"], $params["domain"],
              $params["secure"], $params["httponly"]
          );
      }
      foreach($_COOKIE as $key => $value) {
        setcookie($key, '', 100000);
      }
      session_destroy();
      header('Location: ./');
    }

    $messages[] = '<div>Вы вошли с логином '.$_SESSION['login'].'.&nbsp<a href="./?quit=1">Выйти</a>&nbsp;из аккаунта.</div>';

    try {
      $stmt = $db->prepare("SELECT id FROM sessions WHERE login = ?");
      $stmt->execute(array($_SESSION['login']));
      $app_id = $stmt->fetchColumn();

      $stmt = $db->prepare("SELECT * FROM form WHERE id = ?");
      $stmt->execute(array($app_id));

      $user_data = $stmt->fetch();

      $values['name'] = strip_tags($user_data['name']);
      $values['phone'] = strip_tags($user_data['phone']);
      $values['email'] = strip_tags($user_data['email']);
      $values['date'] = strip_tags($user_data['date']);
      $values['sex'] = strip_tags($user_data['sex']);
      $values['bio'] = strip_tags($user_data['bio']);

      $stmt = $db->prepare("SELECT lang FROM lang_table WHERE id = ?");
      $stmt->execute(array($app_id));

      $user_data = $stmt->fetch();

      $langs_value = explode(", ", $user_data['lang']);

      $values['lang'] = [];
      foreach ($langs_value as $lang) {
        if (!empty($langs[$lang])) {
          $values['lang'][$lang] = $lang;
        }
      }
    } 
    catch(PDOException $e) {
      setcookie('save', 'Ошибка! Результаты не загружены.');
      exit();
    }
  }

  include('form.php');
}
else {
  $errors = FALSE;

  if (empty($_POST['name'])) {
    setcookie('name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else if (!preg_match('/([A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+)/', $_POST['name'])) {
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
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }
  
  //if(!isset($_POST['checkmark']) || $_POST['checkmark'] != 'on') 
  if (empty($_POST['checkmark']))
  {
    setcookie('checkmark_error', '1', time() + 24 * 60 * 60);
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
    setcookie('checkmark_error', '', 100000);
  }
    
    if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
      
      try {
        $stmt = $db->prepare("SELECT id FROM sessions WHERE login = ?");
        $stmt->execute(array($_SESSION['login']));
        $id = $stmt->fetchColumn();
  
        $stmt = $db->prepare("UPDATE form SET name = ?, phone = ?, email = ?, date = ?, sex = ?, bio = ? WHERE id = ?");
        $stmt->execute(array(
          $_POST['name'],
          $_POST['phone'],
          $_POST['email'],
          $_POST['date'],
          $_POST['sex'],
          $_POST['bio'],
          $id
        ));
        
        $stmt = $db->prepare("SELECT lang FROM lang_table WHERE id = ?");
        $stmt->execute(array($id));
        $lang = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  
        $languages = $_POST["langs"];
  
        if (array_diff($languages, $lang) || array_diff($lang, $languages)) {
          $stmt = $db->prepare("DELETE FROM lang_table WHERE id = ?");
          $stmt->execute(array($id));
  
          $stmt = $db->prepare("INSERT INTO lang_table SET id = ?, lang = ?");
          foreach ($languages as $lang) {
            $stmt->execute(array($id, $lang));
          }
        }
  
      } catch(PDOException $e) {
        setcookie('save', 'Ошибка 111111111111! Результаты не сохранены.');
        exit();
      }
    }
    else {
      $user_login = uniqid();
      $user_pass = rand(123456, 999999);
      setcookie('login', $user_login);
      setcookie('pass', $user_pass);
      try {
        $stmt = $db->prepare("INSERT INTO form SET name = ?, phone = ?, email = ?, date = ?, sex = ?, bio = ?");
        $stmt -> execute(array(
          $_POST['name'],
          $_POST['phone'],
          $_POST['email'],
          $_POST['date'],
          $_POST['sex'],
          $_POST['bio']
        ));

        $id = $db->lastInsertId();
        
        foreach ($_POST['langs'] as $lang) {
          $stmt = $db->prepare("INSERT INTO lang_table SET id = ?, lang = ?");
          $stmt->execute(array($id, $lang));
        }

        $stmt = $db->prepare("INSERT INTO sessions SET id = ?, login = ?, pass = ?");
        $stmt->execute(array($id, $user_login, md5($user_pass))); //"'".$user_login."'"
        
      }
      catch(PDOException $e) {
        setcookie('save', 'Ошибка 222222222222222! Результаты не сохранены.');
        exit();
      }
    }
    setcookie('save', '<div>Спасибо, результаты сохранены!</div>');
    header('Location: index.php');
}