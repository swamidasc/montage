<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Projects Label'), ['action' => 'add']) ?></li>
    </ul>
</div>
<div class="projectsLabels index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('status_id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th><?= $this->Paginator->sort('multiple') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($projectsLabels as $projectsLabel): ?>
        <tr>
            <td><?= $this->Number->format($projectsLabel->id) ?></td>
            <td><?= $this->Number->format($projectsLabel->status_id) ?></td>
            <td><?= $this->Number->format($projectsLabel->name) ?></td>
            <td><?= $this->Number->format($projectsLabel->multiple) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $projectsLabel->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $projectsLabel->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $projectsLabel->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projectsLabel->id)]) ?>
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
