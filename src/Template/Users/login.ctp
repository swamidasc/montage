<?php
if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == "http" && $_SERVER['HTTP_HOST'] == "montage.webfurther.com") {
	header("Location: https://montage.webfurther.com/users/login");	
	exit;
}
?>
<?= $this->Form->create('Login', array('id' => 'login','autocomplete' => 'off')) ?>
<h4>Log In</h4>
<p class="infield"><?= $this->Form->input('email',array('autocomplete' => 'off')); ?></p>
<p class="infield"><?= $this->Form->input('password',array('autocomplete' => 'off')); ?></p>
<p><?= $this->Form->button('Log In', array('class' => 'btn')) ?></p>
<?= $this->Form->end() ?>
