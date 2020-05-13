<h2><?= __('Edit Group') ?></h2>
<p class="back"><?= $this->Html->link(__('All Groups'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>


<?= $this->Form->create($group, array('id' => 'edit')) ?>
    <?php
        echo $this->Form->input('name');
        echo $this->Form->input('contactname', array('label' => 'Contact Name'));
        echo $this->Form->input('contactphone', array('label' => 'Contact Phone'));
        echo $this->Form->input('contactemail', array('label' => 'Contact Email'));
        echo $this->Form->input('allowedips', array('label' => 'Allowed IPs'));        
    ?>
    <?= $this->Form->button(__('Update'), array('class' => 'btn')) ?>
	<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $group->id], array('class' => 'btn grey delete', 'confirm' => __('Are you sure you want to delete #{0}?', $group->id))) ?>	
<?= $this->Form->end() ?>


<div class="actions columns large-2 medium-3 hidden">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $group->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $group->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</div>
