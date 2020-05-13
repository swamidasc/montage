<?php
	if ($project->proxy == 1) {
		$proxy = "http://proxy.webpurify.com/proxy/view.php?p=";
	} else {
		$proxy = "";
	}
		
	if (isset($this->request->params['pass'][1]) &&  $this->request->params['pass'][1] == "escalated") {
		$escalated = 1;
	} else {
		$escalated = 0;
	}
	
    if ($tag_project == 1) {
	    $tagged = "tags";
    }	
?>
<div class="next s<?=isset($nextNumImages) ? $nextNumImages : count($images);?>">
<?php if (count($images) > 0) { ?>
<?php foreach ($images as $imagekey=>$image): ?>
	<div class="modimg">
		
	<?php if($tag_project == 1) {
		$imgdef_page = $imgdef;
	?>		
	
		<div id="link_<?=$imagekey?>" class="holder <?=strtolower($imgdef['name'])?>" data-imgid="<?=$imagekey?>" style="background-color:<?=$escalated  == 1?"#FF7300":$imgdef['color']?>">
      <?php if($image['iurl']) { ?>  		
  			<video preload="auto" id="video_<?=$imagekey?>" width="100%" height="390px" controls="true" <?php if ($project->loop_video == 1) {?>loop<?php } ?>>
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
            <?php } elseif ($image['iurl']) { ?>
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
							
							//if(($project->id == 195 || $project->id == 155) && $label['statval'] > 15) {
							//	$classes = ' class="amazonCriteria"';
							//}


							if ($project->tagtimes == 1) {
								$fortagtimes = "onclick=\"getTimestamp('video_".$imagekey."','".$id."')\"";
							} else {
								$fortagtimes = "";
							}	

							echo '<li'.$classes.'>';
							echo '<input '.$fortagtimes.' type="'.$type.'" name="'.$type.'_'.$imagekey.'" value="'.$label['statval'].'" id="'.$id.'" data-imgid="'.$imagekey.'" data-color="'.$label['color'].'" '.$default.'/>';
						    echo '<label for="'.$id.'" '.$style.'>'.$label['name'].'</label>';
						    echo '</li>';
						}					
					?>
					</ul>
				</div>
	
	
	<?php } else { 
	
		$imgdef_page = $imgdef;	
	?>			
		
		<a id="link_<?=$imagekey?>" class="<?=strtolower($imgdef['name'])?>" data-imgid="<?=$imagekey?>" style="background-color:<?=$escalated  == 1?"#FF7300":$imgdef['color']?>">
			<h4 id="status_<?=$imagekey?>"><?=$escalated  == 1?"escalated":$imgdef['name']?></h4>
			<video preload="auto" id="video_<?=$imagekey?>" width="100%" height="390px" controls="true" <?php if ($project->loop_video == 1) {?>loop<?php } ?>>
				  <source src="<?=$image['iurl']?>" type="video/mp4">
			</video>
		</a>

<?php } ?>		
		
		<input type="hidden" id="val_<?=$imagekey?>" name="<?=$imagekey?>" value="<?=$escalated  == 1?"-2":$imgdef['statval']?>" class="status" /> 
		<input type="hidden" id="time_<?=$imagekey?>" name="time_<?=$imagekey?>" value="" />
	</div>
<?php endforeach; ?>
<?php } else { ?>
	<h3 id="lastSlide">No more videos to moderate. <span>Looking for more...</span></h3>
<?php } ?>
</div>
