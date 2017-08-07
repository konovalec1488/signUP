<header>
    <h1>РЕГИСТРАЦИЯ</h1>
</header>
<main>
        <div id="reg">
            <h3>РЕГИСТРАЦИЯ</h3>
            <p class="err"><?= $mes_er ?></p>
            <p class="norm"><?= $mes ?></p>
            <form method="POST">
                <input type="text" name="login" placeholder="Логин" value="<?= $log; ?>">
                <input type="email" name="email" placeholder="Email" value="<?= $em; ?>">
                <input type="password" name="pass1" placeholder="Пароль">
                <input type="password" name="pass2" placeholder="Пароль повторно">
                <input type="submit" name="reg" value="РЕГИСТРАЦИЯ">
            </form>
            <div class="signup"><?= $sign ?></div>
        </div>
</main>
