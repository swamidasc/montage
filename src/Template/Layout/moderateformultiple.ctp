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

$cakeDescription = 'Montage';

if (isset($this->request->params['pass'][1]) && $this->request->params['pass'][1] == "escalated") {
 	$subheader = " - Escalated";
} else {
	$subheader = "";
}




?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="cache-control" content="no-store" />
	<title><?= $cakeDescription?>: <?=$project->name?></title>

	<script type="text/javascript" src="//use.typekit.net/iwh4hkt.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
    <?= $this->Html->css('main_multiple.css?'.md5(uniqid(rand(), true))); ?>
    <?= $this->Html->css('jquery-confirm.css') ?>
    <?= $this->Html->meta('icon') ?>

	<!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>


<body class="preload moderate">
	
	
	<?= $this->Flash->render() ?>
	<?= $this->fetch('content') ?>
	
	
		
<script>
	window.setInterval(function(){ 
		var modURL<?=$project->id?> = "<?=$apiURL?>?action=getStats&ce=<?=$project->client_escalate?>&tn=<?=$project->table_name?>&ut=<?=$role?>&modid=<?=$thisuser?>";
		<?php if ($project->project_type_id == 3 || $project->project_type_id == 5) { $vidid = key($images); ?>
			modURL<?=$project->id?> += "&vidid=<?=$vidid?>";
		<?php } ?>
		
		$.ajax({
			type: 'GET',
    			url: modURL<?=$project->id?>,
			jsonpCallback: 'callback<?=$project->id?>',
			contentType: "application/json",
			dataType: "jsonp",
			success: function( response<?=$project->id?> ) { 	
				$.each(response<?=$project->id?>.stats[0], function(index, element) {
					var projStat<?=$project->id?> = index + "_<?=$project->id?>";	
					$("#"+projStat<?=$project->id?>).html(response<?=$project->id?>.stats[0][index]);
				});
			}
		});
	}, 5000);
	
	<?php  if ($project->project_type_id == 3 || $project->project_type_id == 5) { $vidid = key($images); ?>
		window.setInterval(function(){ 
			var skipURL<?=$project->id?> = "<?=$apiURL?>?action=checkSkip&tn=<?=$project->table_name?>&vidid=<?=$images[$vidid]['imgid']?>";
				$.ajax({
				type: 'GET',
				url: skipURL<?=$project->id?>,
				jsonpCallback: 'skipcallback<?=$project->id?>',
				contentType: "application/json",
				dataType: "jsonp",
				success: function( skip<?=$project->id?> ) { 	
					if (skip<?=$project->id?>.found == 1) {
						$("#live_iframe").attr("src", "/livestreamend.html");
						$("#livestream1 input").prop("disabled", true);
					}
				}
		});
		}, 30000);
	
	<?php } ?>
	
</script> 
    <?= $this->Html->script('jquery.js') ?>
    <?= $this->Html->script('jquery.cookie.js') ?>
    <?= $this->Html->script('jquery-confirm.js') ?>
    <?php if ($project->project_type_id != 3  && $project->project_type_id != 5) { ?>
    <?= $this->Html->script('jquery.cycle.all.js') ?>
    <?= $this->Html->script('jquery.fancybox.pack.js') ?>
    <?php if ($project->tagtimes == 1) { ?>
	 <?= $this->Html->script('main_videotimestamp.js?'.md5(uniqid(rand(), true))); ?>   
	<?php } else  { ?>
    <?= $this->Html->script('main.js?'.md5(uniqid(rand(), true))); ?>
    <?php } ?>
	<?php } else { ?>
	<?= $this->Html->script('main_livestream.js?'.md5(uniqid(rand(), true))); ?>   
    <?php } ?>
<script>
$.cookie("reload", "0");
</script>
</body>
</html>
