<?php

// открытие сессии 
session_start();

/**
 * Функция проверяет является ли пользователь админом
 * @param int $userid
 * id пользователя
 * @return string
 * админ или юзер
 */
function is_admin($db, $username) {
    $query = "SELECT  `admin_is` FROM `users` WHERE name='$username'";
    $result = mysqli_query($db, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $is_admin = $row['admin_is'];
        if ($is_admin) {
            return true;
        }
    }
    return false;
}

/**
 * функция проверяет при помощи функции is_admin() права, и если 'user' - то изменяет их на 'admin'
 * 
 * @param int $iduser
 *  id пользователя
 */
function rights($db, $iduser) {


    //echo 'подлючение успешно';
    if (!is_admin($db, $iduser)) {
        $query = "UPDATE `users` SET `admin_is` = '1' WHERE `name` = '$iduser'";
        $result = mysqli_query($db, $query);
        if (!$result) {
            echo 'результат не был получен: ' . mysqli_error($db) . ' ' . mysqli_errno($db);
        } else {
            echo 'права пользователя изменены на админские';
        }
    }
}

/**
 * 
 * функция принимает базу данных
 * номер пользователя
 * возвращает выборку заголовки и текст сообщений для пользователя $username
 */
function show_text_username($db, $username, $page) {
    $offset = $page * 10;
    $query = 'SELECT informs.name,informs.inform,informs_users.readinform,informs_users.inform_id FROM informs INNER JOIN informs_users ON informs.id=informs_users.inform_id INNER JOIN users ON users.id=informs_users.user_id WHERE users.name="' . $username . '" limit ' . $offset . ',10';
    return mysqli_query($db, $query);
}

/**
 * функция принимает базу данных и номер пользователя
 * функция принимает базу
 * номер пользователя
 * @param type $inform_id
 * и возвращает сообщение по inform_id
 * функция при нажатии на ссылку сообщения меняет readinform не null
 */
function get_inform($db, $username, $inform_id) {

    //запрос на изменение readinform не null при вызове этой функции
    $query_informs_users_id = "SELECT informs_users.id,informs_users.readinform FROM informs_users INNER JOIN users on informs_users.user_id=users.id WHERE users.name='$username' AND informs_users.inform_id='$inform_id' limit 1";
    $result = mysqli_query($db, $query_informs_users_id);
    $array = mysqli_fetch_assoc($result);
    if (is_null($array['readinform'])) {
        if (is_null($array['id'])) {
            echo 'problem3:' . mysqli_errno($db) . ' ' . mysqli_error($db);
        } else {
            $query_readinform = "UPDATE informs_users SET readinform = 1 WHERE informs_users.id = '" . $array['id'] . "'";
            $res = mysqli_query($db, $query_readinform);
            return $res;
        }
    }

    //запрос на получение сообщения по inform_id
    $query_informs_users_id = "SELECT informs.inform,informs.name FROM informs WHERE informs.id='" . $inform_id . "'";
    return mysqli_query($db, $query_informs_users_id);
}

/**
 * 
 * принимает username
 * функция проверяет есть ли username в $_SESSION['username']
 * Возвращает имя или false
 */
function username_SESSION($username) {
    $username = Fix($_POST['username']); //имя пользователя
    if (isset($_SESSION['USERNAME'])) {
        $username = $_SESSION['USERNAME']; //устанавливает, совпадает ли имя пользователя с сессионным 
        echo "Добропожаловаль .$username"; // die ("<p><a href='continue.php'>Щелкните здесь для продолжения</a></p>");
    } else {//return FALSE;
        echo "Пожалуйста, для входа <a href='authenticate.php'> щелкните здесь</a>";
    }
    session_write_close(); //закрытие сессии
}

function Fix($str) { //очистка полей
    $str = trim($str);
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str); //Удаляет экранирование символов, произведенное функцией addslashes()
    }
    return mysql_real_escape_string($str);
}

/**
 * 
 * @param type $db
 * @param type $username
 * @return type
 * количесво сообщений для юзера
 */
function count_informs($db, $username) {
    $query = 'SELECT count(informs_users.inform_id) as count FROM informs_users INNER JOIN users ON users.id=informs_users.user_id WHERE users.name="' . $username . '"';
    $res = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($res);
    return $row['count'];
}

/**
 * проверка не прочитанных сообщений
 * @param type $username
 * @return type
 */
function unread_messages($db, $username) {
    $query = "select COUNT(*) FROM informs_users INNER JOIN users ON informs_users.user_id=users.id WHERE users.name='" . $username . "' AND informs_users.readinform IS NULL";
    $count_inform = mysqli_query($db, $query);
    return mysqli_fetch_row($count_inform)[0];
}

/**
 * проверка верификации авторизации
 * @param type $password
 * @param type $hash
 * @param type $db
 * @param type $row
 * @return string
 */
function verificSignUp($password, $hash, $db, $row) {
    if (password_verify($password, $hash)) {
        $_SESSION['username'] = $row['name'];
        echo '<script>location.replace("../index.php");</script>';
        exit;
        mysqli_close($db);
        exit;
    } else {
        $mes_er = 'Неверно введен пароль';
        return $mes_er;
    }
}

/**
 * проверка на заполнения полей
 * @return ошибку пустого пароля или логина
 */
function inputSignUp($login, $password) {
    if (empty($login)) {
        $mes_er = 'Введите логин!';
        return $mes_er;
    }
    if (empty($password)) {
        $mes_er = 'Введите пароль!';
        return $mes_er;
    }
}

/**
 * проверка авторизации
 * @param type $db
 * @return string
 */
function testSignUp($db) {
    $log = $_POST['login'];
    $login = mysqli_real_escape_string($db, trim($_POST['login']));
    $password = mysqli_real_escape_string($db, trim($_POST['password']));
    if (isset($_POST['sign_up'])) {
        inputSignUp($login, $password);
        $query = "SELECT * FROM `users` WHERE name = '$login'";
        $data = mysqli_query($db, $query);

        if (mysqli_num_rows($data) == 1) {
            $row = mysqli_fetch_assoc($data);
            $hash = $row['pass'];
            verificSignUp($password, $hash, $db, $row);
        } else {
            $mes_er = 'Пользователь не существует!';
            return $mes_er;
        }
    }
}

/**
 * проверка регистрации
 * @return ошибку
 */
function testReg($db) {
    if (isset($_POST['reg'])) {
        $name = mysqli_real_escape_string($db, trim($_POST['login']));
        $email = mysqli_real_escape_string($db, trim($_POST['email']));
        $pass1 = mysqli_real_escape_string($db, trim($_POST['pass1']));
        $pass = password_hash($pass1, PASSWORD_BCRYPT, array('cost' => 12));
        $pass2 = mysqli_real_escape_string($db, trim($_POST['pass2']));
        $errors = inputReg($name, $email, $pass1, $pass2, $db);
        if (empty($errors)) {
            $query = "INSERT INTO `users` (name,email, pass) VALUES ('$name','$email', '$pass')";
            mysqli_query($db, $query);
            mysqli_close($db);
            echo '<script>location.replace("../index.php");</script>';
            exit;
            exit;
        } else {
            $mes_er = $errors;
            return $mes_er;
        }
    }
}

/**
 * проверка на пустоту ячеек при региситрации
 * @param type $name
 * @param type $email
 * @param type $pass1
 * @param type $pass2
 * @param type $db
 * @return массив с ошибками
 */
function inputReg($name, $email, $pass1, $pass2, $db) {
    $errors = [];
    $log = $_POST['login'];
    $em = $_POST['email'];
    if (empty($name)) {
        $errors[] = 'Введите логин!';
    }
    if (empty($email)) {
        $errors[] = 'Введите email!';
    }
    if (empty($pass1)) {
        $errors[] = 'Введите пароль!';
    }
    if (empty($pass2)) {
        $errors[] = 'Повторный пароль введен не верно!';
    }
    if ($pass1 != $pass2) {
        $errors[] = 'Пароли не совпадают!';
    }
    $query = "SELECT * FROM `users` WHERE name = '$name'";
    $data = mysqli_query($db, $query);
    if (mysqli_num_rows($data) >= 1) {
        $errors[] = 'Логин уже существует!';
    }
    $qwert = "SELECT * FROM `users` WHERE email = '$email'";
    $dt = mysqli_query($db, $qwert);

    if (mysqli_num_rows($dt) >= 1) {
        $errors[] = 'Пользователь с таким email уже зарегистрирован';
    }
    $mes_er = array_shift($errors);
    return $mes_er;
}

function logout() {
    unset($_GET);
    unset($_SESSION['username']);
    echo '<script>location.replace("../index.php");</script>';
    exit;
}

/**
 * получаем список пользователей
 * @param type $db
 * @return кто админ 
 */
function getUsers($db) {
    $query = 'SELECT * FROM users';
    $result = mysqli_query($db, $query);
    if (!$result) {
        $mes_error .= 'not result' . ' ' . mysqli_errno($db) . ' ' . mysqli_error($db);
        return $mes_error;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            if (!$row ['admin_is']) {
                $res .= '<option>' . $row ['name'] . '</option>';
                
            }
        }
        return $res;
    }
}

/**
 * получаем id последнего сообщения
 * @param type $db
 * @return string
 */
function getIdLastMess($db) {
    //получаем id последнего сообщения
    $query = 'SELECT id FROM informs ORDER BY id DESC LIMIT 1';
    $result2 = mysqli_query($db, $query);
    if (!$result2) {
        $mes_error .= 'not result' . ' ' . mysqli_errno($db) . ' ' . mysqli_error($db);
        return $mes_error;
    } else {
        $result2 = mysqli_fetch_assoc($result2);
        $count = $result2['id'];
        return $count;
    }
}

function adminGetParam($count, $db) {
//принимаем значения с GET
    if ($_GET) {
        $title = filter_input(INPUT_GET, 'title');
        $text = filter_input(INPUT_GET, 'text');
        $count++;
        //записываем сообщение
        $query = "INSERT INTO informs VALUES ($count,'$title','$text')";
        $result3 = mysqli_query($db, $query);
        if (!$result3) {
            $mes_error .= 'not result' . ' ' . mysqli_errno($db) . ' ' . mysqli_error($db);
            return $mes_error;
        } else {
            $mes .= 'сообщение добавлено';
            return $mes;
        }
        foreach ($_GET['users'] as $user) {
            //получаем user_id
            $query = "SELECT id FROM users WHERE name='$user'";
            $result4 = mysqli_query($db, $query);
            if (!$result4) {
                $mes_error .= 'not result' . ' ' . mysqli_errno($db) . ' ' . mysqli_error($db);
            } else {
                $result4 = mysqli_fetch_assoc($result4);
                $user_id = $result4['id'];
            }
            //записываем в informs_users    
            $query = "INSERT INTO informs_users VALUES (NULL,'$count','$user_id', NULL)";
            $result4 = mysqli_query($db, $query);
            if (!$result4) {
                $mes_error .= 'not result' . ' ' . mysqli_errno($db) . ' ' . mysqli_error($db);
                return $mes_error;
            } else {
                $mes .= 'сообщение связано с юзером<br>';
                return $mes;
            }
        }
    }
}

global $inform_name;
global $ms;
global $row;
/**
 * функциия обработки админа
 * @param type $db
 * @return type
 */
function showAdmin($db) {
    if (filter_input(INPUT_POST, 'submit_form')) {

        $name_user = filter_input(INPUT_POST, 'name');
        rights($db, $name_user);
    }

    $query_right = 'SELECT * FROM `users`';
    $result_right = mysqli_query($db, $query_right);
    return $result_right;
}

global $inform_name;
global $ms;
global $row;
$mes='';
$inform_name='';
$row='';

function showTextMess($db,$username) {
    $inform_id = filter_input(INPUT_GET, 'inform_id');
    if (is_null($inform_id)) {
        $mes = 'ВЫБЕРИТЕ СООБЩЕНИЕ';
    } else if ($inform = get_inform($db, $username, $inform_id)) {
        $row = mysqli_fetch_assoc($inform);
        $inform_name = $row['name'];
        $mes = $row['inform'];

    } else {
        $mes = 'Ошибка2:' . mysqli_errno($db) . ' ' . mysqli_error($db);

    }
}
