<?php
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'login') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'fio') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'phone') ?>
 

    <button>Зарегистрироваться</button>

<?php ActiveForm::end(); ?>