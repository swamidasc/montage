<h2><?= h($project->name) ?></h2>
<p class="back"><?= $this->Html->link(__('All Projects'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>
<p class="new"><?= $this->Html->link(__('Edit Project'), ['action' => 'edit', $project->id], array('class' => 'ion-edit')) ?></p>


<div class="actions columns large-2 medium-3 hidden">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Project'), ['action' => 'edit', $project->id]) ?> </li>
        <li><?= $this->Html->link(__('List Projects'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</div>


<div class="callout right">
	<a href="/projects/moderate/<?=$project->id?>" class="btn">Moderate</a>
</div>


<ul class="details">
	<?php if ($role == 1) { ?>
	<li><strong><?= __('Client Name') ?></strong> <span><?=$project->client->companyname ?></span></li>
    <?php } ?>
	<li><strong><?= __('Group') ?></strong> <span><?=$project->client->companyname ?></span></li>
	<li><strong><?= __('Type') ?></strong> <span><?=$project->projects_type->name ?></span></li>
	<li><strong><?= __('Created') ?></strong> <span><?= h($project->created) ?></span></li>
</ul>

<?php if (!empty($project->users)): ?>
<h3>Project Users</h3>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Email') ?></th>
            <th><?= __('User Type') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($project->users as $users): 
	          	if ($users->user_type_id <= $role) {
				  	continue;
				}
        ?>
        <tr>
            <td><?= h($users->email) ?></td>
            <td><?= h($users->users_type->typename) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('<span>Edit</span>'), ['controller' => 'Users', 'action' => 'edit', $users->id], array('class' => 'ion-edit', 'escape' => FALSE)) ?>
                <?= $this->Form->postLink(__('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="icon" d="M128 405.429C128 428.846 147.198 448 170.667 448h170.667C364.802 448 384 428.846 384 405.429V160H128v245.429zM416 96h-80l-26.785-32H202.786L176 96H96v32h320V96z"/></svg><span>Delete</span>'), ['controller' => 'Users', 'action' => 'delete', $users->id], array('class' => 'ion-close-circled', 'escape' => FALSE, 'confirm' => __('Are you sure you want to delete #{0}?', $users->id))) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
<?php endif; ?>
