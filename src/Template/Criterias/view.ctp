<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Projects Status'), ['action' => 'edit', $projectsStatus->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Projects Status'), ['action' => 'delete', $projectsStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projectsStatus->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Projects Statuses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Projects Status'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?> </li>
    </ul>
</div>
<div class="projectsStatuses view large-10 medium-9 columns">
    <h2><?= h($projectsStatus->name) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Project') ?></h6>
            <p><?= $projectsStatus->has('project') ? $this->Html->link($projectsStatus->project->name, ['controller' => 'Projects', 'action' => 'view', $projectsStatus->project->projectid]) : '' ?></p>
            <h6 class="subheader"><?= __('Name') ?></h6>
            <p><?= h($projectsStatus->name) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($projectsStatus->id) ?></p>
            <h6 class="subheader"><?= __('Statval') ?></h6>
            <p><?= $this->Number->format($projectsStatus->statval) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($projectsStatus->created) ?></p>
            <h6 class="subheader"><?= __('Modified') ?></h6>
            <p><?= h($projectsStatus->modified) ?></p>
        </div>
    </div>
</div>
