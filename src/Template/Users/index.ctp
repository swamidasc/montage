<h2>
<?=isset($active)?"Active":"All"?> Users</h2>
<p class="new"><?= $this->Html->link(__('New User'), ['action' => 'add'], array('class' => 'btn')) ?></p>
<table cellpadding="0" cellspacing="0">
<thead>
	<tr><td colspan="6" align="center">
			
			<?php if (!isset($active)) { ?>
			<a href="/users/active">View Active Users Only</a>
			
			<?php } else { ?>
			<a href="/users">View All Users</a>
			<?php } ?>
		</td>
	</tr>
    <tr>
    	<th><?= $this->Paginator->sort('Users.email') ?></th>
        <th><?= $this->Paginator->sort('Users.group_id') ?></th>
        <th><?= $this->Paginator->sort('Users.user_type_id') ?></th>
        <th><?= $this->Paginator->sort('Users.created') ?></th>
        <th><?= $this->Paginator->sort('Users.modified') ?></th>
        <th class="actions"><?= __('Actions') ?></th>
    </tr>
</thead>
<tbody>
<?php foreach ($users as $user): 
	
	if (isset($active)) {
		if (!in_array($user->id,$activeusers)) {
			continue;
		}
	}
	
?>

    <tr>
        <td><strong><?= $this->Html->link(__(h($user->email)), ['action' => 'edit', $user->id]) ?></strong></td>
        <td><?=$user->group->name ?></td>
        <td><?=$user->users_type->typename ?></td>
        <td><?= h($user->created) ?></td>
        <td><?= h($user->modified) ?></td>
        <td class="actions">
            <a href="/users/report/<?=$user->id?>" class="ion-stats-bars" title="Reports"><span>Reports</span></a>
            <?= $this->Html->link(__('<span>Edit</span>'), ['action' => 'edit', $user->id], array('class' => 'ion-edit', 'escape' => FALSE, 'title' => 'Edit')) ?>
            <?= $this->Form->postLink(__('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="icon" d="M128 405.429C128 428.846 147.198 448 170.667 448h170.667C364.802 448 384 428.846 384 405.429V160H128v245.429zM416 96h-80l-26.785-32H202.786L176 96H96v32h320V96z"/></svg><span>Delete</span>'), ['action' => 'delete', $user->id], array('class' => 'ion-close-circled', 'escape' => FALSE, 'title' => 'Delete', 'confirm' => __('Are you sure you want to delete #{0}?', $user->id))) ?>
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