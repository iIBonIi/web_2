<?php

include ('auth.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  try {
    $stmt = $db->prepare("SELECT id, name, phone, email, date, sex, bio FROM form");
    $stmt->execute();
    $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    print ('Error : ' . $e->getMessage());
    exit();
  }
  $messages = array();

  $errors = array();
  $errors['error_id'] = empty ($_COOKIE['error_id']) ? '' : $_COOKIE['error_id'];
  $errors['name'] = !empty ($_COOKIE['name_error']);
  $errors['name2'] = !empty ($_COOKIE['name_error2']);
  $errors['phone'] = !empty ($_COOKIE['phone_error']);
  $errors['phone2'] = !empty ($_COOKIE['phone_error2']);
  $errors['date'] = !empty ($_COOKIE['date_error']);
  $errors['email'] = !empty ($_COOKIE['email_error']);
  $errors['email2'] = !empty ($_COOKIE['email_error2']);
  $errors['langs'] = !empty ($_COOKIE['langs_error']);
  $errors['bio'] = !empty ($_COOKIE['bio_error']);

  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages['name'] = '<p class="msg">Заполните поле ФИО</p>';
  } else if ($errors['name2']) {
    setcookie('name_error2', '', 10000);
    $messages['name2'] = '<p class="msg">ФИО заполнено некорректно</p>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages['email'] = '<p class="msg">Заполните поле email</p>';
  } else if ($errors['email2']) {
    setcookie('email_error2', '', 100000);
    $messages['email2'] = '<p class="msg">Неверно заполнено поле email</p>';
  }
  if ($errors['phone']) {
    setcookie('phone_error', '', 100000);
    $messages['phone'] = '<p class="msg">Заполните поле телефон</p>';
  }
  if ($errors['phone2']) {
    setcookie('phone_error2', '', 100000);
    $messages['phone2'] = '<p class="msg">Недопустимые символы в номере телефона</p>';
  }
  if ($errors['date']) {
    setcookie('date_error', '', 100000);
    $messages['date'] = '<p class="msg">Заполните дату рождения</p>';
  }
  if ($errors['langs']) {
    setcookie('langs_error', '', 100000);
    $messages['langs'] = '<p class="msg">Выберите язык программирования</p>';
  }
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages['bio'] = '<p class="msg">Расскажите о себе что-нибудь</p>';
  }
  $_SESSION['token'] = bin2hex(random_bytes(32));
  $_SESSION['login'] = $validUser;

  include ('db.php');
  exit();
} else {

  if (!empty ($_POST['token']) && hash_equals($_POST['token'], $_SESSION['token'])) {
    foreach ($_POST as $key => $value) {

      if (preg_match('/^delete(\d+)$/', $key, $matches)) {
        $app_id = $matches[1];
        setcookie('delete', $app_id, time() + 24 * 60 * 60);
        $stmt = $db->prepare("DELETE FROM form WHERE id = ?");
        $stmt->execute([$app_id]);
        $stmt = $db->prepare("DELETE FROM lang_table WHERE id = ?");
        $stmt->execute([$app_id]);
        $stmt = $db->prepare("DELETE FROM sessions WHERE id = ?");
        $stmt->execute([$app_id]);
      }
      if (preg_match('/^save(\d+)$/', $key, $matches)) {
        $app_id = $matches[1];
        $dates = array();
        $dates['name'] = $_POST['name' . $app_id];
        $dates['phone'] = $_POST['phone' . $app_id];
        $dates['email'] = $_POST['email' . $app_id];
        $dates['date'] = $_POST['date' . $app_id];
        $dates['sex'] = $_POST['sex' . $app_id];
        $langs = $_POST['langs' . $app_id];
        $dates['bio'] = $_POST['bio' . $app_id];

        $name = $dates['name'];
        $phone = $dates['phone'];
        $email = $dates['email'];
        $date = $dates['date'];
        $sex = $dates['sex'];
        $bio = $dates['bio'];

        if (empty ($name)) {
          setcookie('name_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        } else if (!preg_match('/([A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+\s[A-Za-zА-Яа-я-]+)/', $name)) {
          setcookie('name_error2', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if (empty ($phone)) {
          setcookie('phone_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        } else if (!is_numeric($phone)) {
          setcookie('phone_error2', '2', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if ( empty($email) ) {
          setcookie('email_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        // else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //   setcookie('email_error2', '1', time() + 24 * 60 * 60);
        //   $errors = TRUE;
        // }
        if ( empty($date) ) {
          setcookie('date_error', '1', time() + 30 * 24 * 60 * 60);
          $errors = TRUE;
        }
        if (empty ($langs)) {
          setcookie('langs_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if (empty ($bio)) {
          setcookie('bio_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
        if ($errors) {
          setcookie('error_id', $app_id, time() + 24 * 60 * 60);
          header('Location: index.php');
          exit();
        } else {
          setcookie('name_error', '', 100000);
          setcookie('name_error2', '', 100000);
          setcookie('phone_error', '', 100000);
          setcookie('phone_error2', '', 10000);
          setcookie('email_error', '', 100000);
          setcookie('date__error', '', 100000);
          setcookie('email_error2', '', 100000);
          setcookie('langs_error', '', 100000);
          setcookie('bio_error', '', 100000);
          setcookie('error_id', '', 100000);
        }
        $stmt = $db->prepare("SELECT name, phone, email, date, sex, bio FROM form WHERE id = ?");
        $stmt->execute([$app_id]);
        $old_dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT lang FROM lang_table WHERE id = ?");
        $stmt->execute([$app_id]);
        $old_langs = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (array_diff($dates, $old_dates[0])) {
          $stmt = $db->prepare("UPDATE form SET name = ?, phone=?, email = ?, date = ?, sex = ?, bio = ? WHERE id = ?");
          $stmt->execute([$dates['name'], $dates['phone'],$dates['email'], $dates['date'], $dates['sex'], $dates['bio'], $app_id]);
        }
        if (array_diff($langs, $old_langs) || count($langs) != count($old_langs)) {
          $stmt = $db->prepare("DELETE FROM lang_table WHERE id = ?");
          $stmt->execute([$app_id]);
          $stmt = $db->prepare("INSERT INTO lang_table (id, lang) VALUES (?, ?)");
          foreach ($langs as $lang) {
            $stmt->execute([$app_id, $lang]);
          }
        }
      }
    }
  } else {
    die ('Ошибка CSRF: недопустимый токен');
  }
  header('Location: index.php');
}