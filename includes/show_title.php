<?php
//выбор сообщений по 1-10 11-20 21-30...
$count_mes = count_informs($db, $username);
$page = filter_input(INPUT_GET, 'page');
if (is_null($page) || $page < 0) {
    $page = 0;
}
if (($page * 10 + 11) > $count_mes) {
    $end = $count_mes;
} else {
    $end = $page * 10 + 10;
}

//левая панель с заголовками новостей
$result = show_text_username($db, $username, $page);
if (!$result): echo 'Ошибка1: ' . mysqli_errno($db) . ' ' . mysqli_error($db);
    ?>

<?php else : ?>
    <div id="lsb">
        <!--выбор сообщений 1-10 11-20 ... -->
        <?php if ($page > 0) : ?>
            <a href="index.php?page=<?= $page - 1; ?>"> <<<</a>
        <?php endif; ?> 
        <a href="index.php?page=<?= $page; ?>"> <?= $page * 10 + 1; ?>-<?= $end; ?></a>
        <?php if ($end !== $count_mes) : ?>
            <a href="index.php?page=<?= $page + 1; ?>"> >>></a>
        <?php endif; ?>
        <!-- заголовки непрочитанных сообщений-->
        <div class="not_read">
            <h3>непрочитанные:</h3>
            <?php $count = 0 ?>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php if (is_null($row['readinform'])) : ?>
                        <?php $count++ ?>

                        <li >
                            <a href="index.php?inform_id=<?= $row['inform_id']; ?>&page=<?= $page; ?>"> <?= $row['name']; ?></a>
                        </li>

                    <?php endif; ?>

                <?php endwhile; ?>              
            </ul>
            <?php if ($count == 0): ?>
                <h3>нет</h3>
            <?php endif; ?>  
        </div>
        <!-- заголовки прочитанных сообщений-->
        <div class="read">
            <h3>прочитанные:</h3>
            <?php $count = 0 ?>
            <ul >
                <?php $result = show_text_username($db, $username, $page); ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                    <?php if (!is_null($row['readinform'])) : ?>
                        <?php $count++ ?>
                        <li >
                            <a href="index.php?inform_id=<?= $row['inform_id']; ?>&page=<?= $page; ?>"><?= $row['name']; ?></a>
                        </li>
                    <?php endif; ?>
                <?php endwhile; ?>               
            </ul>
            <?php if ($count == 0): ?>
                <h3>нет</h3>
            <?php endif; ?> 
        </div>
    </div> 

<?php endif; ?>