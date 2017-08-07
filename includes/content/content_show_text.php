<content>
    <div class="username">
        <h3> непрочитанных сообщений : <?= unread_messages($db, $username); ?> </h3>
    </div>
    <div class="title">
        <p><?= $inform_name ?></p>
    </div>
    <div class="text">
        <p> <?= $mes ?> </p>
    </div>
</content>