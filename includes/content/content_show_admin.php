<?php
include_once 'includes/config.php';
include_once 'functions/function.php';

$query = 'SELECT * FROM users';
$result = mysqli_query($db, $query);
if (!$result) {
    $mes_error .= 'not result' . ' ' . mysqli_errno($db) . ' ' . mysqli_error($db);
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        if (!$row ['admin_is']) {
            $res .= '<option>' . $row ['name'] . '</option>';
        }
    }
}
//получаем id последнего сообщения
$query = 'SELECT id FROM informs ORDER BY id DESC LIMIT 1';
$result2 = mysqli_query($db, $query);
if (!$result2) {
    $mes_error .= 'not result' . ' ' . mysqli_errno($db) . ' ' . mysqli_error($db);
} else {
    $result2 = mysqli_fetch_assoc($result2);
    $count = $result2['id'];
}
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
    } else {
        $mes .= 'сообщение добавлено<br>';
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
        } else {
            $mes .= 'сообщение связано с юзером<br>';
        }
    }
}

if (filter_input(INPUT_POST, 'submit_form')) {

    $name_user = filter_input(INPUT_POST, 'name');
    rights($db, $name_user);
}

$query_right = 'SELECT * FROM `users`';
$result_right = mysqli_query($db, $query_right);
?>
<header>

    <h1>Администратор : <span><?= $username; ?></span></h1>
    <a href="../includes/logout.php">ВЫЙТИ</a>

</header>
<main>
    <div id="lsb" class='margin'>
        <table >
            <caption><h3>Добавить администратора</h3></caption>
            <?php while ($row = mysqli_fetch_assoc($result_right)): ?>
                <tr>                    
                    <?php if (!$row['admin_is']): ?>
                        <td><?= $row['name'] ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="name" value="<?= $row['name'] ?>"/>
                                <input class='admin' type="submit" value="добавить" name="submit_form"/>
                            </form>
                        </td>
                    <?php endif; ?>  
                </tr>                
            <?php endwhile; ?>
        </table>
    </div>

    <div id="reg">
        <h3>Форма отправки сообщения</h3>
        <p class="err"><?= $mes_error ?></p>
        <p class="norm"><?= $mes ?></p>
        <form action="#" method="GET" name="message" >
            <label><p>Выберите пользователей:</p>
                <select class='select' size="2" multiple name="users[]">
                    <?php echo $res; ?>
                </select>
            </label>
            <br/>
            <label>Заголовок сообщения:
                <input type="text" name="title"/>
            </label>
            <br/>
            <label>Текст сообщения:
                <textarea rows="10" cols="45" name="text"></textarea>
            </label>
            <br/><br/>
            <input type="submit" value="Отправить">
        </form>
    </div>

</main>