<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <form action="" method="POST">

        <table>
            <h1>Страница администратора</h1>
            <div class = "langStat">
                
                <?php
                
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'Pascal';");
                $stmt->execute();
                $Pascal = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'C';");
                $stmt->execute();
                $C = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'C++';");
                $stmt->execute();
                $Cpp = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'JavaScript';");
                $stmt->execute();
                $JavaScript = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'PHP';");
                $stmt->execute();
                $PHP = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'Python';");
                $stmt->execute();
                $Python = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'Java';");
                $stmt->execute();
                $Java = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'Haskel';");
                $stmt->execute();
                $Haskel = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'Clojure';");
                $stmt->execute();
                $Clojure = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'Prolog';");
                $stmt->execute();
                $Prolog = $stmt->fetchColumn();
                $stmt = $db->prepare("SELECT count(id) from lang_table where lang = 'Scala';");
                $stmt->execute();
                $Scala = $stmt->fetchColumn();

                echo '<div class="msgbox">';
                if (!empty ($messages)) {
                    foreach ($messages as $message) {
                        print ($message);
                    }
                }
                echo '</div>';
                ?>
            </div>
            <tr>
                <th>id</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>email</th>
                <th>Год</th>
                <th>Пол</th>
                <th>ЯП</th>
                <th>Биография</th>
                <th>Изменить</th>
            </tr>
            <?php
            foreach ($values as $value) {
                echo '<tr>
                            <td style="font-weight: 700;">';
                print ($value['id']);
                echo '</td>
                            <td class="name">
                                <input name="name' . $value['id'] . '" value="';
                print (htmlspecialchars(strip_tags($value['name'])));
                echo '">
                            </td>
                            <td class="phone">
                            <input  name="phone' . $value['id'] . '" value="';
                print (htmlspecialchars(strip_tags($value['phone'])));
                echo '">
                            </td>
                            <td class="email">
                                <input  name="email' . $value['id'] . '" value="';
                print (htmlspecialchars(strip_tags($value['email'])));
                echo '">
                            </td>
                             <td class="date">
                                <input name="date' . $value['id'] . '" type="date" value="';
                print (htmlspecialchars(strip_tags($value['date'])));
                echo '" min="1900-01-01" max="2024-01-01"/> ';
                                
                echo '</select>
                            </td>
                            <td> 
                                <div >
                                    <input type="radio" id="radioMale' . $value['id'] . '" name="sex' . $value['id'] . '" value="male" ';
                if ($value['sex'] == 'male')
                    echo 'checked';
                echo '>
                                    <label for="radioMale' . $value['id'] . '">Мужчина</label>
                                </div>
                                <div >
                                    <input type="radio" id="radioFemale' . $value['id'] . '" name="sex' . $value['id'] . '" value="female" ';
                if ($value['sex'] == 'female')
                    echo 'checked';
                echo '>
                                    <label for="radioFemale' . $value['id'] . '">Женщина</label>
                                </div>
                            </td>
                            ';
                $stmt = $db->prepare("SELECT lang FROM lang_table WHERE id = ?");
                $stmt->execute([$value['id']]);
                $langs = $stmt->fetchAll(PDO::FETCH_COLUMN);
                echo '<td class="langs">
                                <div class="align">
                                    <div class="marg">
                                        <input type="checkbox" id="Pascal' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Pascal"' . (in_array('Pascal', $langs) ? ' checked' : '') . '>
                                        <label for="Pascal' . $value['id'] . '">Pascal</label>
                                    </div>
                                    <div class="marg">
                                        <input type="checkbox" id="C' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="C"' . (in_array('C', $langs) ? ' checked' : '') . '>
                                        <label for="C' . $value['id'] . '">C</label>
                                    </div>
                                    <div class="marg">
                                        <input type="checkbox" id="Cpp' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Cpp"' . (in_array('Cpp', $langs) ? ' checked' : '') . '>
                                        <label for="Cpp' . $value['id'] . '">Cpp</label>
                                    </div>
                                    <div class="marg">
                                        <input type="checkbox" id="JavaScript' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="JavaScript"' . (in_array('JavaScript', $langs) ? ' checked' : '') . '>
                                        <label for="JavaScript' . $value['id'] . '">JavaScript</label>
                                    </div>
                                </div>
                                <div class="align">
                                    <div class="marg">
                                        <input type="checkbox" id="PHP' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="PHP"' . (in_array('PHP', $langs) ? ' checked' : '') . '>
                                        <label for="PHP' . $value['id'] . '">PHP</label>
                                    </div>
                                    <div class="marg">
                                        <input type="checkbox" id="Python' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Python"' . (in_array('Python', $langs) ? ' checked' : '') . '>
                                        <label for="Python' . $value['id'] . '">Python</label>
                                    </div>
                                    <div class="marg">
                                        <input type="checkbox" id="Java' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Java"' . (in_array('Java', $langs) ? ' checked' : '') . '>
                                        <label for="Java' . $value['id'] . '">Java</label>
                                    </div>
                                    <div class="marg">
                                        <input type="checkbox" id="Haskel' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Haskel"' . (in_array('Haskel', $langs) ? ' checked' : '') . '>
                                        <label for="Haskel' . $value['id'] . '">Haskel</label>
                                    </div>
                                </div>
                                <div class="align">
                                    <div class="marg">                           
                                        <input type="checkbox" id="Clojure' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Clojure"' . (in_array('Clojure', $langs) ? ' checked' : '') . '>
                                        <label for="Clojure' . $value['id'] . '">Clojure</label>
                                    </div>
                                    <div class="marg">
                                        <input type="checkbox" id="Prolog' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Prolog"' . (in_array('Prolog', $langs) ? ' checked' : '') . '>
                                        <label for="Prolog' . $value['id'] . '">Prolog</label>
                                    </div>
                                    <div class="marg">
                                        <input type="checkbox" id="Scala' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Scala"' . (in_array('Scala', $langs) ? ' checked' : '') . '>
                                        <label for="Scala' . $value['id'] . '">Scala</label>
                                    </div>
                                </div>
                            </td>
                            <td class="bio">
                                <textarea  name="bio' . $value['id'] . '" id="" cols="15" rows="4" maxlength="20">';
                print htmlspecialchars(strip_tags($value['bio']));
                /*<div>
                                <select multiple="multiple" style="color:black;">
                                </select>
                                <option id="Pascal' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Pascal"' . (in_array('Pascal', $langs) ? ' selected' : '') . '>Pascal</option>
                                <option id="C' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="C"' . (in_array('C', $langs) ? ' selected' : '') . '>C</option>
                                <option id="Cpp' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="C++"' . (in_array('Cpp', $langs) ? ' selected' : '') . '>C++</option>
                                <option id="JavaScript' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="JavaScript"' . (in_array('JavaScript', $langs) ? ' selected' : '') . '>JavaScript</option>
                                <option id="PHP' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="PHP"' . (in_array('PHP', $langs) ? ' selected' : '') . '>PHP</option>
                                <option id="Python' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Python"' . (in_array('Python', $langs) ? ' selected' : '') . '>Python</option>
                                <option id="Java' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Java"' . (in_array('Java', $langs) ? ' selected' : '') . '>Java</option>
                                <option id="Haskel' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Haskel"' . (in_array('Haskel', $langs) ? ' selected' : '') . '>Haskel</option>
                                <option id="Clojure' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Clojure"' . (in_array('Clojure', $langs) ? ' selected' : '') . '>Clojure</option>
                                <option id="Prolog' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Prolog"' . (in_array('Prolog', $langs) ? ' selected' : '') . '>Prolog</option>
                                <option id="Scala' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Scala"' . (in_array('Scala', $langs) ? ' selected' : '') . '>Scala</option>
                                    <input type="checkbox" id="Cs' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="C#"' . (in_array('C#', $langs) ? ' checked' : '') . '>
                     
                                    <label for="Cs' . $value['id'] . '">C#</label>
                                </div>
                                <div >
                                    <input type="checkbox" id="Java' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Java"' . (in_array('Java', $langs) ? ' checked' : '') . '>
                            
                                    <label for="Java' . $value['id'] . '">Java</label>
                                </div>
                                <div >
                                    <input type="checkbox" id="Python' . $value['id'] . '" name="langs' . $value['id'] . '[]" value="Python"' . (in_array('Python', $langs) ? ' checked' : '') . '>
                         
                                    <label for="Python' . $value['id'] . '">Python</label>
                                </div>*/
                echo '</textarea>
                            </td>
                            <td >
                            <div class="change">
                       
                                <div>
                                    <input class="width80" name="save' . $value['id'] . '" type="submit" value="Сохранить"/>
                                </div>
                              
                                <div>
                                    <input class="width80" name="delete' . $value['id'] . '" type="submit" value="Удалить"/>
                                </div>
                            </div>
                            </td>


                        </tr>';
            }
            ?>
        </table>

        <h3 class="langProg">Статистика языков программирования:</h3>
        <table>
            <?php
                echo "<tr> <td> Pascal </td><td> C </td><td> C++ </td><td> JavaScript </td><td> PHP </td><td> Python </td><td> Java </td><td> Haskel </td><td> Clojure </td><td> Prolog </td><td> Scala </td> </tr>";
                echo "<tr>";
                echo "<td>" . (empty ($Pascal) ? '0' : $Pascal) . "</td> ";
                echo "<td>" . (empty ($C) ? '0' : $C) . "</td> ";
                echo "<td>" . (empty ($Cpp) ? '0' : $Cpp) . "</td> ";
                echo "<td>" . (empty ($JavaScript) ? '0' : $JavaScript) . "</td> ";
                echo "<td>" . (empty ($PHP) ? '0' : $PHP) . "</td> ";
                echo "<td>" . (empty ($Python) ? '0' : $Python) . "</td> ";
                echo "<td>" . (empty ($Java) ? '0' : $Java) . "</td> ";
                echo "<td>" . (empty ($Haskel) ? '0' : $Haskel) . "</td> ";
                echo "<td>" . (empty ($Clojure) ? '0' : $Clojure) . "</td> ";
                echo "<td>" . (empty ($Prolog) ? '0' : $Prolog) . "</td> ";
                echo "<td>" . (empty ($Scala) ? '0' : $Scala) . "</td> ";
                echo "</tr>";
                
                // echo "Pascal: ";
                // echo (empty ($Pascal) ? '0' : $Pascal) . "</br>";
                // echo "C: ";
                // echo (empty ($C) ? '0' : $C) . "</br>";
                // echo "C++: ";
                // echo (empty ($Cpp) ? '0' : $Cpp) . "</br>";
                // echo "JavaScript: ";
                // echo (empty ($JavaScript) ? '0' : $JavaScript) . "</br>";
                // echo "PHP: ";
                // echo (empty ($PHP) ? '0' : $PHP) . "</br>";
                // echo "Python: ";
                // echo (empty ($Python) ? '0' : $Python) . "</br>";
                // echo "Java: ";
                // echo (empty ($Java) ? '0' : $Java) . "</br>";
                // echo "Haskel: ";
                // echo (empty ($Haskel) ? '0' : $Haskel) . "</br>";
                // echo "Clojure: ";
                // echo (empty ($Clojure) ? '0' : $Clojure) . "</br>";
                // echo "Prolog: ";
                // echo (empty ($Prolog) ? '0' : $Prolog) . "</br>";
                // echo "Scala: ";
                // echo (empty ($Scala) ? '0' : $Scala) . "</br>";
            ?>
        <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>" />
    </form>
</body>

</html>