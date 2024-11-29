<?php
     
     namespace app\models;
      
     use Yii;
     use yii\base\Model;
      
     /**
      * Password reset request form
      */
     class PasswordResetRequestForm extends Model
     {
         public $email;
      
         /**
          * @inheritdoc
          */
         public function rules()
         {
             return [
                 ['email', 'trim'],
                 ['email', 'required'],
                 ['email', 'email'],
                 ['email', 'exist',
                     'targetClass' => '\app\models\User',
                     'filter' => ['status' => User::STATUS_ACTIVE],
                     'message' => 'В системе нет пользователя, зарегистрированного под данным адресом email, проверьте корректность ввода данных!.'
                 ],
             ];
         }
      
         /**
          * Sends an email with a link, for resetting the password.
          *
          * @return bool whether the email was send
          */
         public function sendEmail()
         {
             /* @var $user User */
             $user = User::findOne([
                 'status' => User::STATUS_ACTIVE,
                 'email' => $this->email,
             ]);
      
             if (!$user) {
                 return false;
             }
      
             if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                 $user->generatePasswordResetToken();
                 if (!$user->save()) {
                     return false;
                 }
             }
      
             return Yii::$app
                 ->mailer
                 ->compose(
                     ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                     ['user' => $user]
                 )
                 ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                 ->setTo($this->email)
                 ->setSubject('Сброс пароля для ' . Yii::$app->name)
                 ->send();
         }

         public function attributeLabels()
         {
            return [
          
                'email' => 'Адрес email'
            ];
         }
      
     }