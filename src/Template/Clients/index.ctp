<h2>Clients</h2>
<p class="new"><?= $this->Html->link(__('New Client'), ['action' => 'add'], array('class' => 'btn')) ?></p>

<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><?= $this->Paginator->sort('companyname','Company') ?></th>
            <th><?= $this->Paginator->sort('contactname','Contact') ?></th>
            <th><?= $this->Paginator->sort('contactphone','Phone') ?></th>
            <th><?= $this->Paginator->sort('contactemail','Email') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($clients as $client): ?>
        <tr>
            <td><strong><?= $this->Html->link(__(h($client->companyname)), ['action' => 'edit', $client->id]) ?></strong></td>
            <td><?= h($client->contactname) ?></td>
            <td><?= h($client->contactphone) ?></td>
            <td><?= h($client->contactemail) ?></td>
            <td class="actions">
	            <?= $this->Html->link(__('<span>Edit</span>'), ['action' => 'edit', $client->id], array('class' => 'ion-edit', 'escape' => FALSE)) ?>
	            <?= $this->Form->postLink(__('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="icon" d="M128 405.429C128 428.846 147.198 448 170.667 448h170.667C364.802 448 384 428.846 384 405.429V160H128v245.429zM416 96h-80l-26.785-32H202.786L176 96H96v32h320V96z"/></svg><span>Delete</span>'), ['action' => 'delete', $client->id], array('class' => 'ion-close-circled', 'escape' => FALSE, 'confirm' => __('Are you sure you want to delete #{0}?', $client->id))) ?>
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
