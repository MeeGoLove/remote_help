<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\models\LoginForm */
 
     use yii\helpers\Html;
     use yii\bootstrap\ActiveForm;
      
     $this->title = 'Вход в систему';
     $this->params['breadcrumbs'][] = $this->title;
     ?>
      
     <div class="site-login">
         <h1><?= Html::encode($this->title) ?></h1>
         <p>Для работы в системе Вам необходимо пройти авторизацию:</p>
      
         <?php $form = ActiveForm::begin([
             'id' => 'login-form',
             'layout' => 'horizontal',
             /*'fieldConfig' => [
                 'template' => "{label}\n<div class=\"col-md-6\">{input}</div>\n<div class=\"col-md-6\">{error}</div>",
                 'labelOptions' => ['class' => 'col-md-6 control-label'],
             ],*/
         ]); ?>
             <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
             <?= $form->field($model, 'password')->passwordInput() ?>
             <?= $form->field($model, 'rememberMe')->checkbox(/*[
                 'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
             ]*/) ?>
             <div style="color:#999;margin:1em 0">
                 Если Вы забыли пароль, то Вы можете его <?= Html::a('сбросить', ['site/request-password-reset']) ?>.
             </div>
             <div class="form-group">
                 <div class="col-lg-offset-1 col-lg-11">
                     <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                 </div>
             </div>
         <?php ActiveForm::end(); ?>
      
     </div>