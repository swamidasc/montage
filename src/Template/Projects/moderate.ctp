<?php
    if ($tag_project == 1) {
	    $tagged = "tags";
    }
    
    $aim_alert = array();
    $aim_alert2 = array();
    
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

		
?>
<input type="hidden" name="tn" id="tn" value="<?=$project->table_name?>" /> 
<input type="hidden" name="endpoint" id="endpoint" value="<?=$project->endpoint?>" /> 
<input type="hidden" name="modid" id="modid" value="<?=$_SESSION['Auth']['User']['id']?>" /> 
<input type="hidden" name="slideid" id="slideid" value="0" /> 
<?php if ($project->aim_nudity) {?>
<input type="hidden" name="aim_nudity" id="aim_nudity" value="1" />
<?php } ?>
<?php if ($confirm) { ?>
<input type="hidden" name="confirm" id="confirm" value="<?=implode(',',array_keys($confirm))?>" />
<input type="hidden" name="confirmname" id="confirmname" value="<?=implode(',',$confirm)?>" />
<input type="hidden" name="confirmskip" id="confirmskip" value="0" />
<?php } ?>


<div id="loading"><span></span></div>
<div id="overlayBG"></div>
<div id="overlay"></div>
<div id="overlayProfanityBG"></div>

<?php 
	//echo '<p style="position:fixed; top:0; left:0; z-index:99999; background:#000; color:#FFF; padding:20px;">';
	//echo "This Page: ".$page1count."<br>";
	
	// Page 01 ------------------------------------------------
	if($page1count >= 22 && $page1count <= 32) {
		$numImages = '32';
	} 

	if($page1count >= 16 && $page1count <= 21) {
		$numImages = '21';
	} 

	if($page1count >= 11 && $page1count <= 15) {
		$numImages = '15';
	} 

	if($page1count >= 9 && $page1count <= 10) {
		$numImages = '10';;
	} 

	if($page1count >= 7 && $page1count <= 8) {
		$numImages = '8';
	} 

	if($page1count >= 4 && $page1count <= 6) {
		$numImages = '6';
	} 

	if($page1count >= 2 && $page1count <= 3) {
		$numImages = '3';
	} 
	
	if($page1count == 1 ) {
		$numImages = '1';
	} 
	//echo 'Page01: '.$numImages.'<br/><br/>';
	//echo "Next Page: ".$page2count."<br>";
	//$page2count = 5;
	//echo "Reduced Number: ".$page2count."<br>";
	
	
	// Page 02 ------------------------------------------------
	if($page2count >= 22 && $page2count <= 32) {
		$numImages02 = '32';
	} 

	if($page2count >= 16 && $page2count <= 21) {
		$numImages02 = '21';
	} 

	if($page2count >= 11 && $page2count <= 15) {
		$numImages02 = '15';
	} 

	if($page2count >= 9 && $page2count <= 10) {
		$numImages02 = '10';;
	} 

	if($page2count >= 7 && $page2count <= 8) {
		$numImages02 = '8';
	} 

	if($page2count >= 4 && $page2count <= 6) {
		$numImages02 = '6';
	} 

	if($page2count >= 2 && $page2count <= 3) {
		$numImages02 = '3';
	} 
	
	if($page2count == 1 ) {
		$numImages02 = '1';
	} 
?>


<div id="mod" class="<?=isset($tagged) ? $tagged : '';?>">

	<? /* ==========================================
		       No Images to Moderate	
	================================================ */ ?>
	<?php if (!isset($images)) { ?>
		<div id="last" class="slide">
			<?php if (isset($locked) && is_array($locked)) { ?>			
				<p>The following moderators have locked images:</p>			
				<?php foreach ($locked as $lockkey => $lockedmod) { ?>
					<?=$lockedmod['email']?> has <?=$lockedmod['total']?> <?=$project->name?> image(s) locked. <a href="/projects/clearlocksbymodid/<?=$project->id?>/<?=$lockkey?>">Release Lock</a><br>
				<?php } ?>
			<?php } ?>
			<?php if ($grouplang == "cz") { ?>
				 <h3 id="lastSlide">没有图片要审核 <br>查找更多</h3>
			<?php } else { ?>			
			<h3 id="lastSlide">No images to moderate. <span>Looking for more...</span></h3>
			<?php if (isset($quote) && $quote != "") {?>
			<center><p><div style="width: 500px"><i><?=$quote?><br>-- <?=$quoteauthor?></i></div></p></center>
			<?php } ?>
			<?php } ?>
		</div>	
		<script type="text/javascript">
		setTimeout(function(){ reloadFunc(); }, 5000);
		function reloadFunc() {
			$.cookie("reload", "1");
			location.reload();
		}
		</script>		
	<?php } else { ?>

	<? /* ==========================================
		       First Slide	
	================================================ */ ?>

	<div class="slide s<?=isset($numImages) ? $numImages : count($images);?><?= $project->scroll_vert ? ' vertical' : '';?><?php echo $project->grid == 2 ? ' large-list' : '';?>">
	<?php //echo 'images from projectscontroller moderate function 1-->:';
		//print_r($images);
		?>
	<?php foreach ($images as $imagekey=>$image):

		if ($project->blobimages) {
			$blobimage = $image['iurl'];
			$image['iurl'] = "";
		}
		
	?>
		<div class="modimg">
			<?php // Vertical Scrolling 
				if($project->scroll_vert) { ?>
				<em class="magnify text ion-ios-search-strong" data-fancybox-href="#imgDetails_<?=$imagekey?>"></em>
				<div id="imgDetails_<?=$imagekey?>" class='imgDetails hidden'><p><strong>URL:</strong> <?=$image['iurl']?></p><p><strong>ID:</strong> <?=$image['imgid']?></p><?php if (isset($image['mod'])) {?><p><strong>Mod:</strong> <?=$image['mod']?></p><?php } ?></div>
			<?php } else {?>
				<em class="magnify ion-ios-search-strong" data-fancybox-href="<?php if ($project->blobimages) { echo "data:image/jpg;base64,".$blobimage; } else { ?><?=$proxy?><?=$proxy!=""?urlencode($image['iurl']):$image['iurl']?><?php } ?>" data-title="<div class='imgDetails'><p><strong>URL:</strong> <?=$image['iurl']?></p><p><strong>ID:</strong> <?=$image['imgid']?></p><?php if (isset($image['mod'])) {?><p><strong>Mod:</strong> <?=$image['mod']?></p><?php } ?><?php if ($project->vansapac == 1) { ?><p><strong>Date:</strong> <?=$image['m2date']?></p><?php } ?><p><strong>Rotate:</strong> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&ra=90'>90&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&ra=180'>180&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&ra=270'>270&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&ra=360'>360&deg;</a> | <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&fltr[]=flip%7Cx'>Flip X-Axis</a> | <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image['iurl']?>&fltr[]=flip%7Cy'>Flip Y-Axis</a></p><?php if ($project->ocr == 1) { ?><p><a id='ocr1' href='javascript:void(0)' onclick=getOCR('<?=$image['imgid']?>','<?=$proxy?><?=$proxy!=""?urlencode($image['iurl']):$image['iurl']?>');>Profanity Check</a> <img style='display: none' id='loading-image' src='/img/ajax-loader.gif'> <span style='font-weight: bold;' id='ocrResult_<?=$image['imgid']?>'></span></p><?php } ?></div>"></em>
			<?php } ?>
			
			<?php
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
							echo "<input type=\"hidden\" id=\"aim_".$imagekey."\" name=\"aim_".$imagekey."\" value=\"0\" />";
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
						<?php } ?>
					</h4>
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

			<input type="hidden" id="val_<?=$imagekey?>" name="<?=$imagekey?>" src="<?=$image['iurl']?>" value="<?=$escalated  == 1?$escvalue:$imgdef_page['statval']?>" class="status" />
			<input type="hidden" id="changed_<?=$imagekey?>" name="changed_<?=$imagekey?>" value="0" />  
						
		</div>
	<?php endforeach; ?>
	</div>

	<? /* ==========================================
		       Second Slide	
	================================================ */ ?>

	<?php if (isset($images2) && count($images2) > 0) { ?>
	<div class="slide s<?=isset($numImages02) ? $numImages02 : count($images2);?><?= $project->scroll_vert ? ' vertical' : '';?><?php echo $project->grid == 2 ? ' large-list' : '';?>">
	<?php //echo 'images from projectscontroller moderate function 2--->:';
		//print_r($images2);
		?>
	<?php 
		foreach ($images2 as $imagekey2=>$image2): 
		
		
		if ($project->blobimages) {
			$blobimage2 = $image2['iurl'];
			$image2['iurl'] = "";
		}		

			if ($escalated  == 1) {			
				for ($x = 0; $x <= count($project->labels) -1; $x++) {
					if ($project->labels[$x]['statval'] == $image2['status']) {
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
			<?php if($project->scroll_vert) { ?>
				<em class="magnify text ion-ios-search-strong" data-fancybox-href="#imgDetails_<?=$imagekey2?>"></em>
				<div id="imgDetails_<?=$imagekey2?>" class='imgDetails hidden'><p><strong>URL:</strong> <?=$image2['iurl']?></p><p><strong>ID:</strong> <?=$image2['imgid']?></p><?php if (isset($image2['mod'])) {?><p><strong>Mod:</strong> <?=$image2['mod']?></p><?php } ?></div>
			<?php } else {?>
				<em class="magnify ion-ios-search-strong" data-fancybox-href="<?php if ($project->blobimages) { echo "data:image/jpg;base64,".$blobimage2; } else { ?><?=$proxy?><?=$proxy!=""?urlencode($image2['iurl']):$image2['iurl']?><?php } ?>" data-title="<div class='imgDetails'><p><strong>URL:</strong> <?=$image2['iurl']?></p><p><strong>ID:</strong> <?=$image2['imgid']?></p><?php if (isset($image2['mod'])) {?><p><strong>Mod:</strong> <?=$image2['mod']?></p><?php } ?><?php if ($project->vansapac == 1) { ?><p><strong>Date:</strong> <?=$image2['m2date']?></p><?php } ?><p><strong>Rotate:</strong> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image2['iurl']?>&ra=90'>90&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image2['iurl']?>&ra=180'>180&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image2['iurl']?>&ra=270'>270&deg;</a> <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image2['iurl']?>&ra=360'>360&deg;</a> | <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image2['iurl']?>&fltr[]=flip%7Cx'>Flip X-Axis</a> | <a target='_blank' href='http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?src=<?=$image2['iurl']?>&fltr[]=flip%7Cy'>Flip Y-Axis</a></p><?php if ($project->ocr == 1) { ?><p><a id='ocr2' href='javascript:void(0)' onclick=getOCR('<?=$image2['imgid']?>','<?=$proxy?><?=$proxy!=""?urlencode($image2['iurl']):$image2['iurl']?>');>Profanity Check</a> <img style='display: none' id='loading-image' src='/img/ajax-loader.gif'> <span style='font-weight: bold;' id='ocrResult_<?=$image2['imgid']?>'></span></p><?php } ?></div>"></em>
			<?php } ?>
			
			<?php if($tag_project == 1) {
				$imgdef_page = $imgdef;
			?>
			
				<div id="link_<?=$imagekey2?>" class="holder " data-imgid="<?=$imagekey2?>" style="background-color:<?=$escalated  == 1?$esccolor:$imgdef_page['color']?>">
					<?= $project->scroll_vert ? '<div class="scroll">' : '';?>
					<?php if ($project->blobimages == 1) { ?>
					<img id="<?=$imagekey?>" <?= $project->scroll_vert ? '' : 'class="zoom"';?> src="data:image/jpg;base64,<?=$blobimage2?>" />
					<?php } else { ?>
					<img id="<?=$imagekey2?>" <?= $project->scroll_vert ? '' : 'class="zoom"';?> src="<?=$proxy?><?=$proxy!=""?urlencode($image2['iurl']):$image2['iurl']?>" data-zoom-image="<?=$proxy?><?=$project->thumb==1?urlencode($image2['iurl']):$image2['iurl']?>"/>
					<?php } ?>
					<?= $project->scroll_vert ? '</div>' : '';?>
				</div>
				
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
							
							if ($project->id == 196 || $project->id == 158 || $project->id == 200 || $project->id == 162) {
								if (strpos($label['name'],'Reject') !== false) {
									$classes = ' class="snapCriteria2"';
								} else if (strpos($label['name'],'Report') !== false) {
									$classes = ' class="snapCriteria1"';
								}
							}								

							echo '<li'.$classes.'>';
							echo '<input type="'.$type.'" name="'.$type.'_'.$imagekey2.'" value="'.$label['statval'].'" id="'.$id.'" data-imgid="'.$imagekey2.'" data-color="'.$label['color'].'" '.$default.'/>';
						    echo '<label for="'.$id.'" '.$style.'>'.$label['name'].'</label>';
						    echo '</li>';
						}					
					?>
					</ul>
				</div>
			
			<?php } else {				
					$imgdef_page = $imgdef;

					if (isset($image2['aim_nudity']) && $image2['aim_nudity'] > 40) {
							$aim_alert2[] = $imagekey2;
							echo "<input type=\"hidden\" id=\"aim_".$imagekey2."\" name=\"aim_".$imagekey2."\" value=\"1\" />";
					}	
			?>
			
				<a id="link_<?=$imagekey2?>" href="javascript:void(0)" class="<?=strtolower($imgdef_page['name'])?><?php if ($image2['blink']==1) {?> blinkit<?php }?>" data-imgid="<?=$imagekey2?>" style="background-color:<?=$escalated == 1?$esccolor:$imgdef_page['color']?>;">
					<?php if (preg_match("/custom\-culture/i",$image2['iurl'])  && $project->vansapac == 1) {?>
					<span style="position: absolute;margin-top: -30px;margin-left: -350px;"><strong>Custom Culture</strong></span>
					<?php } ?>						
					<h4 id="status_<?=$imagekey2?>">
						<?php if ($grouplang == "cz") { ?>
						<?=$escalated  == 1?$escname:$imgdef_page['translation']?>
						<?php } else { ?>
						<?=$escalated  == 1?$escname:$imgdef_page['name']?>
						<?php } ?>						
					</h4>
					<?= $project->scroll_vert ? '<div class="scroll">' : '';?>	
					
					
					
					<?php if ($project->blobimages == 1) { ?>
					<img id="<?=$imagekey2?>" <?= $project->scroll_vert ? '' : 'class="zoom"';?> src="data:image/jpg;base64,<?=$blobimage2?>" />
					<?php } else { ?>
					<img id="<?=$imagekey2?>" <?= $project->scroll_vert ? '' : 'class="zoom"';?> src="<?=$proxy?><?=$proxy!=""?urlencode($image2['iurl']):$image2['iurl']?>" data-zoom-image="<?=$proxy?><?=$project->thumb==1?urlencode($image2['iurl']):$image2['iurl']?>"/>
					<?php } ?>					
							
					<?= $project->scroll_vert ? '</div>' : '';?>
				</a>
			
			<?php } ?>

			<!-- Text area for escalated images -->
			<?php if ($escalated == 1 && $project->escalatememo == 1) { ?>
				<em class="reasonBtn ion-edit" data-reason="#reason_<?=$imagekey2?>"></em>
				<div id="reason_<?=$imagekey2?>" class="reason">
					<h3>Reason:</h3>
					<textarea name="memo_<?=$imagekey2?>" id="memo_<?=$imagekey2?>"></textarea>
				</div>
			<?php } ?>

			<input type="hidden" id="val_<?=$imagekey2?>" name="<?=$imagekey2?>" src="<?=$image2['iurl']?>" value="<?=$escalated  == 1?$escvalue:$imgdef_page['statval']?>" class="status" /> 
			<input type="hidden" id="changed_<?=$imagekey2?>" name="changed_<?=$imagekey2?>" value="0" /> 
		</div>
	<?php endforeach; ?>		
	</div>
	<?php } else { ?>
		<div id="last" class="slide">
			<?php if ($grouplang == "cz") { ?>
			 <h3 id="lastSlide">没有图片要审核 <br>查找更多</h3>
			<?php } else { ?>
			<h3 id="lastSlide">No images to moderate. <span>Looking for more...</span></h3>
			<?php if (isset($quote) && $quote != "") {?>
			<center><p><div style="width: 500px"><i><?=$quote?><br>-- <?=$quoteauthor?></i></div></p></center>
			<?php } ?>
		    <?php } ?>
		</div>
   <?php } ?>
   <?php } ?>
</div>
	
<a id="back" class="navBtn btn grey" href="javascript:void(0);"><em class="ion-arrow-left-b"></em></a>
<?php if (isset($images) && count($images) > 0) { ?>
<a id="next" class="navBtn btn" href="javascript:void(0);"><em class="ion-arrow-right-b"></em></a>
<?php } ?>

<script type="text/javascript"> 		 
	// Moderate Image
	function moderateImage(id, className){
		
		$('a#link_'+id).removeClass('blinkit');
	//alert('test');
		if (typeof className === 'undefined') {
			var className = $('#link_'+id).attr('class');
		}
		
		var statusLabel;
		var status;
		var statusVal;
		var statusColor;
		var statusText = $('#status_'+id);
		
		switch(className){
		<?php for ($x = 0; $x <= count($project->labels) -1; $x++) {?>
		case '<?=seoUrl($project->labels[$x]['name'])?>':
		
		<?php if ($grouplang == "cz") { 
			$labelname = $project->labels[$x+1]['translation'];
			$noname = $project->labels[0]['translation'];
		 } else { 
			$labelname = $project->labels[$x+1]['name'];
			$noname = $project->labels[0]['name'];
		 } ?>
			statusLabel = '<?php if (isset($project->labels[$x+1]['name'])) { echo strtolower($labelname); } else {  echo strtolower($noname); } ?>';
			//alert(statusLabel);
			status = '<?php if (isset($project->labels[$x+1]['name'])) { echo seoUrl($project->labels[$x+1]['name']); } else {  echo seoUrl($project->labels[0]['name']); } ?>';
			statusVal = <?php if (isset($project->labels[$x+1]['statval'])) { echo $project->labels[$x+1]['statval']; } else {  echo $project->labels[0]['statval']; } ?>;
			statusColor = '<?php if (isset($project->labels[$x+1]['color'])) { echo $project->labels[$x+1]['color']; } else {  echo $project->labels[0]['color']; } ?>';
		break;  
		<?php } ?>
		}
		
		$('a#link_'+id).removeClass();
		if (statusVal == -1) {;
			$('#'+id).removeClass('zoom');
		}
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