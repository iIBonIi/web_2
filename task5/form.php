<!DOCTYPE html>
<html lang='ru'>
  <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='style.css'>
    <title>Задание 5</title>
  </head>
  
  <body>

    <?php
      if (!empty($messages)) {
        print('<div id="messages">');
        foreach ($messages as $message) {
          print($message);
          print('&nbsp;');
        }
        print('</div>');
      }
    ?>
    
    <h3>ФОРМА</h3>
    <form action='index.php' method='POST' class='form'>
      <div>
      
        <label>
          Фамилия, Имя, Отчество:<br />
          <input name='name' placeholder='Введите ФИО' value="<?php print $values['name']; ?>" maxlength='50' required/>
        </label><br />
  
        
        <label>
          Номер телефона:<br />
          <input name='phone' type='tel' placeholder='+79001110011' value="<?php print $values['phone']; ?>" maxlength='12' required/>
        </label><br />
      </div>
      
      <div>
        
        <label>
          Адрес электронной почты:<br />
          <input name='email' type='email' placeholder='Введите вашу почту' value="<?php print $values['email']; ?>" maxlength='50' required/>
        </label><br />
        
        
        <label>
          Дата рождения:<br />
          <input name='date' type='date' value="<?php print $values['date']; ?>" min='1900-01-01' max='2020-01-01' required/>
        </label><br />
      </div>
      
      <div>
        Пол: <div class='mr1'></div> <br />
        <label>
          <input name='sex' type='radio' value='male' <?php if ($values['sex'] == 'male') {print 'checked';} ?>/>
        М
        <div></div>
          <input name='sex' type='radio' value='female' <?php if ($values['sex'] == 'female') {print 'checked';} ?>/>
        Ж <div class='mr2'></div>
        </label><br />

        <label>
          Любимый язык программирования:<br/>
          <select name='langs[]' multiple='multiple'>
            <?php
                foreach ($langs as $key => $value) {
                  $selected = empty($values['langs'][$key]) ? '' : ' selected="selected" ';
                  printf('<option value="%s",%s>%s</option>', $key, $selected, $value);
                }
                ?>
          </select>
        </label><br />
      </div>
      
      <label>
        Биография:<br />
        <textarea name='bio' maxlength='20'><?php print $values['bio']; ?></textarea>
      </label><br />

      <label>
        <input name='checkmark' type='checkbox'/>
        С контрактом ознакомлен(а)</label><br/>

      <input type='submit' value='Сохранить'/>
    </form>
  </body>
</html>