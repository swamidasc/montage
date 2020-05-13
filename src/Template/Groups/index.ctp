<h2>Moderation Groups</h2>
<p class="new"><?= $this->Html->link(__('New Group'), ['action' => 'add'], array('class' => 'btn')) ?></p>

<div class="actions columns large-2 medium-3 hidden">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('New Group'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</div>

<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('name','Company') ?></th>
            <th><?= $this->Paginator->sort('contactname','Contact') ?></th>
            <th><?= $this->Paginator->sort('contactphone','Phone') ?></th>
            <th><?= $this->Paginator->sort('contactemail','Email') ?></th>
            <th><?= $this->Paginator->sort('created') ?></th>
            <th><?= $this->Paginator->sort('modified') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($groups as $group): ?>
        <tr>
            <td><strong><?= $this->Html->link(__(h($group->name)), ['action' => 'view', $group->id]) ?></strong></td>
            <td><?= h($group->contactname) ?></td>
            <td><?= h($group->contactphone) ?></td>
            <td><?= h($group->contactemail) ?></td>
            <td><?= h($group->created) ?></td>
            <td><?= h($group->modified) ?></td>
            <td class="actions">
	            <?= $this->Html->link(__('<span>Edit</span>'), ['action' => 'edit', $group->id], array('class' => 'ion-edit', 'escape' => FALSE)) ?>
	            <?= $this->Form->postLink(__('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="icon" d="M128 405.429C128 428.846 147.198 448 170.667 448h170.667C364.802 448 384 428.846 384 405.429V160H128v245.429zM416 96h-80l-26.785-32H202.786L176 96H96v32h320V96z"/></svg><span>Delete</span>'), ['action' => 'delete', $group->id], array('class' => 'ion-close-circled', 'escape' => FALSE, 'confirm' => __('Are you sure you want to delete #{0}?', $group->id))) ?>

            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>
<div>
    <ul class="pagination">
        <?= $this->Paginator->prev('&lsaquo; ' . __('Prev'), array('escape' => FALSE)) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('Next') . ' &rsaquo;', array('escape' => FALSE)) ?>
    </ul>
    <p class="total"><?= $this->Paginator->counter() ?></p>
</div>
