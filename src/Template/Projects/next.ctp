<?php
    $aim_alert = array();
	
    if ($tag_project == 1) {
	    $tagged = "tags";
    }

	if ($project->proxy == 1 && ($project->id == 118 || $project->id == 129)) {
		$proxy = "http://proxy.webpurify.com/proxy/target_view.php?p=";
	} else if ($project->proxy == 1) {
		$proxy = "http://proxy.webpurify.com/proxy/view.php?p=";
	} elseif ($project->thumb == 1) {
		$proxy = "http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?f=png&w=500&src=";
	} else {
		$proxy = "";
	}
	
	if (isset($this->request->params['pass'][1]) &&  $this->request->params['pass'][1] == "escalated") {
		$escalated = 1;
	} else {
		$escalated = 0;
	}
	
	//$nextPagecount = 9;
	
	// Next Page ------------------------------------------
	// There is probably a cleaner way to do this......
	if($nextPagecount >= 22 && $nextPagecount <= 32) {
		$nextNumImages = '32';
	} 

	if($nextPagecount >= 16 && $nextPagecount <= 21) {
		$nextNumImages = '21';
	} 

	if($nextPagecount >= 11 && $nextPagecount <= 15) {
		$nextNumImages = '15';
	} 

	if($nextPagecount >= 9 && $nextPagecount <= 10) {
		$nextNumImages = '10';;
	} 

	if($nextPagecount >= 7 && $nextPagecount <= 8) {
		$nextNumImages = '8';
	} 

	if($nextPagecount >= 4 && $nextPagecount <= 6) {
		$nextNumImages = '6';
	} 

	if($nextPagecount >= 2 && $nextPagecount <= 3) {
		$nextNumImages = '3';
	} 
	
	if($nextPagecount == 1 ) {
		$nextNumImages = '1';
	} 
	
		
?>
<div class="next s<?=isset($nextNumImages) ? $nextNumImages : count($images);?><?= $project->scroll_vert ? ' vertical' : '';?><?php echo $project->grid == 2 ? ' large-list' : '';?>">
<?php if (count($images) > 0) { ?>
<?php foreach ($images as $imagekey=>$image): 

		if ($project->blobimages) {
			$blobimage = $image['iurl'];
			$image['iurl'] = "";
		}

			if ($escalated  == 1) {			
				for ($x = 0; $x <= count($project->labels) -1; $x++) {
					if ($project->labels[$x]['statval'] == $image['status']) {
						$esccolor = $project->labels[$x]['color'];
						if ($grouplang == "cz") {
							$escname = $project->labels[$x]['translation'];
						} else {
							$escname = $project->labels[$x]['name'];
						}
						$escvalue = $project->labels[$x]['statval'];	
					}
					
				}	
			}	
?>	
	<div class="modimg">
		Tags: <?= $tagged;?>
		<?php if($project->scroll_vert) { ?>
			<em class="magnify text ion-ios-search-strong" data-fancybox-href="#imgDetails_<?=$imagekey?>"></em>
			<div id="imgDetails_<?=$imagekey?>" class='imgDetails hidden'><p><strong>URL:</strong> <?=$image['iurl']?></p><p><strong>ID:</strong> <?=$image['imgid']?></p><?php if (isset($image['mod'])) {?><p><strong>Mod:</strong> <?=$image['mod']?></p><?php } ?></div>
		<?php } else {?>
			<em class="magnify ion-ios-search-strong" data-fancybox-href="<?php if ($project->blobimages) { echo "data:image/jpg;base64,".$blobimage; } else { ?><?=$proxy?><?=$proxy!=""?urlencode($image['iurl']):$image['iurl']?><?php } ?>" data-title="<div class='imgDetails'><p><strong>URL:</strong> <?=$image['iurl']?></p><p><strong>ID:</strong> <?=$image['imgid']?></p><?php if (isset($image['mod'])) {?><p><strong>Mod:</strong> <?=$image['mod']?></p><?php } ?><?php if ($project->vansapac == 1) { ?><p><strong>Date:</strong> <?=$image['m2date']?></p><?php } ?><p><strong>Rotate:</strong> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&ra=90'>90&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&ra=180'>180&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&ra=270'>270&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&ra=360'>360&deg;</a></p><?php if ($project->ocr == 1) { ?><p><a id='ocr1' href='javascript:void(0)' onclick=getOCR('<?=$image['imgid']?>','<?=$proxy?><?=$proxy!=""?urlencode($image['iurl']):$image['iurl']?>');>Profanity Check</a> <img style='display: none' id='loading-image' src='/img/ajax-loader.gif'> <span style='font-weight: bold;' id='ocrResult_<?=$image['imgid']?>'></span></p><?php } ?></div>"></em>
		<?php } ?>
				<?php if($tag_project == 1) {	
					$imgdef_page = $imgdef;			
				?>
<div id="link_<?=$imagekey?>" class="holder <?=strtolower($imgdef['name'])?>" data-imgid="<?=$imagekey?>" style="background-color:<?=$escalated  == 1?$esccolor:$imgdef['color']?>">
					<?= $project->scroll_vert ? '<div class="scroll">' : '';?>
					<?php if ($project->blobimages == 1) { ?>
					<img id="<?=$imagekey?>" <?= $project->scroll_vert ? '' : 'class="zoom"';?> src="data:image/jpg;base64,<?=$blobimage?>" />
					<?php } else { ?>
					<img id="<?=$imagekey?>" <?= $project->scroll_vert ? '' : 'class="zoom"';?> src="<?=$proxy?><?=$proxy!=""?urlencode($image['iurl']):$image['iurl']?>" data-zoom-image="<?=$proxy?><?=$proxy!=""?urlencode($image['iurl']):$image['iurl']?>"/>
					<?php } ?>
					<?= $project->scroll_vert ? '</div>' : '';?>
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
							
							if ($project->id == 196 || $project->id == 158 || $project->id == 200 || $project->id == 162) {
								if (strpos($label['name'],'Reject') !== false) {
									$classes = ' class="snapCriteria2"';
								} else if (strpos($label['name'],'Report') !== false) {
									$classes = ' class="snapCriteria1"';
								}
							}									

							echo '<li'.$classes.'>';
							echo '<input type="'.$type.'" name="'.$type.'_'.$imagekey.'" value="'.$label['statval'].'" id="'.$id.'" data-imgid="'.$imagekey.'" data-color="'.$label['color'].'" '.$default.'/>';
						    echo '<label for="'.$id.'" '.$style.'>'.$label['name'].'</label>';
						    echo '</li>';
						}					
					?>
					</ul>
				</div>
			
			<?php } else { 
		
					$imgdef_page = $imgdef;
					
					if (isset($image['aim_nudity']) && $image['aim_nudity'] > 40) {
							$aim_alert[] = $imagekey;
							echo "<input type=\"hidden\" id=\"aim_".$imagekey."\" name=\"aim_".$imagekey."\" value=\"\" />";
					}	
			?>
			
				<a id="link_<?=$imagekey?>" href="javascript:void(0)" class="<?=strtolower($imgdef_page['name'])?><?php if ($image['blink']==1) {?> blinkit<?php }?>" data-imgid="<?=$imagekey?>" style="background-color:<?=$escalated  == 1?$esccolor:$imgdef_page['color']?>">
					<?php if (preg_match("/custom\-culture/i",$image['iurl'])  && $project->vansapac == 1) {?>
					<span style="position: absolute;margin-top: -30px;margin-left: -350px;"><strong>Custom Culture</strong></span>
					<?php } ?>						
						<h4 id="status_<?=$imagekey?>">
						<?php if ($grouplang == "cz") { ?>
						<?=$escalated  == 1?$escname:$imgdef_page['translation']?>
						<?php } else { ?>
						<?=$escalated  == 1?$escname:$imgdef_page['name']?>
						<?php } ?></h4>
					<?= $project->scroll_vert ? '<div class="scroll">' : '';?>
					
					
					<?php if ($project->blobimages == 1) { ?>
					<img id="<?=$imagekey?>" <?= $project->scroll_vert ? '' : 'class="zoom"';?> src="data:image/jpg;base64,<?=$blobimage?>" />
					<?php } else { ?>
					<img id="<?=$imagekey?>" <?= $project->scroll_vert ? '' : 'class="zoom"';?> src="<?=$proxy?><?=$proxy!=""?urlencode($image['iurl']):$image['iurl']?>" data-zoom-image="<?=$proxy?><?=$proxy!=""?urlencode($image['iurl']):$image['iurl']?>"/>
					<?php } ?>
					<?= $project->scroll_vert ? '</div>' : '';?>
				</a>
			
			<?php } ?>

		<!-- Text area for escalated images -->
		<?php if ($escalated == 1 && $project->escalatememo == 1) { ?>
			<em class="reasonBtn ion-edit" data-reason="#reason_<?=$imagekey?>"></em>
			<div id="reason_<?=$imagekey?>" class="reason">
				<h3>Reason:</h3>
				<textarea name="memo_<?=$imagekey?>" id="memo_<?=$imagekey?>"></textarea>
			</div>
		<?php } ?>

		<input type="hidden" id="val_<?=$imagekey?>" name="<?=$imagekey?>" value="<?=$escalated  == 1?$escvalue:$imgdef_page['statval']?>" class="status" />
		<input type="hidden" id="changed_<?=$imagekey?>" name="changed_<?=$imagekey?>" value="0" />  
	</div>
<?php endforeach; ?>
<?php } else { ?>
			<?php if ($grouplang == "cz") { ?>
			 <h3 id="lastSlide">没有图片要审核 <br>查找更多</h3>
			<?php } else { ?>
			<h3 id="lastSlide">No images to moderate. <span>Looking for more...</span></h3>
			<?php } ?>
<?php } ?>
</div>