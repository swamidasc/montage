<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Users Type'), ['action' => 'edit', $usersType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users Type'), ['action' => 'delete', $usersType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Type'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="usersTypes view large-10 medium-9 columns">
    <h2><?= h($usersType->id) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Typename') ?></h6>
            <p><?= h($usersType->typename) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($usersType->id) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($usersType->created) ?></p>
            <h6 class="subheader"><?= __('Modified') ?></h6>
            <p><?= h($usersType->modified) ?></p>
        </div>
    </div>
</div>
