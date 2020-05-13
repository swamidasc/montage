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
	<header>
		<div class="container">
			<h1><a href="/">Montage: WebPurify Moderation System</a></h1>
			<h2>
				<?php if ($grouplang == "cz") { ?>
				中国Customs自由定制鞋
				<?php } else {?> 
				<?php echo $project->name; ?>  <?php echo $subheader?>
				<?php } ?>
			</h2>
			<a href="javascript:void(0);" id="menu"><span></span></a>
			<?php if ($project->project_type_id == 3 || $project->project_type_id == 5) { ?>
			<div class="nav">
				<ul>
					<?php if ($role != 6) {?>
					<li><h5>Submitted</h5><h3 id="submitted_<?=$project->id?>"><?php echo $stats->submitted ?></h3></li>
					<li><h5>Unmonitored</h5><h3 id="unmonitored_<?=$project->id?>"><?php echo $stats->unmonitored ?></h3></li>
					<li><h5>Completed</h5><h3 id="completed_<?=$project->id?>"><?php echo $stats->completed ?></h3></li>	
					<?php } ?>
					<?php if ($role != 6) {?>
					<li><h5>Longest Wait</h5><h3 id="longest_<?=$project->id?>"><?php echo $stats->longest ?></h3></li>
					<?php } ?>
					<li class="meta help"><a href="javascript:void(0);" class="ion-help-circled"></a></li>
					<?php if ($project->project_type_id == 3) { ?>
					<li class="meta refresh"><a href="javascript:void(0);" class="ion-refresh-circled"></a></li>
					<?php } else { ?>
					<li class="meta info"><a href="javascript:void(0);" class="ion-information-circled"></a></li>
					<?php } ?>
					<li class="meta close"><a href="javascript:void(0);" class="ion-close-circled"></a></li>
				</ul>
			</div>				
			<?php } else { ?>
			<div class="nav">
				<ul>
					<?php if ($role != 6) {?>
					<li><h5><?php if ($grouplang == "cz") { ?>已提交<?php } else { ?>Submitted<?php } ?></h5><h3 id="submitted_<?=$project->id?>"><?php echo $stats->submitted ?></h3></li>
					<li><h5><?php if ($grouplang == "cz") { ?>已审核<?php } else { ?>Moderated<?php } ?></h5><h3 id="moderated_<?=$project->id?>"><?php echo $stats->moderated ?></h3></li>
					<li><h5><?php if ($grouplang == "cz") { ?>待审核<?php } else { ?>Pending<?php } ?></h5><h3 id="pending_<?=$project->id?>"><?php echo $stats->pending ?></h3></li>
					<?php if ($stats->timelength) { ?>		
					<li><h5>Length Pending</h5><h3 id="timelength_<?=$project->id?>"><?php echo $stats->timelength ?></h3></li>				
					<?php } ?>					
					<?php } ?>
					<li><h5><?php if ($grouplang == "cz") { ?>暂缓<?php } else { ?>Escalated<?php } ?></h5><h3 id="escalated_<?=$project->id?>"><?php echo $stats->escalated ?></h3></li>
					<?php if ($role != 6) {?>
					<li><h5><?php if ($grouplang == "cz") { ?>图片队列<?php } else { ?>Longest Wait<?php } ?></h5><h3 id="longest_<?=$project->id?>"><?php echo $stats->longest ?></h3></li>
					<?php } ?>
					<li class="meta help"><a href="javascript:void(0);" class="ion-help-circled"></a></li>
					<li class="meta info"><a href="javascript:void(0);" class="ion-information-circled"></a></li>
					<li class="meta close"><a href="javascript:void(0);" class="ion-close-circled"></a></li>
				</ul>
			</div>
			<?php } ?>
		</div>
	</header>
	
	
	<div class="checkProfanity">
		<form id="profanityCheck">
			<input type="hidden" name="projectid" value="<?=$project->id?>" id="projectid" />
			<p><span></span><input id="profanitytext" type="text" placeholder="Try it out..." autocomplete="off"/></p>
			<input type="submit" value="Check for Profanity" id="profanitybutton" class="btn" />
		</form>
		<div id="profanityResults"></div>
	</div>
	
	<div id="criteria">
		<?= $project->criteria?>
	</div>

	<?= $this->Flash->render() ?>
	<?= $this->fetch('content') ?>
	
	<footer>
		<div class="wrap">
	    <p>Copyright &copy;<?php echo date("Y");?> WebFurther, LLC. All rights Reserved</p>
		</div>
	</footer>
	
		
<script>
	window.setInterval(function(){ 
		var modURL<?=$project->id?> = "<?=$apiURL?>?action=getStats&ce=<?=$project->client_escalate?>&tn=<?=$project->table_name?>&ut=<?=$role?>&modid=<?=$thisuser?>";
		<?php if ($project->project_type_id == 3) { $vidid = key($images); ?>
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
    <?php if ($project->project_type_id != 3) { ?>
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
