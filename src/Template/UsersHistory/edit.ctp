<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $usersHistory->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $usersHistory->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Users History'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</div>
<div class="usersHistory form large-10 medium-9 columns">
    <?= $this->Form->create($usersHistory) ?>
    <fieldset>
        <legend><?= __('Edit Users History') ?></legend>
        <?php
            echo $this->Form->input('user_id', ['options' => $users]);
            echo $this->Form->input('description');
            echo $this->Form->input('ipaddress');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
