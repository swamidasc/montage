<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Project Status'), ['action' => 'add']) ?></li>
    </ul>
</div>
<div class="projectStatuses index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('projectid') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('statval') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th><?= $this->Paginator->sort('modified') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($projectStatuses as $projectStatus): ?>
        <tr>
            <td><?= $this->Number->format($projectStatus->id) ?></td>
            <td><?= $this->Number->format($projectStatus->projectid) ?></td>
            <td><?= h($projectStatus->name) ?></td>
            <td><?= $this->Number->format($projectStatus->statval) ?></td>
            <td><?= h($projectStatus->created) ?></td>
            <td><?= h($projectStatus->modified) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $projectStatus->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $projectStatus->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $projectStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projectStatus->id)]) ?>
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
