<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Users Project'), ['action' => 'add']) ?></li>
    </ul>
</div>
<div class="usersProjects index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('user_id') ?></th>
            <th><?= $this->Paginator->sort('project_id') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($usersProjects as $usersProject): ?>
        <tr>
            <td><?= $this->Number->format($usersProject->id) ?></td>
            <td><?= $this->Number->format($usersProject->user_id) ?></td>
            <td><?= $this->Number->format($usersProject->project_id) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $usersProject->userid]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $usersProject->userid]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $usersProject->userid], ['confirm' => __('Are you sure you want to delete # {0}?', $usersProject->userid)]) ?>
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
