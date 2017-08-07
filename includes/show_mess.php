<?php

//страница отображения сообщений пользователю - не админу
include_once 'includes/config.php';
include_once 'functions/function.php';

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
include_once 'content/content_show_mess.php';


