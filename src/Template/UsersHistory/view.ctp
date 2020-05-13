<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Users History'), ['action' => 'edit', $usersHistory->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users History'), ['action' => 'delete', $usersHistory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersHistory->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users History'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users History'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</div>
<div class="usersHistory view large-10 medium-9 columns">
    <h2><?= h($usersHistory->id) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('User') ?></h6>
            <p><?= $usersHistory->has('user') ? $this->Html->link($usersHistory->user->email, ['controller' => 'Users', 'action' => 'view', $usersHistory->user->id]) : '' ?></p>
            <h6 class="subheader"><?= __('Description') ?></h6>
            <p><?= h($usersHistory->description) ?></p>
            <h6 class="subheader"><?= __('Ipaddress') ?></h6>
            <p><?= h($usersHistory->ipaddress) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($usersHistory->id) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($usersHistory->created) ?></p>
        </div>
    </div>
</div>
