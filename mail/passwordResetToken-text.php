<?php
    $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
    ?>
     
    Привет, <?= $user->username ?>,
    Для сброса пароля перейди по следующей ссылке:
    <?= $resetLink ?>