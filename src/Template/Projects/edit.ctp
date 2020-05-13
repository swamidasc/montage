<?= $this->Html->script('tinymce/tinymce.min.js') ?>
<h2><?= __('Edit Project') ?></h2>
<p class="back"><?= $this->Html->link(__('All Projects'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>


    	<?php if ($role <= 4 && $training==1) { ?>
  	           <p> <center> <?= $this->Form->postLink(__('<span>Reset Queue</span>'), ['action' => 'resetQueue', $project->id], array('escape' => FALSE, 'confirm' => __('Are you sure you want to reset the queue for this project?', $project->name))) ?>  		
    	<?php } ?> </center></p>
    	

<?= $this->Form->create($project, array('id' => 'edit', 'class' => 'project')) ?>
	
    <fieldset>
   	
   	
        <?php			
			if ($role == 1) {
				echo $this->Form->input(
				'group_id', 
				[
				'type' => 'select',
				'multiple' => false,
				'options' => $groups, 
				'empty' => true,
				'label' => 'Moderation Group',
				'default' => $project['group_id']		
				]);
			} else {
				//echo "Group Name: ".$project['group']['name']."</br>";
				
			}
		
			if ($role == 1) {
            	echo $this->Form->input('name');
            } else {
	        	echo "<p><strong>Project Name:</strong> ".$project['name']."</p>";    
            }

			if ($role == 1) {
				echo $this->Form->input(
				'project_type_id', 
				[
				'type' => 'select',
				'multiple' => false,
				'options' => $projectstypes, 
				'empty' => true,
				'label' => 'Project Type',
				'default' => $project['project_type_id']
				]);
			} else {
				echo "<p><strong>Project Type:</strong> ".$project->projects_type['name']."</p>";
			}
			
			if ($project->project_type_id == 2) {
				echo "<p><strong>Loop Videos: ".$this->Form->checkbox('loop_video')."</strong></p>";
			}
			
			if ($role < 2) { 
				echo "<p><strong>Proxy Images: ".$this->Form->checkbox('proxy')."</strong></p>";
				echo "<p><strong>Thumbnail Images: ".$this->Form->checkbox('thumb')."</strong></p>";
				echo "<p><strong>Scroll Vertical: ".$this->Form->checkbox('scroll_vert')."</strong></p>";
				echo "<p><strong>Admin Escalate to Client: ".$this->Form->checkbox('client_escalate')."</strong></p>";
				echo "<p><strong>Auto-Moderate Exact Duplicates: ".$this->Form->checkbox('automod')."</strong></p>";
				echo "<p><strong>Download Images: ".$this->Form->checkbox('download')."</strong></p>";
				echo "<p><strong>AIM Nudity Check: ".$this->Form->checkbox('aim_nudity')."</strong></p>";
				echo "<p><strong>Escalate Memos: ".$this->Form->checkbox('escalatememo')."</strong></p>";
				echo "<p><strong>Allow Re-Moderate: ".$this->Form->checkbox('fixafter')."</strong></p>";
				
			} elseif ($role == 3) {
				echo "<p><strong>Proxy Images:</strong> ";
				echo $project['proxy']==1?"Yes":"No"."</p>";
				echo "<p><strong>Thumbnail Images:</strong> ";
				echo $project['thumb']==1?"Yes":"No"."</p>";				
				echo "<p><strong>Scroll Vertical:</strong> ";
				echo $project['scroll_vert']==1?"Yes":"No"."</p>";	
			}
			
			
			
			
			
			echo $this->Form->input('profanity_key');
			
			
			if ($role < 2) { 
				echo $this->Form->input('return_limit');			
			} else {
				echo "<p><strong>Return Limit:</strong> ";
				echo $project['return_limit']?$project['return_limit']:"0";
				echo " Seconds</p>";	
			}
		
			if ($role < 2) {
			echo $this->Form->input('table_name');							

			echo $this->Form->input('endpoint');	

            echo $this->Form->input('startdate',[ 'label' => [ 'text' => 'Start Date' ]]);
            
            echo $this->Form->input('enddate',[ 'label' => [ 'text' => 'End Date' ]]);		
			}


		if (isset($defimages) && $project->id != 122) {
			$numberofimages = $defimages;
		} else {
			$numberofimages = $project['images'];
		}

            
 		$options['32'] = 32;
		$options['21'] = 21;
		$options['15'] = 15;				
		$options['10'] = 10;
		$options['8'] = 8;
		$options['6'] = 6;				
		$options['3'] = 3;				
		$options['1'] = 1;				
		echo $this->Form->input('defimages', array(
    'options' => $options,
    'type' => 'select',
    'default' => $numberofimages,
    'label' => 'Default Number of Images Per Page',
    
   )
);           

if ($role < 2) {            
?>  
 <fieldset id="status">
	<label>Status</label>
    <table id="statusTable">
        <tr>
            <th>Status Name</th>
            <th>Status Value</th>
            <th>Status Color</th>
            <th>Tag?</th>
            <th>Default?</th>
            <th>Actions</th>
        </tr>   
<?php if ($project->labels) { 
	$labelcount = 0;	
	foreach ($project->labels as $label) { 
	//if ($label['statval'] < 0) {
	//	continue;
	//}
 	$labelcount++;

	if ($labelcount == 1) { ?>
        <tr><td>Escalate</td><td>-2</td><td bgcolor="#FF7300"></td><td></td>
        <td align="center"><input class="statval" type="radio" name="labeldefault" <?=$label->def==1?"checked":""?> value="1" /></td>
        <td class="actions">&nbsp;</td></tr> 
        <input class="statname" type="hidden" name="statname_1" value="Escalate" />
        <input class="statval" type="hidden" name="statval_1" value="-2" />
        <input class="statval" type="hidden" name="statcolor_1" value="#FF7300" />
        <input class="statval" type="hidden" name="tag_1" value="0" />
        <input class="statval" type="hidden" name="sort_1" value="<?=$label['sort']?>" />
		<input class="statval" type="hidden" name="confirm_1" value="<?=$label['confirm']?>" />
		<input class="statval" type="hidden" name="pinginplace_1" value="<?=$label['pinginplace']?>" />	        
        
   <?php } elseif ($labelcount == 2) { ?>
		<tr><td>Broken</td><td>-1</td><td bgcolor="#E3A700"></td><td></td>
		<td align="center"><input class="statval" type="radio" name="labeldefault" value="2" <?=$label->def==1?"checked":""?> /></td>
		<td class="actions">&nbsp;</td></tr>
        <input class="statname" type="hidden" name="statname_2" value="<?=$label['name']?>" />
        <input class="statval" type="hidden" name="statval_2" value="-1" />	
        <input class="statval" type="hidden" name="statcolor_2" value="#E3A700" />
        <input class="statval" type="hidden" name="tag_2" value="0" />
        <input class="statval" type="hidden" name="sort_2" value="<?=$label['sort']?>" />
		<input class="statval" type="hidden" name="confirm_2" value="<?=$label['confirm']?>" />
		<input class="statval" type="hidden" name="pinginplace_2" value="<?=$label['pinginplace']?>" />	          
  	<?php } elseif ($labelcount == 3) { ?>	
		<tr><td>Pending</td><td>0</td><td bgcolor="#FFFFFF"></td><td></td>
		<td align="center"><input class="statval" type="radio" name="labeldefault" value="0"  /></td>
		<td class="actions">&nbsp;</td></tr>  
	<?php } ?>	
	
	<?php if ($labelcount >= 3) { ?>	
<tr id="status_<?=$labelcount?>">
	<td><input class="statname" type="text" name="statname_<?=$labelcount?>" value="<?=$label['name']?>" required="required"/></td>
	<td><input class="statval" type="text" name="statval_<?=$labelcount?>" required="required" value="<?=$label['statval']?>" /></td>
	<td><input class="statcolor" type="text" name="statcolor_<?=$labelcount?>" required="required" value="<?=$label['color']?>" /></td>
	<td align="center"><input class="statcolor" type="checkbox" name="tag_<?=$labelcount?>" value="1" <?=$label['tag']==1?"checked":""?> /></td>
	<td align="center"><input class="statval" type="radio" name="labeldefault" value="<?=$labelcount?>" <?=$label['def']==1?"checked":""?> /></td>
	<td class="actions"><a href="javascript:void(0);" class="ion-close-circled removeStatus"></a></td></tr>
	<input class="statval" type="hidden" name="sort_<?=$labelcount?>" value="<?=$label['sort']?>" />
	<input class="statval" type="hidden" name="confirm_<?=$labelcount?>" value="<?=$label['confirm']?>" />
	<input class="statval" type="hidden" name="pinginplace_<?=$labelcount?>" value="<?=$label['pinginplace']?>" />	
	<input class="statval" type="hidden" name="tag_<?=$labelcount?>" value="<?=$label['tag']?>" />
</td>
</tr>

<?php } } ?>    

  

<?php }  } ?>

   </table>
	
<?php if ($role < 2) { ?>
<p><a href="javascript:void(0);" id="addStatus" class="btn">Add Status</a></p>
<?php
}

if ($role <= 4) {
echo "<p>".$this->Form->label('','Moderation Criteria');
echo $this->Form->textarea('criteria');
} ?>
</fieldset>	


<?php
if ($role < 3) { 
echo '<p class="multiple">';
	echo $this->Form->label('managers._ids','Managers');
	echo $this->Form->select('users._ids', $groupmanagers, ['multiple' => true,'value' => $usersprojects]);
echo '</p>';
} else {
?>
<input type="hidden" name="users[_ids]" value="">
<input type="hidden" name="users[_ids][]" value="<?=implode(',',array_keys(array_intersect_key($groupmanagers,$usersprojects)));?>" />
<?php
}


if ($role != 1) {
	$assignedclients = array_intersect_key($clientusers, $usersprojects);

?>
<input type="hidden" name="clientusers[_ids]" value="">
<?php foreach (array_keys($assignedclients) as $newclients) { 
	if (isset($newclients)) {
?><input type="hidden" name="clientusers[_ids][]" value="<?=$newclients?>" />
<?php } } ?>
<?php 
}

if ($role < 3) { 
	echo '<p class="multiple">';
	echo $this->Form->label('moderators._ids','Moderators');
	echo $this->Form->select('moderators._ids', $moderators,['label' => 'Moderators','multiple' => true,'value' => $usersprojects]);
echo '</p>';
} 
	 
if ($role == 1) { 
	echo '<p class="multiple">';
	echo $this->Form->label('clientusers._ids','Client Users');
	echo $this->Form->select('clientusers._ids', $clientusers,['label' => 'Client Users','multiple' => true,'value' => $usersprojects]);
echo '</p>';
}
?>	 
	 
    </fieldset>
    <p></p>
    <?= $this->Form->button(__('Update'), array('class' => 'btn')) ?>
    <?= $this->Form->end() ?>
    
<script type="text/javascript"> 
    tinyMCE.init({ 
        theme : "modern", 
        mode : "textareas", 
        convert_urls : false,
		plugins: "textcolor colorpicker lists paste code preview",
		toolbar: "forecolor backcolor code preview blockquote"
    });
</script>    