<?php

header('Content-Type: text/html; charset=UTF-8');

session_start();

if (!empty($_SESSION['login'])) {
  header('Location: ./');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $logmessage = '';
  $logerror = empty($_COOKIE['login_error']) ? '' : $_COOKIE['login_error'];
  if ($logerror) {
    setcookie('login_error', '', 100000);
    $logmessage = '<div>Неверный логин или пароль.&nbsp;</div>';
  }
  
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel='stylesheet' href='style.css'>
  <title>Задание 5</title>
</head>
<body>

  <?php
    if (!empty($logmessage)) {
      print($logmessage);
    }
  ?>

  <div>
		<form method='POST' class="login">
				<div>
					<label>Логин&nbsp;&nbsp;</label>
					<input type="text" name="login" placeholder="Ваш логин">
				</div>
				<div>
					<label>Пароль</label>
					<input type="pass" name="pass" placeholder="Ваш пароль">
				</div>
				<input type="submit" value='Войти'/>
		</form>
  </div>
</body>
</html>
<?php
}
else {

  $login = strip_tags($_POST['login']);
  $pass =  md5($_POST['pass']);

  $db_user = 'u67354';
  $db_pass = '3075308'; 

  $db = new PDO('mysql:host=localhost;dbname=u67354', $db_user, $db_pass, array(PDO::ATTR_PERSISTENT => true));

  try {

    $stmt = $db->prepare("SELECT * FROM sessions WHERE login = ?");
    $stmt->execute(array($login));
    $user_data = $stmt->fetch();

    if ($pass == $user_data['pass']) {
      $_SESSION['login'] = $login;
    }
    else {
      setcookie('login_error', '1', time() + 24 * 60 * 60);
      header('Location: login.php');
      exit();
    }

  }
  catch(PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
  }

  header('Location: ./');
}