<?= $this->Html->script('tinymce/tinymce.min.js') ?>
<h2><?= __('Add Project') ?></h2>
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


<?= $this->Form->create($project, array('id' => 'edit')) ?>
    <fieldset>
        <?php
        
        
        	if ($role == 1) {
			echo $this->Form->input(
			'client_id', 
				[
				'type' => 'select',
				'multiple' => false,
				'options' => $clients, 
				'empty' => true,
				'label' => 'Client'
				]);
			} else {
				
			}
			

			echo $this->Form->input(
			'group_id', 
			[
			'type' => 'select',
			'multiple' => false,
			'options' => $groups, 
			'empty' => true,
			'label' => 'Moderation Group'
			]);
			
            echo $this->Form->input('name');

			echo $this->Form->input(
			'project_type_id', 
			[
			'type' => 'select',
			'multiple' => false,
			'options' => $projectstypes, 
			'empty' => true,
			'label' => 'Project Type'
			]);
			
			
			echo "<p><strong>Proxy Images: ".$this->Form->checkbox('proxy')."</strong></p>";
			
			echo "<p><strong>Thumbnail Images: ".$this->Form->checkbox('thumb')."</strong></p>";
			
			
			echo "<p><strong>Scroll Vertical: ".$this->Form->checkbox('scroll_vert')."</strong></p>";
			
			
			echo "<p><strong>Admin Escalate to Client: ".$this->Form->checkbox('client_escalate')."</strong></p>";
			
			echo "<p><strong>Auto-Moderate Exact Duplicates: ".$this->Form->checkbox('automod')."</strong></p>";
			
			echo "<p><strong>Download Images: ".$this->Form->checkbox('download')."</strong></p>";
			
			echo "<p><strong>AIM Nudity Check: ".$this->Form->checkbox('aim_nudity')."</strong></p>";
			
			echo "<p><strong>Escalate Memos: ".$this->Form->checkbox('escalatememo')."</strong></p>";
			
			echo "<p><strong>Allow Re-Moderate: ".$this->Form->checkbox('fixafter')."</strong></p>";
			
			echo $this->Form->input('profanity_key');	
			
			echo $this->Form->input('return_limit');			
			
			echo $this->Form->input('table_name');	
			
			echo $this->Form->input('endpoint');				
			
            echo $this->Form->input('startdate',[ 'label' => [ 'text' => 'Start Date' ]]);
            
            echo $this->Form->input('enddate',[ 'label' => [ 'text' => 'End Date' ]]);			
?>

<fieldset id="status">
	<label>Status</label>
    <table id="statusTable">
        <tr>
            <th>Status Name</th>
            <th>Status Value</th>
            <th>Status Color</th>
            <th>Default?</th>            
            <th>Actions</th>
        </tr>
        <tr><td>Escalate</td><td>-2</td><td bgcolor="#FF7300"></td>
        <td align="center"><input class="statval" type="radio" name="labeldefault" value="1" /></td>
        <td class="actions">&nbsp;</td></tr> 
        <input class="statname" type="hidden" name="statname_1" value="Escalate" />
        <input class="statval" type="hidden" name="statval_1" value="-2" />
        <input class="statval" type="hidden" name="statcolor_1" value="#FF7300" />
		<tr><td>Broken</td><td>-1</td><td bgcolor="#E3A700"></td>
		<td align="center"><input class="statval" type="radio" name="labeldefault" value="2"  /></td>
		<td class="actions">&nbsp;</td></tr>
        <input class="statname" type="hidden" name="statname_2" value="Broken" />
        <input class="statval" type="hidden" name="statval_2" value="-1" />	
        <input class="statval" type="hidden" name="statcolor_2" value="#E3A700" />	
		<tr><td>Pending</td><td>0</td><td bgcolor="#FFFFFF"></td>
		<td align="center"><input class="statval" type="radio" checked name="labeldefault" value="0"  /></td>
		<td class="actions">&nbsp;</td></tr>       
    </table>
	<p><a href="javascript:void(0);" id="addStatus" class="btn">Add Status</a></p>
<?php  
echo $this->Form->label('','Moderation Criteria');
echo $this->Form->textarea('criteria'); ?>
</fieldset>	

    </fieldset>
    <?= $this->Form->button(__('Add Project'), array('class' => 'btn')) ?>
    <?= $this->Form->end() ?>

<script type="text/javascript"> 
    tinyMCE.init({ 
        theme : "modern", 
        mode : "textareas", 
        convert_urls : false,
		plugins: "textcolor colorpicker lists",
		toolbar: "forecolor backcolor"        
    });
</script>      