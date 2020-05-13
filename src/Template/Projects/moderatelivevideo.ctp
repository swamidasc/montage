<?php
	if (isset($this->request->params['pass'][1]) &&  $this->request->params['pass'][1] == "escalated") {
		$escalated = 1;
	} else {
		$escalated = 0;
	}	

    if ($tag_project == 1) {
	    $tagged = "tags";
    }   
?>
<input type="hidden" name="tn" id="tn" value="<?=$project->table_name?>" /> 
<input type="hidden" name="endpoint" id="endpoint" value="<?=$project->endpoint?>" /> 
<input type="hidden" name="modid" id="modid" value="<?=$_SESSION['Auth']['User']['id']?>" /> 
<input type="hidden" name="slideid" id="slideid" value="0" /> 
<?php if ($confirm) { ?>
<input type="hidden" name="confirm[]" id="confirm" value="<?=implode(',',array_keys($confirm))?>" />
<input type="hidden" name="confirmname[]" id="confirmname" value="<?=implode(',',$confirm)?>" />
<input type="hidden" name="confirmskip" id="confirmskip" value="0" />
<?php } ?>
<div id="overlayBG"></div>
<div id="overlayProfanityBG"></div>
<?php if ($tag_project != 1) { ?>
<div id="modliveVid">
<?php } else { 
  
  // --------- Add Project IDs Here --------- //
  $largeList = 1;
?>


<div id="mod" class="live <? echo isset($tagged) ? $tagged : ''; echo $largeList ? ' large' : '';?>">
<div id="modVid" class="<?php echo $project->id; echo $largeList ? ' large-list' : '';?>">
  
<?php } ?>
	<?php if (!isset($images)) { ?>
	<? /* ==========================================
		       No Images to Moderate	
	================================================ */ ?>
		<div id="last" class="slide">
			<?php if (isset($locked)) { ?>			
				<p>The following moderators have locked videos:</p>			
				<?php foreach ($locked as $lockkey => $lockedmod) { ?>
					<?=$lockedmod['email']?> has <?=$lockedmod['total']?> <?=$project->name?> video(s) locked. <a href="/projects/clearlocksbymodid/<?=$project->id?>/<?=$lockkey?>">Release Lock</a><br>
				<?php } ?>
			<?php } ?>
			<h3 id="lastSlide">No <?=$escalated==1?"Escalated":""?> Live Streams to Moderate. <span>Looking for more...</span></h3>
			<?php if (isset($quote) && $quote != "") {?>
			<center><p><div style="width: 500px"><i><?=$quote?><br>-- <?=$quoteauthor?></i></div></p></center>
			<?php } ?>			
		</div>	
		<script type="text/javascript">
		setTimeout(function(){ reloadFunc(); }, 5000);
		function reloadFunc() {
			$.cookie("reload", "1");
			location.reload();
		}
		</script>		

	<? /* ==========================================
		       First Slide	
	================================================ */ ?>
	
			
	<?php } else { ?>
	<!-- slide 1 -->
	
			<?php
			//	if ($escalated  == 1) {			
			//		for ($x = 0; $x <= count($project->labels) -1; $x++) {				
			//			if ($project->labels[$x]['statval'] == $image['status']) {
			//				$esccolor = $project->labels[$x]['color'];
			//				$escname = $project->labels[$x]['name'];
			//				$escvalue = $project->labels[$x]['statval'];
			//			}
			//		}	
			//	}			
			?>	
	<?php if($tag_project == 1) { 	
			$imgdef_page = $imgdef;
	?>
	<form id="livestream1">
		<div class="slide s<?=isset($numImages) ? $numImages : count($images);?>">
			<?php foreach ($images as $imagekey=>$image) { 
				// add a name and ID
				$linkurlorig = str_replace("<iframe","<iframe id='live_iframe' name='live_iframe' scrolling='yes' ",$image['iurl']);
				$linkurl = str_replace("src=/","src=",$linkurlorig);	
			?>	
				<div class="modimg">
					
					<input type="hidden" name="vidid" id="vidid" value="<?=$imagekey?>" />	
								
					<div id="link_<?=$imagekey?>" class="holder <?=strtolower($imgdef['name'])?>" data-imgid="<?=$imagekey?>" style="background-color:<?=$imgdef['color']?>">
  					<div class="videoContainer">
  					<h4 id="stopWatch" >0</h4>
						<?=$linkurl?> </iframe>
						</div>
					
					</div>
					<input type="hidden" id="val_<?=$imagekey?>" name="<?=$imagekey?>" value="<?=$escalated  == 1?"-2":$imgdef['statval']?>" class="status" /> 
					
					<div id="labels_<?=$imagekey?>" class="labels">
					<ul>
					<?php 
						foreach ($project->labels as $label) {
							
							if($label['tag'] == 0) {
								$type = 'radio';
							} else {
								$type = 'checkbox';
							}
							
							if($label['def'] == 1) {
								$default = 'checked="checked" data-default="true"';
								$style = 'style="background-color:'.$label['color'].'"';
							} else {
								$default = '';
								$style = '';
							}
							
							if ($label['pinginplace'] == 1) {
								$jsping = "onclick=(pinginplace(".$label['statval']."))";
							} else if ($type == 'radio') {
								$jsping = "onclick=(pinginplace('off'))";
							}
							
							
							$id = str_replace(' ', '_', strtolower($label['name']).'_'.$imagekey);
							echo '<li>';
							
							if ($label['statval'] == 101) {
								$id = "watching";
							}
							
							echo '<input type="'.$type.'" name="'.$type.'_'.$imagekey.'" value="'.$label['statval'].'" id="'.$id.'" data-imgid="'.$imagekey.'" data-color="'.$label['color'].'" '.$default.' '.$jsping.' />';
						    echo '<label for="'.$id.'" '.$style.'>'.$label['name'].'</label>';
						    echo '</li>';
						}					
					?>
						<li><span id="inplacebutton"><a id="inPlaceSubmit" class="btn">Moderate</a></span></li>
					</ul>
				    </div>					    			
				</div>				
			<?php } ?>
		</div>
		
		</form>

	<?php } ?>
				  
   <?php } ?>
</div>
			<?php if (($image['iurl'] && $escalated == 1 && $project->escalatememo == 1) || ($project->modmemo == 1 && $image['iurl'])) { ?>
				<?php 
					$image['iurl'] = str_replace("src=/","src=",$image['iurl']);	
					$linkurl1 = str_replace('<iframe src="','',$image['iurl']);
					$linkurl1 = str_replace('<iframe src=','',$linkurl1);
					$linkurl = str_replace('" height="400" width="700" />','',$linkurl1);					
					$linkurl = str_replace(' height=400 width=700 />','',$linkurl);		
					
					if (strpos($linkurl, 'iframe') !== false) {
						$linkurl = "";
					}				
					
					
							
				?>	
					<form id="livestream1_comments" class="comments">
					<input type="hidden" name="vidid" id="vidid" value="<?=$imagekey?>" />		
  				<div id="reason_<?=$imagekey?>" class="reasonMemo">
  					<p><strong>Stream ID:</strong> <?=$image['imgid']?></p>
  					<p><strong>Stream URL: <?=$linkurl?></strong> 
  					<div style="display: none" id="memoButtonSpan">
  					  <h4>Details:</h4>
  						<p><textarea name="memo_<?=$imagekey?>" id="memo_<?=$imagekey?>"><?php if (isset($image['memo'])) { ?><?=$image['memo']?><?php } ?></textarea></p>
              <a href="javascript:void(0);" id="memoButton" class="btn">Submit Memo</a>
  					</div>
  				</div>
				</form>
			<?php } ?>		
<?php if ($tag_project == 1) { ?>
</div>
<?php } ?>
	
<script type="text/javascript">    
	function pinginplace(val) {
		// show submit button:		
		if (val == "off") {
			$("#inplacebutton").css("display", "none");
		} else {
			$("#inplacebutton").css("display", "block");	
		}
	}	
</script>

<?php
	function seoUrl($string) {
	    $string = strtolower($string);
	    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	    $string = preg_replace("/[\s-]+/", " ", $string);
	    $string = preg_replace("/[\s_]/", "-", $string);
	    return $string;
	}
?>