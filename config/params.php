<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'icon-framework' => 'fa',  // Font Awesome Icon framework
    //'uploadHostInfo' => 'http://mysite.local/upload', // Показываем отсюда
    'uploadPath' => dirname(__DIR__, 2) . '/web/upload/', // Загружаем сюда
    'user.passwordResetTokenExpire' => 3600,
    'supportEmail' => 'robot@devreadwrite.com'
];
