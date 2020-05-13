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
<div id="loading"><span></span></div>
<div id="overlayBG"></div>
<div id="overlayProfanityBG"></div>


<?php if ($tag_project != 1) { ?>
<div id="modVid">
<?php } else { ?>
<div id="mod" class="<?=isset($tagged) ? $tagged : '';?>">
<div id="modVid" class="<?php echo $project->id; echo ($project->id == 190 || $project->id == 142 || $project->largelist == 1 ||  $project->id == 195 || $project->id == 155) ? ' large-list' : ''; echo ($project->vidtitlethumb == 1) ? ' amazon' : '';?>">
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
			<h3 id="lastSlide">No videos to moderate. <span>Looking for more...</span></h3>
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
				if ($escalated  == 1) {			
					for ($x = 0; $x <= count($project->labels) -1; $x++) {				
						if ($project->labels[$x]['statval'] == $image['status']) {
							$esccolor = $project->labels[$x]['color'];
							$escname = $project->labels[$x]['name'];
							$escvalue = $project->labels[$x]['statval'];
						}
					}	
				}			
			?>	
	
	<?php if($tag_project == 1) { 	
			$imgdef_page = $imgdef;
	?>
		<div class="slide s<?=isset($numImages) ? $numImages : count($images);?>">
			<?php foreach ($images as $imagekey=>$image) { ?>			
				<div class="modimg">
					<div id="link_<?=$imagekey?>" class="holder <?=strtolower($imgdef['name'])?>" data-imgid="<?=$imagekey?>" style="background-color:<?=$escalated  == 1?"#FF7300":$imgdef['color']?>">
						
						<?php if($image['iurl']) { ?>
  						<video preload="auto" id="video_<?=$imagekey?>" width="100%" height="390px" controls="true" class="firstVid" <?php if ($project->loop_video == 1) {?>loop<?php } ?>>
  						  <source src="<?=$image['iurl']?>" type="video/mp4">
  						</video>
						<?php } ?>
						
      			<?php if (($escalated == 1 && $project->escalatememo == 1) || $project->modmemo == 1) { ?>
      			  
      			  <?php if($project->vidtitlethumb == 1) { ?>
      			  <aside>
                <h2 class="vidTitle <?php echo ($image['iurl']) ? null : 'noVid';?>">
                  <!-- Add Title Here -->
                  <?=$image['title']?>
                </h2>
                
                <!-- If there is a Video -->
                <?php if($image['iurl'] && $image['title'] &&  $image['thumb']) { ?>
                  <a class="thumbnail" data-fancybox-href="#thumbnailOverlay">
                    <!-- Add Thubnail Here -->
                    <img src="<?=$image['thumb']?>" />
                  </a>
                  <div id="thumbnailOverlay">
                    <!-- Add Thubnail Here Too -->
                    <img src="<?=$image['thumb']?>" />
                    <p>
                      <!-- Add Title Here Too -->
                      <?=$image['title']?>
                    </p>
                  </div>       
                         
                <!-- Image Only-->
                <?php } elseif ($image['thumb']) { ?>
                  <div id="imageOnly">
                    <img src="<?=$image['thumb']?>" />
                  </div>
                <?php } ?>
                                
              <?php } ?>
                
        				<div id="reason_<?=$imagekey?>" class="reasonMemo">
        					<h3>Details:</h3>
        					<textarea name="memo_<?=$imagekey?>" id="memo_<?=$imagekey?>"><?php if (isset($image['memo'])) { ?><?=$image['memo']?><?php } ?></textarea>
        				</div>

      			  <?php echo($project->vidtitlethumb == 1) ? '</aside>' : null ?>
      			  
      			<?php } ?>
      										
					</div>
					
					<input type="hidden" id="val_<?=$imagekey?>" name="<?=$imagekey?>" value="<?=$escalated  == 1?"-2":$imgdef['statval']?>" class="status" /> 
					<input type="hidden" id="time_<?=$imagekey?>" name="time_<?=$imagekey?>" value="" />
					
					<div id="labels_<?=$imagekey?>" class="labels">
					<ul>
					<?php 
						foreach ($project->labels as $label) {
							$classes = '';
							
							if($label['tag'] == 0) {
								$type = 'radio';
							} else {
								$type = 'checkbox';
							}
							
							if($label['def'] == 1 && $escalated  != 1) {
								$default = 'checked="checked" data-default="true"';
								$style = 'style="background-color:'.$label['color'].'"';
							} else if ($escalated  == 1 && $label['statval'] == -2) {
								$default = 'checked="checked" data-default="true"';
								$style = 'style="background-color:'.$label['color'].'"';								
							} else {
								$default = '';
								$style = '';
							}
							
							$id = str_replace(' ', '_', strtolower($label['name']).'_'.$imagekey);
							
						//	if(($project->id == 195 || $project->id == 155) && $label['statval'] > 15) {
						//		$classes = ' class="amazonCriteria"';
						//	}
							
							if ($project->tagtimes == 1) {
								$fortagtimes = "onclick=\"getTimestamp('video_".$imagekey."','".$id."')\"";
							} else {
								$fortagtimes = "";
							}
							
							echo '<li'.$classes.'>';
							echo '<input '.$fortagtimes.' type="'.$type.'" name="'.$type.'_'.$imagekey.'" value="'.$label['statval'].'" id="'.$id.'" data-tmstmp="" data-imgid="'.$imagekey.'" data-color="'.$label['color'].'" '.$default.'/>';
						    echo '<label for="'.$id.'" '.$style.'>'.$label['name'].'</label>';
						    echo '</li>';
						}					
					?>
					</ul>
				    </div>					
				</div>
			
			<?php } ?>
		</div>
	

	<?php } else { ?>
		<div class="slide s<?=isset($numImages02) ? $numImages02 : count($images2);?>">
			<?php foreach ($images as $imagekey=>$image):?>
				<div class="modimg">
					<a id="link_<?=$imagekey?>" class="<?=strtolower($imgdef['name'])?>" data-imgid="<?=$imagekey?>" style="background-color:<?=$escalated  == 1?"#FF7300":$imgdef['color']?>">
						<h4 id="status_<?=$imagekey?>"><?=$escalated  == 1?"escalated":$imgdef['name']?></h4>
						<video preload="auto" id="video_<?=$imagekey?>" width="100%" height="390px" controls="true" class="firstVid" <?php if ($project->loop_video == 1) {?>loop<?php } ?>>
							  <source src="<?=$image['iurl']?>" type="video/mp4">
						</video>
					</a>
					<input type="hidden" id="val_<?=$imagekey?>" name="<?=$imagekey?>" value="<?=$escalated  == 1?"-2":$imgdef['statval']?>" class="status" /> 
				</div>
			<?php endforeach; ?>
		</div>
		
    <?php } ?>
				
		<!-- Second Slide -->
	
		<?php if (isset($images2) && count($images2) > 0) { ?>
			<?php if ($tag_project == 1) { 
				$imgdef_page = $imgdef;
			?> 
		
			<div class="slide s<?=isset($numImages02) ? $numImages02 : count($images2);?><?= $project->scroll_vert ? ' vertical' : '';?>">
				<?php foreach ($images2 as $imagekey2=>$image2): ?>
					<div class="modimg">
						<div id="link_<?=$imagekey2?>" class="holder" data-imgid="<?=$imagekey2?>" style="background-color:<?=$escalated  == 1?"#FF7300":$imgdef_page['color']?>">
  						<?php if($image2['iurl']) { ?>
  							<video preload="auto" id="video_<?=$imagekey2?>" width="100%" height="390px" controls="true" <?php if ($project->loop_video == 1) {?>loop<?php } ?>>
								  <source src="<?=$image2['iurl']?>" type="video/mp4">
                </video>
              <?php } ?>                
              
							<!-- Text area for comments -->
            <?php if (($escalated == 1 && $project->escalatememo == 1) || $project->modmemo == 1) { ?>
			
      		  <?php if($project->vidtitlethumb == 1) { ?>
      			  <aside>
                <h2 class="vidTitle <?php echo ($image2['iurl']) ? null : 'noVid';?>">
                  <!-- Add Title Here -->
                  <?=$image2['title']?>
                </h2>
                
                <!-- If there is a Video -->
                <?php if($image2['iurl'] && $image2['title'] &&  $image2['thumb']) { ?>
                  <a class="thumbnail" data-fancybox-href="#thumbnailOverlay">
                    <!-- Add Thubnail Here -->
                    <img src="<?=$image2['thumb']?>" />
                  </a>
                  <div id="thumbnailOverlay">
                    <!-- Add Thubnail Here Too -->
                    <img src="<?=$image2['thumb']?>" />
                    <p>
                      <!-- Add Title Here Too -->
                      <?=$image2['title']?>
                    </p>
                  </div>       
                         
                <!-- Image Only-->
                <?php } elseif ($image2['thumb']) { ?>
                  <div id="imageOnly">
                    <img src="<?=$image2['thumb']?>" />
                  </div>
                <?php } ?>
                                
              <?php } ?>		
			
      				<div id="reason_<?=$imagekey2?>" class="reasonMemo">
      					<h3>Details:</h3>
      					<textarea name="memo_<?=$imagekey2?>" id="memo_<?=$imagekey2?>"><?php if (isset($image2['memo'])) { ?><?=$image2['memo']?><?php } ?></textarea>
      				</div>
			
      			  <?php echo($project->vidtitlethumb == 1) ? '</aside>' : null ?>
            <?php } ?>	
			
													
						</div>
						<input type="hidden" id="val_<?=$imagekey2?>" name="<?=$imagekey2?>" value="<?=$escalated  == 1?"-2":$imgdef['statval']?>" class="status" /> 
            <input type="hidden" id="time_<?=$imagekey2?>" name="time_<?=$imagekey2?>" value="" />
            <div id="labels_<?=$imagekey2?>" class="labels">
					<ul>
					<?php 
						foreach ($project->labels as $label) {
							$classes = '';

							if($label['tag'] == 0) {
								$type = 'radio';
							} else {
								$type = 'checkbox';
							}
							
							if($label['def'] == 1 && $escalated  != 1) {
								$default = 'checked="checked" data-default="true"';
								$style = 'style="background-color:'.$label['color'].'"';
							} else if ($escalated  == 1 && $label['statval'] == -2) {
								$default = 'checked="checked" data-default="true"';
								$style = 'style="background-color:'.$label['color'].'"';								
							} else {
								$default = '';
								$style = '';
							}
							
							$id = str_replace(' ', '_', strtolower($label['name']).'_'.$imagekey2);

						//	if(($project->id == 195 || $project->id == 155) && $label['statval'] > 15) {
						//		$classes = ' class="amazonCriteria"';
						//	}
							
							if ($project->tagtimes == 1) {
								$fortagtimes = "onclick=\"getTimestamp('video_".$imagekey2."','".$id."')\"";
							} else {
								$fortagtimes = "";
							}							
							
							echo '<li'.$classes.'>';
							echo '<input '.$fortagtimes.' type="'.$type.'" name="'.$type.'_'.$imagekey2.'" value="'.$label['statval'].'" id="'.$id.'" data-imgid="'.$imagekey2.'" data-color="'.$label['color'].'" '.$default.'/>';
						    echo '<label for="'.$id.'" '.$style.'>'.$label['name'].'</label>';
						    echo '</li>';
						}					
					?>
					</ul>
				</div>						
					</div>
				<?php endforeach; ?>		
			</div>		

			<?php } else { ?>
			<div class="slide">
				<?php foreach ($images2 as $imagekey2=>$image2): 					
			if ($escalated  == 1) {			
				for ($x = 0; $x <= count($project->labels) -1; $x++) {
					if ($project->labels[$x]['statval'] == $image2['status']) {
						$esccolor = $project->labels[$x]['color'];
						$escname = $project->labels[$x]['name'];
						$escvalue = $project->labels[$x]['statval'];	
					}
					
				}	
			}						
		
				?>
					<div class="modimg">
						<a id="link_<?=$imagekey2?>" class="<?=strtolower($imgdef['name'])?>" data-imgid="<?=$imagekey2?>" style="background-color:<?=$escalated == 1?"#FF7300":$imgdef['color']?>;">
							<h4 id="status_<?=$imagekey2?>"><?=$escalated  == 1?"escalated":$imgdef['name']?></h4>
							<video preload="auto" id="video_<?=$imagekey2?>" width="100%" height="390px" controls="true" <?php if ($project->loop_video == 1) {?>loop<?php } ?>>
								  <source src="<?=$image2['iurl']?>" type="video/mp4">
							</video>
						</a>
						<input type="hidden" id="val_<?=$imagekey2?>" name="<?=$imagekey2?>" value="<?=$escalated  == 1?"-2":$imgdef['statval']?>" class="status" />
					</div>
				<?php endforeach; ?>		
			</div>
			<?php } ?>
		<?php } else { ?>
		
		
			<div id="last" class="slide"><h3 id="lastSlide">No videos to moderate. <span>Looking for more...</span></h3></div>		
					
		<?php } ?>
	   
   <?php } ?>
</div>

<?php if ($tag_project == 1) { ?>
</div>
<?php } ?>
	
<a id="back" class="navBtn btn grey" href="javascript:void(0);"><em class="ion-arrow-left-b"></em></a>
<?php if (isset($images) && count($images) > 0) { ?>
	<a id="next" class="navBtn btn" href="javascript:void(0);"><em class="ion-arrow-right-b"></em></a>
<?php } ?>




<script type="text/javascript">    
	
	function getTimestamp(video,violation) {
		var vid = document.getElementById(video);
		$('#'+violation).attr( "data-tmstmp", vid.currentTime );
	}
	

	// Moderate Image
	function moderateImage(id, className){
		if (typeof className === 'undefined') {
			var className = $('#link_'+id).attr('class');
		}
	
		var statusLabel;
		var status;
		var statusVal;
		var statusColor;
		var statusText = $('#status_'+id);
		
		switch(className){
		<?php for ($x = 0; $x <= count($project->labels) -1; $x++) { ?>
		case '<?=seoUrl($project->labels[$x]['name'])?>':
			statusLabel = '<?php if (isset($project->labels[$x+1]['name'])) { echo strtolower($project->labels[$x+1]['name']); } else {  echo strtolower($project->labels[0]['name']); } ?>';
			status = '<?php if (isset($project->labels[$x+1]['name'])) { echo seoUrl($project->labels[$x+1]['name']); } else {  echo seoUrl($project->labels[0]['name']); } ?>';
			statusVal = <?php if (isset($project->labels[$x+1]['statval'])) { echo $project->labels[$x+1]['statval']; } else {  echo $project->labels[0]['statval']; } ?>;
			statusColor = '<?php if (isset($project->labels[$x+1]['color'])) { echo $project->labels[$x+1]['color']; } else {  echo $project->labels[0]['color']; } ?>';
		break;  
		<?php } ?>
		}
		
		$('#link_'+id).removeClass();
		$('#link_'+id).addClass(status);
		$('#val_'+id).val(statusVal);
		$('#link_'+id).css({'background-color': statusColor});
		statusText.html(statusLabel);	
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