<?php
	if ($project->proxy == 1) {
		$proxy = "http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?f=png&h=250&w=200&src=http://proxy.webpurify.com/proxy/view.php?p=";
	} else {
		$proxy = "http://proxy.webpurify.com/proxy/phpThumb/phpThumb.php?f=png&h=250&w=200&src=";	
	}
	
	$justproxy = "http://proxy.webpurify.com/proxy/view.php?p=";
?>
<h2>Review: <em><?php echo $project['name'] ?></em></h2>
<p class="back"><?= $this->Html->link(__('All Projects'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>


<?= $this->Form->create($project, array('url' => '/projects/review/'.$project->id, 'id' => 'edit', 'class' => 'project', 'type' => 'post')) ?>
<input type="hidden" name="search" value="1" />
<input type="hidden" name="cachevalue" value="<?=md5(uniqid(rand(), true))?>" />
    <fieldset>
    	<?php if ($project->project_type_id == 1) {?>
    	<?php echo $this->Form->input('iurl',[ 'label' => [ 'text' => 'Image URL' ]]); ?><br>
    	<?php echo $this->Form->input('imgid',[ 'label' => [ 'text' => 'Image ID' ]]); ?><br>
    	<?php echo $this->Form->input('customid',[ 'label' => [ 'text' => 'Custom ID' ]]); ?>  
	<?php } else { ?>
    	<?php echo $this->Form->input('iurl',[ 'label' => [ 'text' => 'Video URL' ]]); ?><br>
    	<?php echo $this->Form->input('vidid',[ 'label' => [ 'text' => 'Video ID' ]]); ?><br>
    	<?php echo $this->Form->input('customid',[ 'label' => [ 'text' => 'Custom ID' ]]); ?>  		
   		<?php } ?>
    		
    	<p><label><?php echo $this->Form->checkbox('bydate'); ?> <strong>Include Date Range</strong></label></p>
    	
    	<?php if ($project->group_id == 1) { 
	    	$timezone_options = [
				'Asia/Calcutta' => 'IST',
				'US/Pacific' => 'PT'
			];
    	?>
    	<p> 
    	<?php echo $this->Form->label('timezone','Timezone'); ?>
    	<?php echo $this->Form->select('timezone',$timezone_options); ?>
    	</p>
    	<?php } ?>
    	
    	<?php
    		if ($_REQUEST['bydate'] == 1) {
    		
	    		$defaultstart = $_REQUEST['startdate']['year']."-".$_REQUEST['startdate']['month']."-".$_REQUEST['startdate']['day']." ".$_REQUEST['startdate']['hour'].":".$_REQUEST['startdate']['minute'];
	    		
	    		$defaultend = $_REQUEST['enddate']['year']."-".$_REQUEST['enddate']['month']."-".$_REQUEST['enddate']['day']." ".$_REQUEST['enddate']['hour'].":".$_REQUEST['enddate']['minute'];
	    		
    		} else {
    			$defaultstart = date("Y-m-d H:i");
    			$defaultend = date("Y-m-d H:i");
			}
		?>
    	<?php echo $this->Form->input('startdate',array('label' => 'Start Date','default' => $defaultstart)); ?>
        <?php echo $this->Form->input('enddate',array('label' => 'End Date','default' => $defaultend)); ?>		
    		   		
		<p> 
			<?php echo $this->Form->label('modby','Moderators'); 
			echo $this->Form->select('modby', $mods,['empty' => 'All']); ?>
		</p>

		<?php 

			echo $this->Form->input('filter_by', 
				[
				'type' => 'select',
				'multiple' => false,
				'options' => $labels, 
				'empty' => true,
				'label' => 'Filter by'		
				]);
		?>
		<?= $this->Form->button(__('Search'), array('class' => 'btn')) ?>
	</fieldset>
<?= $this->Form->end() ?>   

<?php if (isset($images) && count($images) == 0) { ?>
<h5 class="center noImg">No images found. Please try your search again.</h5>
<?php } else if (isset($images) && count($images) > 0) { 


	include(ROOT .DS. '/webroot/inc' . DS . 'paginate.php');
	
	$per_page = 10;
	$total_results = count($images);
	$total_pages = ceil($total_results / $per_page);
	
	
	
	
	if (isset($_GET['page'])) {
    	$show_page = $_GET['page']; //current page
		if ($show_page > 0 && $show_page <= $total_pages) {
        	$start = ($show_page - 1) * $per_page;
			$end = $start + $per_page;
		} else {
        	// error - show first set of results
			$start = 0;              
			$end = $per_page;
		}
	} else {
    	// if page isn't set, show first set of results
		$start = 0;
		$end = $per_page;
		$page = 0;
	}
	
	
	
	// display pagination
	$page = intval($_GET['page']);
	$tpages=$total_pages;
	if ($page <= 0) {
    	$page = 1;
    	$show_page = 1;
	}
	//
	$query_str = preg_replace('/\&tpages\=\d+/','',$_SERVER['QUERY_STRING']);
	
	// create the query string:
	$query_str = "search=1&iurl=".$_REQUEST['iurl']."&imgid=".$_REQUEST['imgid']."&bydate=".$_REQUEST['bydate']."&timezone=".$_REQUEST['timezone']."&startdate[year]=".$_REQUEST['startdate']['year']."&startdate[month]=".$_REQUEST['startdate']['month']."&startdate[day]=".$_REQUEST['startdate']['day']."&startdate[hour]=".$_REQUEST['startdate']['hour']."&startdate[minute]=".$_REQUEST['startdate']['minute']."&enddate[year]=".$_REQUEST['enddate']['year']."&enddate[month]=".$_REQUEST['enddate']['month']."&enddate[day]=".$_REQUEST['enddate']['day']."&enddate[hour]=".$_REQUEST['enddate']['hour']."&enddate[minute]=".$_REQUEST['enddate']['minute']."&modby=".$_REQUEST['modby']."&filter_by=".$_REQUEST['filter_by']."&cachevalue=".$_REQUEST['cachevalue'];
	
	
	$reload = "?".$query_str . "&tpages=" . $tpages;
	echo '<div class="pagination top"><ul>';
    if ($total_pages > 1) {
        echo paginate($reload, $show_page, $total_pages);
    }
    echo "</ul></div>";			
?>	
<a name="searchtop"></a>
<p class="total">Total of <strong><?=$total_results?></strong> records found.</p>

		<div id="results">	

			<ul class="content">
				<?php for ($i = $start; $i < $end; $i++) { 
					if (!isset($images[$i]->imgid) && !isset($images[$i]->vidid)) { continue; }
					
					if ($project->blobimages == 1) {
						$blobimage = "http://proxy.webpurify.com/proxy/getblob.php?proj=".$project->table_name."&id=".$images[$i]->imgid;
						$images[$i]->iurl = "";
					}					
				?>
				<li>
					<div class="resultsContainer">
						<?php if ($project->project_type_id == 2) {?>
							<div class="imgContainer">
								<video id="video_<?=$i?>" width="100%" style="height:285px !important" controls="true">
									<source src="<?=$images[$i]->iurl?>" type="video/mp4">
								</video>
							</div>
						<?php } else { 
								if ($project->download == 1 && $images[$i]->downloaded == 1) { 
									$s3url = "https://s3-us-west-1.amazonaws.com/wpim2/";
									
									$pic_base = basename($images[$i]->iurl);
									
									// get the filename extension
									$ext = strtolower(substr($pic_base, -3));				

									if ($ext != "jpg" && $ext != "gif" && $ext != "png") {
										$ext = "jpg";
									}
									
									$imgs3 = $s3url . $images[$i]->imgid . "." .$ext;
									
									$fulls3img = $proxy . urlencode($imgs3);
									$fulls3imgJP = $justproxy . urlencode($imgs3);
								} else {
									
								}
								?>
			
							<?php if ($project->project_type_id != 3 && $project->blobimages == 0) { ?>
							<?php if ($project->id == 10000) {
								// this was for project 125 (yearbook) but thye stopped using it.
							?>
							<div class="imgContainer"><a target="_bank" href="<?=$justproxy?><?=urlencode($images[$i]->option1)?>">							
							<?php } else { ?>
							<div class="imgContainer"><a target="_bank" href="<?php if ($project->download == 1 && $images[$i]->downloaded == 1) { ?><?=$fulls3imgJP?><?php } else { ?><?=$justproxy?><?=urlencode($images[$i]->iurl)?><?php } ?>">
							<?php } ?>
								<?php if ($project->download == 1 && $images[$i]->downloaded == 1) { ?>
									<img border="0" src="<?=$fulls3img?>">	
								<?php } else if ($project->project_type_id == 3) { ?>
									
								<?php } else if ($project->project_type_id == 4) { ?>
									<iframe width="440" height="250"  src="<?=$images[$i]->iurl?>embed" frameborder="0"></iframe>		
								<?php } else { ?>
									<?php if ($project->id == 10000) { ?>
									<img border="0" src="<?=$proxy?><?=urlencode($images[$i]->option1)?>">
									<?php } else { ?>
									<img border="0" src="<?=$proxy?><?=urlencode($images[$i]->iurl)?>">
									<?php } ?>
								<?php } ?>
							</a></div>
							<?php } else { ?>
							<div class="imgContainer" style="margin-bottom: 30px"><img border="0" width="440" src="<?=$blobimage?>"></div>
							<?php } ?>
						<?php } ?>
						<?php if ($project->blobimages == 0) { ?>
						<p><strong>URL:</strong> <input type="text" value="<?php if ($project->download == 1 && $images[$i]->downloaded == 1) {
									echo $fulls3imgJP;
							} else {
								if ($project->project_type_id == 3) { 
									$linkurl = str_replace("<iframe src=","",$images[$i]->iurl);
									$linkurl = str_replace(" height=400 width=700 />","",$linkurl);
									echo $linkurl;
								} else {
									echo $images[$i]->iurl;
								}
							}?>"/></p>
						<?php } ?>
						<?php if ($project->project_type_id == 2 || $project->project_type_id == 3) {?>
						<p><strong>Video ID:</strong> <?=$images[$i]->vidid?></p>
						<?php if (isset($images[$i]->clientvidid)) { ?>
						<p><strong>Custom ID:</strong> <?=$images[$i]->clientvidid?></p>
						<?php } ?>
						<?php } else { ?>
						<p><strong>Image ID:</strong> <?=$images[$i]->imgid?></p>
						<?php if (isset($images[$i]->clientimgid)) { ?>
						<p><strong>Custom ID:</strong> <?=$images[$i]->clientimgid?></p>
						<?php } ?>
						<?php } ?>			
<?php 
 if ($tag_project == 1 && isset($images[$i]->status) && strpos($images[$i]->status,',') !== false && $project->project_type_id == 1) {
								// images tagged with commas
								$tagstat = "";								
								$tagvals = explode(',',$images[$i]->status);						
								foreach ($tagvals as $taglabel) {
						    		if ($tagstat != "") {
								    		$tagstat .= ", ";
							    	}								
									$tagstat .= $labels[$taglabel];
								}
 							} else if ($tag_project == 1 && $images[$i]->status > 1 && $project->project_type_id == 1) { 
							$tagval = $images[$i]->status;
							foreach ($taglabels as $taglabel) {
							    if ($tagval - $taglabel >= 0 && $tagval - $taglabel != 1) {
							    	if ($tagstat != "") {
								    	$tagstat .= ", ";
							    	}
							    	$tagval = $tagval - $taglabel;
							        $tagstat .= $labels[$taglabel];
							    }
							}
							} else if ($tag_project == 1 && isset($images[$i]->status) && $images[$i]->status != 1 && $project->project_type_id == 2) {
								$stati=explode(",",$images[$i]->status);	
								if (isset($images[$i]->tagtimes)) {
									$stattimes=explode(",",$images[$i]->tagtimes);
								}
								
								
								foreach ($stati as $statkey => $thestatus) {
							    	if ($tagstat != "") {
								    	$tagstat .= ", ";
							    	}					
							    	$tagstat .= $labels[$thestatus];
							    	if ($stattimes[$statkey]) {
								    $tagstat .= " (".floor($stattimes[$statkey])." sec)";	
							    	}
							    	
							    					
								} 
							}
						if ($tagstat) {
						?>
						<p><strong>Tags:</strong> <?=$tagstat?></p>	
						<?php $tagstat = "";} else { ?>
						<?php if ($project->project_type_id == 3) { 
							
								//print_r($images[$i]);
							
								for ($sx = 1; $sx <= $images[$i]->numstatus; $sx++) { 
									
									$thisstatus = "status".$sx;
									
								?>
								<p><strong>Status <?=$sx?>:</strong> <?=$labels[$images[$i]->{$thisstatus}->status]?></p>
								<p><strong>Status <?=$sx?> Time: </strong> <?=$images[$i]->{$thisstatus}->modtime?> <?=$timezone=="Asia/Calcutta"?"IST":$timezone?></p>	
								<?php if ($sx != 1) { ?>
								<p><strong>Status <?=$sx?> Memo: </strong> <?=$images[$i]->{$thisstatus}->memo?></p>
								<?php } ?>
						<?php	} ?>
						<?php } else { ?>						
						<p><strong>Status:</strong> <?=$labels[$images[$i]->status]?></p>
						<?php } ?>
						<?php } ?>
						<?php if (($project->escalatememo == 1 || $project->modmemo == 1) && $project->project_type_id != 3) { ?>
						<p><strong>Memo:</strong>  <?=$images[$i]->memo?></p>
						<?php } ?>						
						
						<?php if ($images[$i]->broken == 1) { ?>
							<p><strong>Broken:</strong> Yes</p>
						<?php } ?>	
					
						<p><strong>Submitted:</strong> <?=$images[$i]->sdate?> <?=$timezone=="Asia/Calcutta"?"IST":$timezone?></p>
						<p><strong>Moderated:</strong> <?=$images[$i]->m1date?> <?=$timezone=="Asia/Calcutta"?"IST":$timezone?></p>
					
						<?php if (isset($images[$i]->modemail)) { ?>
							<p><strong>Moderator:</strong> <?=$images[$i]->modemail?></p>
						<?php } ?>

						<?php if ($allowfix == 1 && ($role <= 4 || $role == 7)) { 
								if ($project->project_type_id == 2) {
									 $projtype = "video";
								 } else { 
									 $projtype = "image";
								}	
						?>
								<p><a target="_blank" href="/projects/moderate/<?=$project->id?>/<?=$images[$i]->vidid ? $images[$i]->vidid : $images[$i]->imgid?>" onclick="if(!confirm('Are you sure you want to set this <?=$projtype?> to escalated?')) return false;">Reset this <?=$projtype?> to escalated.</a></p>
						<?php } ?>	
						
					</div>
				</li>
				<?php } ?>
			</ul>	
				
		</div>
<?php
	echo '<div class="pagination bottom"><ul>';
    if ($total_pages > 1) {
        echo paginate($reload, $show_page, $total_pages);
    }
    echo "</ul></div>";			
?>		
		
<?php } ?>
