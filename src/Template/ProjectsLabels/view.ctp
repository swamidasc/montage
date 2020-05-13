<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Projects Label'), ['action' => 'edit', $projectsLabel->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Projects Label'), ['action' => 'delete', $projectsLabel->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projectsLabel->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Projects Labels'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Projects Label'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="projectsLabels view large-10 medium-9 columns">
    <h2><?= h($projectsLabel->name) ?></h2>
    <div class="row">
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($projectsLabel->id) ?></p>
            <h6 class="subheader"><?= __('Status Id') ?></h6>
            <p><?= $this->Number->format($projectsLabel->status_id) ?></p>
            <h6 class="subheader"><?= __('Name') ?></h6>
            <p><?= $this->Number->format($projectsLabel->name) ?></p>
            <h6 class="subheader"><?= __('Multiple') ?></h6>
            <p><?= $this->Number->format($projectsLabel->multiple) ?></p>
        </div>
    </div>
</div>
