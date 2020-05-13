<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Projects Type'), ['action' => 'edit', $projectsType->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Projects Type'), ['action' => 'delete', $projectsType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projectsType->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Projects Types'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Projects Type'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="projectsTypes view large-10 medium-9 columns">
    <h2><?= h($projectsType->name) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Name') ?></h6>
            <p><?= h($projectsType->name) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($projectsType->id) ?></p>
        </div>
    </div>
</div>
