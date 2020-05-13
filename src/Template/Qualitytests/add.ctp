<h2><?= __('Add QC Test') ?></h2>
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

			echo $this->Form->input(
			'project_id', 
			[
			'type' => 'select',
			'multiple' => false,
			'options' => $projects, 
			'empty' => true,
			'label' => 'Project'
			]);
			
			echo $this->Form->label('','QC Test Description');
			echo $this->Form->textarea('description');   
    
?>
    </fieldset><br>
    <?= $this->Form->button(__('Add QC Test'), array('class' => 'btn')) ?>
    <?= $this->Form->end() ?>