<h2>Add User</h2>
<p class="back"><?= $this->Html->link(__('Cancel'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>

<?= $this->Form->create($user, array('id' => 'edit')) ?>
    <?php
    	if ($role == 1) {
			echo $this->Form->input(
			'group_id', 
			[
			'type' => 'select',
			'multiple' => false,
			'options' => $groups, 
			'empty' => true,
			'label' => 'Moderation Group'
			]);
		} else {
			 echo $this->Form->hidden('group_id', ['value'=>$group]);
		}
		
		echo $this->Form->input(
		'user_type_id', 
		[
		'type' => 'select',
		'multiple' => false,
		'options' => $userstypes, 
		'empty' => true,
		'label' => 'User Type'
		]);
		
		
        echo $this->Form->input('email');
        echo $this->Form->input('password');
		echo $this->Form->input('active', array('type'=>'checkbox', 'format' => array('before', 'input', 'between', 'label', 'after', 'error' )));


    ?>
	<?= $this->Form->button(__('Submit'), array('class' => 'btn')) ?>
<?= $this->Form->end() ?>
