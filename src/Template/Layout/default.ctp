<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */



$cakeDescription = 'Montage: WebPurify Moderation System';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<title><?= $cakeDescription ?>: <?= $this->fetch('title') ?></title>

	<script type="text/javascript" src="//use.typekit.net/iwh4hkt.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
    <?= $this->Html->css('main.css') ?>
    <?= $this->Html->css('jquery.datetimepicker.css') ?>
    <?= $this->Html->meta('icon') ?>
    
		<?php if ($grouplang == "cz") {
    	echo $this->Html->css('cz.css');
		} ?>    

	<!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>


<body class="preload">
	<div id="wp">	
	<header <?=$training==1?"id=\"training\"":""?>>
		<h1><a href="/projects">Montage: WebPurify Moderation System</a></h1>					
		<?php if (isset($_SESSION['Auth']['User'])) { ?>
		<a href="javascript:void(0);" id="menu"><span></span></a>		
		<ul class="desktop">
			<li>
			<?php if ($role >= 5) {?><a href="/users/edit/<?=$_SESSION['Auth']['User']['id']?>"><?php } ?>		
			<?=$_SESSION['Auth']['User']['email']?>
			<?php if ($role >= 5) {?></a><?php } ?>
			</li>
			<li class="desktop">
				<?=$_SESSION['Auth']['User']['Userstype']?>
			</li>
			<li><a href="/users/logout">Logout</a></li>
		</ul>
		<?php } ?>
	</header>


	<?php if (isset($_SESSION['Auth']['User'])) { ?>
	<nav>
		<ul>
		<?php if ($role == 1) { ?>
			<li<? echo ' class="current" '; ?>><a href="/Reports/selectData">OCR-Reports</a></li>
			<li<? if($this->request->params['controller'] == "Groups" && $this->request->params['action'] != "editips") echo ' class="current"'; ?>><a href="/groups">Moderation Groups</a></li>
			<li<? if($this->request->params['controller'] == "Clients") echo ' class="current"'; ?>><a href="/clients">Clients</a></li>
		<?php } ?>
		<?php if ($role && $role <= 3) { ?>
			<li<? if($this->request->params['controller'] == "Projects") echo ' class="current"'; ?>><a href="/projects">Projects</a></li>
			<li<? if($this->request->params['controller'] == "Users") echo ' class="current"'; ?>><a href="/users">Users</a></li>
		<?php } ?>
		<?php if ($role <= 3 || $_SESSION['Auth']['User']['id'] == 54 || $_SESSION['Auth']['User']['id'] == 56 || $_SESSION['Auth']['User']['id'] == 62) { ?>
			<li<? if($this->request->params['controller'] == "Qualitytests") echo ' class="current"'; ?>><a href="/qualitytests">QC Tests</a></li>
		<?php } ?>
		<?php if ($role <= 4 && $_SESSION['Auth']['User']['group_id'] == 1) { ?>
			<li<? if($this->request->params['action'] == "editips") echo ' class="current"'; ?>><a href="/settings">Settings</a></li>
		<?php } ?>
		<?php if ($role == 1 && $_SESSION['Auth']['User']['group_id'] == 1) { ?>
			<li<? if($this->request->params['action'] == "editips") echo ' class="current"'; ?>><a href="/settings">Settings</a></li>
		<?php } ?>

			<?php if ($role >= 5) {?>
				<li class="mobile"><a href="/users/edit/<?=$_SESSION['Auth']['User']['id']?>">Edit User</a>
			<?php } ?>
			</li>		
			<li class="mobile"><a href="/users/logout">Logout</a></li>
		</ul>
	</nav>
	<?php } ?>


	<?= $this->Flash->render() ?>


	<div id="content">
		<div class="wrap">
		<?= $this->fetch('content') ?>
		</div>
	</div>
	
	</div>
	<footer>
		<div class="wrap">
	    <p>Copyright &copy;<?php echo date("Y");?> WebFurther, LLC. All rights Reserved</p>
		</div>
	</footer>
	
    <?= $this->Html->script('jquery.js') ?>
    <?= $this->Html->script('jquery.cookie.js')?>
    <?= $this->Html->script('jquery.datetimepicker.full.js')?>
    <?= $this->Html->script('jquery.fancybox.pack.js') ?>
    <?= $this->Html->script('main.js') ?> 
    <script>
	    window.setInterval(function(){
			$.ajax({
				url: '/projects/keepSession',
				cache: false,
           });	
		}, 15000);
	</script>
</body>
</html>
