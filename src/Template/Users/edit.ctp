<h2>Edit User</h2>

<?php if ($role == 6 || $role == 5) { ?>
<p class="back"><?= $this->Html->link(__('< Back'), ['controller' => 'projects', 'action' => 'index'], array('class' => 'ion-chevron-left back-btn')) ?></p>
<?php } else { ?>
<p class="back"><?= $this->Html->link(__('All Users'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>
<?php } ?>

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
			 echo $this->Form->hidden('groupid', ['value'=>$group]);
		}
		
		if ($role != 6 && $role != 5) {
		echo $this->Form->input(
		'user_type_id', 
		[
		'type' => 'select',
		'multiple' => false,
		'options' => $userstypes, 
		'empty' => true,
		'label' => 'User Type'
		]);
		} else {
			echo $this->Form->hidden('user_type_id', ['value'=>$user->user_type_id]);
		}
		
        echo $this->Form->input('email');
        echo $this->Form->input('password');
        
        if ($role != 6 && $role != 5) {
			echo $this->Form->input('active', array('type'=>'checkbox', 'format' => array('before', 'input', 'between', 'label', 'after', 'error' )));
		}
    ?>
	<?= $this->Form->button(__('Update'), array('class' => 'btn')) ?>
	
<?php if ($role =! 6) { ?>	
	<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], array('class' => 'btn grey delete', 'confirm' => __('Are you sure you want to delete #{0}?', $user->id))) ?>	
<?php } ?>
<?= $this->Form->end() ?>