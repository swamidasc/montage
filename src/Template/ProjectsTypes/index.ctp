<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Projects Type'), ['action' => 'add']) ?></li>
    </ul>
</div>
<div class="projectsTypes index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('name') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($projectsTypes as $projectsType): ?>
        <tr>
            <td><?= $this->Number->format($projectsType->id) ?></td>
            <td><?= h($projectsType->name) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $projectsType->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $projectsType->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $projectsType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projectsType->id)]) ?>
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
