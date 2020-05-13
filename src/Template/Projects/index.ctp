<?php if ($role < 2) { ?>
	<p class="new desktop"><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add'], array('class' => 'btn')) ?></li>
<?php } ?>

<?php if (isset($livestream)) { ?>
	<audio id="livestream_alarm" src="/media/livestream_alarm.mp3" preload="auto"></audio>
	<h2>Live Stream Projects</h2>
	
<table id="projects" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
        	<?php if ($role == 1) { ?>
            <th><?= $this->Paginator->sort('Projects.name','Name') ?></th>
            <?php } ?>
            <th><?= $this->Paginator->sort('Groups.name','Group') ?></th>
            <th><?= $this->Paginator->sort('Projects.project_type_id','Type') ?></th>
            <th>Submitted</th>
            <th>Unmonitored</th>
            <?php if ($role != 6) { ?>
            <th>Currently being Monitored</th>
            <?php } ?>
            <th>Escalated</th>
            <th>Completed</th>
            <?php if ($role != 6) { ?>
			<th>Longest Wait</th>
			<?php } ?>
			<th class="divide desktop">Moderate <?php if ($role == 6) { ?>Escalated<?php } ?></th>
            <?php if ($role != 6) { ?>
            <th class="actions desktop"><?= __('Actions') ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>	
   <?php foreach ($projects as $project): 
    
    if ($project->hide == 1 || ($project->project_type_id != 3 && $project->project_type_id != 5)) {
	    continue;
    }
    ?>
        <tr>
            <td><strong><?=$project->name?></strong></td>
          <?php if ($role == 1) { ?>
            <td><?=$project->group->name ?></td>
          <?php } ?>   
            <td><?=$project->projects_type->name ?></td>
            <td align="center"><div id="submitted_<?=$project->id?>"><?= h($stats[$project->id]['submitted']) ?></div></td>
            <td align="center"><font color="red">
   
            <div id="unmonitored_<?=$project->id?>" style="font-size: 20px; font-weight: bold"><?= h($stats[$project->id]['unmonitored']) ?></span></div></td>
            <?php if ($role != 6) { ?>
             <td align="center"><div id="monitored_<?=$project->id?>"><font color="red"><?= h($stats[$project->id]['monitored']) ?></font></div></td>
            <?php } ?>  
			<td class="mod">
            		<span id="escalated_<?=$project->id?>">
            			<?php if ($stats[$project->id]['escalated'] > 0 && $project->modoff != 1) { ?>
	            				<a id="esc<?=$project->id?>" class="btn escalated" href="/projects/moderate/<?=$project->id?>/escalated"><?= h($stats[$project->id]['escalated']) ?></a>
	            		<?php } else { ?>0<?php } ?>
            		</span>
            </td>   
            <td align="center"><div id="completed_<?=$project->id?>"><?= h($stats[$project->id]['completed']) ?></div></td>  
            <?php if ($role != 6) { ?>          
            <td align="center"><div id="longest_<?=$project->id?>"><?= h($stats[$project->id]['longest']) ?></div></td>
			<?php } ?>
            <?php if ($role < 6 and $project->modoff != 1) { ?>
            <td class="mod divide desktop"><a id="<?=$project->name?>" href="/projects/moderate/<?=$project->id?>" class="btn">Moderate</a></td>
            <?php } elseif ($project->modoff != 1 && $role != 7) { ?>
            <td class="mod divide desktop"><a id="<?=$project->name?>" href="/projects/moderate/<?=$project->id?>/escalated" class="btn">Moderate</a></td>
            <?php } ?>
            <?php if ($role != 6) { ?>
            <td class="actions desktop">
	            <?php if ($role <= 4 || $role == 7) { ?>
	            <a target="_blank" href="/projects/review/<?=$project->id?>" class="ion-ios-eye" title="Review"><span>Review</span></a>
                <?php } ?>
                <?php if ($role < 5) { ?>
				<?= $this->Html->link(__('<span>Edit</span>'), ['action' => 'edit', $project->id], array('class' => 'ion-edit', 'escape' => FALSE,'target' => '_blank')) ?>
				<?php } ?>				
				
	            <?php if ($role == 1) { ?>
	            <?= $this->Form->postLink(__('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="icon" d="M128 405.429C128 428.846 147.198 448 170.667 448h170.667C364.802 448 384 428.846 384 405.429V160H128v245.429zM416 96h-80l-26.785-32H202.786L176 96H96v32h320V96z"/></svg><span>Delete</span><span>Delete</span>'), ['action' => 'delete', $project->id], array('class' => 'ion-close-circled', 'escape' => FALSE, 'confirm' => __('Are you sure you want to delete #{0}?', $project->name))) ?>
                <?php } ?>
            </td>
            <?php } ?>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>
	

<?php } ?>

<h2><?=$training==1?"Training ":""?>Projects</h2>

<table id="projects" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
        	<?php if ($role == 1) { ?>
            <th><?= $this->Paginator->sort('Projects.name','Name') ?></th>
            <?php } ?>
            <th>     
	            <?php if ($grouplang == "cz") { ?>
	            <?= $this->Paginator->sort('Groups.name','分组'); ?>
	            <?php } else { ?>		                             	         
	            <?= $this->Paginator->sort('Groups.name','Group'); ?>
	            <?php } ?>    
	        </th>
            <th>
	            <?php if ($grouplang == "cz") { ?>
	            <?= $this->Paginator->sort('Projects.project_type_id','类型') ?>
				<?php } else { ?>
				<?= $this->Paginator->sort('Projects.project_type_id','Type') ?>
				<?php } ?>
            </th>
            <th>
	            <?php if ($grouplang == "cz") { ?>
	            已提交
	            <?php } else { ?>
	            Submitted
	            <?php } ?>
            </th>
            <th>
	            <?php if ($grouplang == "cz") { ?>
	            待审核
	            <?php } else { ?>
	            Pending
	            <?php } ?>
	        </th>
            <?php if ($role != 6 && ($role != 5 && !isset($vansapac))) { ?>
            <th>Overdue</th>
            <?php } ?>
            <th><?php if (isset($vansapac) && $role == 5) { ?>暂缓<?php } else { ?>Escalated<?php } ?></th>
            <th>
	        	<?php if ($grouplang == "cz") { ?>
	            已审核
	            <?php } else { ?>
	            Moderated
	            <?php } ?>
	        </th>
            <?php if ($role != 6) { ?>
			<th>
				<?php if ($grouplang == "cz") { ?>
				图片队列
				<?php } else { ?>
				Longest Wait
				<?php } ?>
			</th>
			<?php } ?>
					<?php if ($grouplang != "cz") {?>
					<th class="mod divide desktop">
						Moderate <?php if ($role == 6) { ?>Escalated<?php } ?>
					</th>
					<?php } else { ?>
						<th class="mod">审核</th>
					<?php } ?>
					
          <?php if ($role != 6) { ?>
            <?php if ($grouplang != "cz") { ?><th class="actions desktop"><?= __('Actions') ?></th><?php } ?>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($projects as $project): 
    
    if ($project->hide == 1 || ($project->project_type_id == 3 || $project->project_type_id == 5)) {
	    continue;
    }
    ?>
        <tr>
            <td>
	            <?php if ($grouplang == "cz") { ?>
		        中国Customs自由定制鞋 / <?=$project->name?>
		        <?php } else { ?>
	            <strong><?=$project->name?></strong>
	            <?php } ?>
	        </td>
          <?php if ($role == 1) { ?>
            <td><?=$project->group->name ?></td>
          <?php } ?>   
            <td>
	        	<?php if ($grouplang == "cz") { ?>
	        	图片审核
	        	<?php } else { ?>
	            <?=$project->projects_type->name ?> 
	            <?php } ?>
	        </td>
            <td align="center"><div id="submitted_<?=$project->id?>"><?= h($stats[$project->id]['submitted']) ?></div></td>
            <td align="center"><font color="red">        
            <div id="pending_<?=$project->id?>"><?= h($stats[$project->id]['pending']) ?></div> <?php if ($project->id == 142 || $project->id == 155) { ?><br><div id="timelength_<?=$project->id?>"><?=$stats[$project->id]['timelength']?></div><?php } ?></font></td>
            <?php if ($role != 6 && ($role != 5 && !isset($vansapac))) { ?>
             <td align="center"><font color="red"><div id="overdue_<?=$project->id?>"><?= h($stats[$project->id]['overdue']) ?></div><?php if ($stats[$project->id]['overtime']) { ?><br><div id="overtime_<?=$project->id?>"><?=$stats[$project->id]['overtime']?></div><?php } ?></font></td>
            <?php } ?>
            <td class="mod">
            		<span id="escalated_<?=$project->id?>">
            			<?php if ($stats[$project->id]['escalated'] > 0 && $project->modoff != 1) { ?>
	            				<a id="esc<?=$project->id?>" class="btn escalated" href="/projects/moderate/<?=$project->id?>/escalated"><?= h($stats[$project->id]['escalated']) ?></a>
	            		<?php } else { ?>0<?php } ?>
            		</span>
            </td>    
            <td align="center"><div id="moderated_<?=$project->id?>"><?= h($stats[$project->id]['moderated']) ?></div> <?php if ($project->id == 142 || $project->id == 155) { ?><br><div id="modtotaltime_<?=$project->id?>"><?=$stats[$project->id]['modtotaltime']?></div><?php } ?></td>  
            <?php if ($role != 6) { ?>          
            <td align="center"><div id="longest_<?=$project->id?>"><?= h($stats[$project->id]['longest']) ?></div></td>
			<?php } ?>
            <?php if ($role < 6 and $project->modoff != 1) { ?>
            <?php if ($grouplang != "cz") { ?>
            <td class="mod divide desktop"><a id="<?=$project->name?>" href="/projects/moderate/<?=$project->id?>" class="btn">Moderate0</a></td>
            <?php } else { ?>
             <td class="mod divide"><a id="<?=$project->name?>" href="/projects/moderate/<?=$project->id?>" class="btn">审核</a></td>
            <?php } ?>
            <?php } elseif ($project->modoff != 1 && $role != 7) { ?>
            <td class="mod divide desktop"><a id="<?=$project->name?>" href="/projects/moderate/<?=$project->id?>/escalated" class="btn">Moderate1</a></td>
            <?php } ?>
            <?php if ($role != 6) { ?>
            <td class="actions desktop">
	            <?php if ($role <= 4 || $role == 7) { ?>
	            <a target="_blank" href="/projects/review/<?=$project->id?>" class="ion-ios-eye" title="Review"><span>Review</span></a>
                <?php } ?>
                <?php if ($role < 5) { ?>
				<?= $this->Html->link(__('<span>Edit</span>'), ['action' => 'edit', $project->id], array('class' => 'ion-edit', 'escape' => FALSE,'target' => '_blank')) ?>
				<?php } ?>				
				
	            <?php if ($role == 1) { ?>
	            <?= $this->Form->postLink(__('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="icon" d="M128 405.429C128 428.846 147.198 448 170.667 448h170.667C364.802 448 384 428.846 384 405.429V160H128v245.429zM416 96h-80l-26.785-32H202.786L176 96H96v32h320V96z"/></svg><span>Delete</span>'), ['action' => 'delete', $project->id], array('class' => 'ion-close-circled', 'escape' => FALSE, 'confirm' => __('Are you sure you want to delete #{0}?', $project->name))) ?>
                <?php } ?>
            </td>
            <?php } ?>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>
<div>
    <ul class="pagination">
        <?= $this->Paginator->prev('&lsaquo; ' . __('Prev'), array('escape' => FALSE)) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('Next') . ' &rsaquo;', array('escape' => FALSE)) ?>
    </ul>
    <p class="total"><?= $this->Paginator->counter() ?></p>
</div>

<script>

delete_cookie = function (name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};
	
	<?php foreach ($projects as $project) { 
		if ($project->project_type_id == 3) { ?>
			delete_cookie("live_<?=$project->id?>","","");
		<?php }
	} ?>	
	
	window.setInterval(function(){

	<?php foreach ($projects as $project): 
	    if ($project->hide == 1) {
	    	continue;
		}
	
		if ($procclient[$project->id]) {
			$client_escalate = $procclient[$project->id];
		} else {
			$client_escalate = $project->client_escalate;
		}
	
	?>
	var modURL<?=$project->id?> = "<?=$project->endpoint?>?overdue=<?=$project->return_limit?>&action=getStats&tn=<?=$project->table_name?>&ce=<?=$client_escalate?>&ut=<?=$role?>";
		
		$.ajax({
			type: 'GET',
    			url: modURL<?=$project->id?>,
			jsonpCallback: 'callback<?=$project->id?>',
			contentType: "application/json",
			dataType: "jsonp",
			success: function( response<?=$project->id?> ) { 
				$.each(response<?=$project->id?>.stats[0], function(index, element) {
					var projStat<?=$project->id?> = index + "_<?=$project->id?>";	
					if (index != "escalated" || (index == "escalated" && response<?=$project->id?>.stats[0][index] == 0)) {
					<?php if ($project->project_type_id == 3) { ?>
							if (index == "submitted") {
								console.log("The current count "+$('#submitted_<?=$project->id?>').text());
								//console.log("The new count is: "+response<?=$project->id?>.stats[0][index]);
								if ($("#submitted_<?=$project->id?>").text() < response<?=$project->id?>.stats[0][index]) {
									console.log("play the alert");
									var promise = document.getElementById('livestream_alarm').play();	
									if (promise !== undefined) {
										promise.then(_ => {
											// Autoplay started!
    										}).catch(error => {
	    										//alert(error);
											// Autoplay was prevented.
											// Show a "Play" button so that user can start playback.
    										});
								}
										
								}
							}
				   <?php } ?>
				   		<?php					   		
					   		if ($project->id == 122 && $role == 5) { 
						?>
				   			if (index == "pending" || index == "longest" || index == "overdue") {
					   			continue;
				   			} 
				   		<?php }  ?>
						$("#"+projStat<?=$project->id?>).html(response<?=$project->id?>.stats[0][index]);
					} else {
						$("#"+projStat<?=$project->id?>).html('<a id="esc<?=$project->id?>" class="btn escalated" href="/projects/moderate/<?=$project->id?>/escalated">'+response<?=$project->id?>.stats[0][index]+'</a>');
							// Open Moderation Window
							$('#esc<?=$project->id?>').on("click", function(e){
								window.open(this.href, $(this).attr('id'), "width=1600, height=900");
								e.preventDefault();
							});
					}
				});
			}
		});

	<?php endforeach; ?>
	}, 5000);
</script> 