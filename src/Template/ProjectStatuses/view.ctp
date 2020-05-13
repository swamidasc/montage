<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Project Status'), ['action' => 'edit', $projectStatus->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Project Status'), ['action' => 'delete', $projectStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projectStatus->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Project Statuses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project Status'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="projectStatuses view large-10 medium-9 columns">
    <h2><?= h($projectStatus->name) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Name') ?></h6>
            <p><?= h($projectStatus->name) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($projectStatus->id) ?></p>
            <h6 class="subheader"><?= __('Projectid') ?></h6>
            <p><?= $this->Number->format($projectStatus->projectid) ?></p>
            <h6 class="subheader"><?= __('Statval') ?></h6>
            <p><?= $this->Number->format($projectStatus->statval) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($projectStatus->created) ?></p>
            <h6 class="subheader"><?= __('Modified') ?></h6>
            <p><?= h($projectStatus->modified) ?></p>
        </div>
    </div>
</div>
