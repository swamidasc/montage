<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Projects Status'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
    </ul>
</div>
<div class="projectsStatuses index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('project_id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('statval') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th><?= $this->Paginator->sort('modified') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($projectsStatuses as $projectsStatus): ?>
        <tr>
            <td><?= $this->Number->format($projectsStatus->id) ?></td>
            <td>
                <?= $projectsStatus->has('project') ? $this->Html->link($projectsStatus->project->name, ['controller' => 'Projects', 'action' => 'view', $projectsStatus->project->projectid]) : '' ?>
            </td>
            <td><?= h($projectsStatus->name) ?></td>
            <td><?= $this->Number->format($projectsStatus->statval) ?></td>
            <td><?= h($projectsStatus->created) ?></td>
            <td><?= h($projectsStatus->modified) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $projectsStatus->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $projectsStatus->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $projectsStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projectsStatus->id)]) ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
