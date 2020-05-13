<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Users History'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</div>
<div class="usersHistory index large-10 medium-9 columns">
    <table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('id') ?></th>
            <th><?= $this->Paginator->sort('user_id') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th><?= $this->Paginator->sort('description') ?></th>
            <th><?= $this->Paginator->sort('ipaddress') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($usersHistory as $usersHistory): ?>
        <tr>
            <td><?= $this->Number->format($usersHistory->id) ?></td>
            <td>
                <?= $usersHistory->has('user') ? $this->Html->link($usersHistory->user->email, ['controller' => 'Users', 'action' => 'view', $usersHistory->user->id]) : '' ?>
            </td>
            <td><?= h($usersHistory->created) ?></td>
            <td><?= h($usersHistory->description) ?></td>
            <td><?= h($usersHistory->ipaddress) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $usersHistory->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $usersHistory->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $usersHistory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersHistory->id)]) ?>
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
