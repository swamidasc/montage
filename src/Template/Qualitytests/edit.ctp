<h2><?= __('Edit QC Test') ?></h2>
<p class="back"><?= $this->Html->link(__('Cancel'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>


<!--
<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Projects'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</div>
-->


<?= $this->Form->create($qualitytest, array('id' => 'edit')) ?>
    <fieldset>
        <?php
        
        	echo $this->Form->input('name');
?>
			<p><strong>Project: <?=$project->name?></strong></p>
<?php			
			echo $this->Form->label('','QC Test Description');
			echo $this->Form->textarea('description');       
?>

<fieldset id="status">
	<label>Test Images</label>
    <table id="statusTable">     
    	<?php for ($i = 0; $i <= 4; $i++) { 
	    	
	    	if (isset($qualitytest['qualityimages'][$i]->statusid)) {
		    	$thestatus = $qualitytest['qualityimages'][$i]->statusid;
	    	} else {
		    	$thestatus = "";
	    	}
	    	
    	?>
		<tr id="status_1">
			<td><strong>Image URL:</strong> 
			<input class="statname" type="text" name="iurl_<?=$i?>" value="<?php if (isset($qualitytest['qualityimages'][$i]->iurl)) { echo $qualitytest['qualityimages'][$i]->iurl; }?>" />
			<br>
			<?php	
			echo $this->Form->input(
			'status'."_".$i, 
			[
			'type' => 'select',
			'multiple' => false,
			'options' => $statuses, 
			'empty' => true,
			'label' => 'Status:',
			'default' => $thestatus
			]);	?><strong>Reason:</strong> <textarea name="reason_<?=$i?>"><?php if (isset($qualitytest['qualityimages'][$i]->reason)) { echo $qualitytest['qualityimages'][$i]->reason; }?></textarea></td>
			<td class="actions"><a href="javascript:void(0);" class="ion-close-circled removeStatus"></a></td>
		</tr>
		<?php } ?>
 </table>

    </fieldset><br>
    <?= $this->Form->button(__('Update QC Test'), array('class' => 'btn')) ?>
    <?= $this->Form->end() ?>