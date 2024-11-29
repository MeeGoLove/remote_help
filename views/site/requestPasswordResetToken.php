<?php
     
     use yii\helpers\Html;
     use yii\bootstrap\ActiveForm;
      
     $this->title = 'Сброс пароля';
     $this->params['breadcrumbs'][] = $this->title;
     ?>
      
     <div class="site-request-password-reset">
         <h1><?= Html::encode($this->title) ?></h1>
         <p>Укажите Ваш email, при помощи которого Вы регистрировались в системе. Ссылка для сброса пароля будет отправлена на указанный Вами адрес email.</p>
         <div class="row">
             <div class="col-lg-5">
      
                 <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                     <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                     <div class="form-group">
                         <?= Html::submitButton('Отправить запрос на сброс пароля', ['class' => 'btn btn-primary']) ?>
                     </div>
                 <?php ActiveForm::end(); ?>
      
             </div>
         </div>
     </div>