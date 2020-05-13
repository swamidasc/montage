<h2><?= h($group->name) ?></h2>
<p class="back"><?= $this->Html->link(__('All Groups'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>
<p class="new"><?= $this->Html->link(__('Edit Group'), ['action' => 'edit', $group->id], array('class' => 'ion-edit')) ?></p>


<ul class="details">
    <li><strong><?= __('Contact Name') ?></strong> <span><?= h($group->contactname) ?></span></li>
    <li><strong><?= __('Contact Phone') ?></strong> <span><?= h($group->contactphone) ?></span></li>
    <li><strong><?= __('Contact Email') ?></strong> <span><?= h($group->contactemail) ?></span></li>
    <li><strong><?= __('Created') ?></strong> <span><?= h($group->created) ?></span></li>
    <li><strong><?= __('Group Id') ?></strong> <span><?= $this->Number->format($group->id) ?></span></li>
</ul>


<h3><?= __('Group Projects') ?></h3>
<?php if (!empty($group->projects)): ?>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?= __('Name') ?></th>
        <th><?= __('Table Name') ?></th>
        <th><?= __('Return Limit') ?></th>
        <th><?= __('Created') ?></th>
        <th><?= __('Start Date') ?></th>
        <th><?= __('End Date') ?></th>
        <th class="actions"><?= __('Actions') ?></th>
    </tr>
    <?php foreach ($group->projects as $projects): ?>
	    <tr>
	        <td><strong><?= $this->Html->link(__(h($projects->name)), ['controller' => 'Projects', 'action' => 'view', $projects->id]) ?></strong></td>
	        <td><?= h($projects->table_name) ?></td>
	        <td><?= h($projects->return_limit) ?></td>
	        <td><?= h($projects->created) ?></td>
	        <td><?= h($projects->startdate) ?></td>
	        <td><?= h($projects->enddate) ?></td>
	
	
	        <td class="actions">
				<?= $this->Html->link(__('<span>Edit</span>'), ['controller' => 'Projects', 'action' => 'edit', $projects->id], array('class' => 'ion-edit', 'escape' => FALSE)) ?>
				<?= $this->Form->postLink(__('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="icon" d="M128 405.429C128 428.846 147.198 448 170.667 448h170.667C364.802 448 384 428.846 384 405.429V160H128v245.429zM416 96h-80l-26.785-32H202.786L176 96H96v32h320V96z"/></svg><span>Delete</span>'), ['controller' => 'Projects', 'action' => 'delete', $projects->id], array('class' => 'ion-close-circled', 'escape' => FALSE, 'confirm' => __('Are you sure you want to delete #{0}?', $projects->id))) ?>
	        </td>
	    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>



<h3><?= __('Group Users') ?></h3>
<?php if (!empty($group->users)): ?>
<table cellpadding="0" cellspacing="0">
	<thead>
	    <tr>
            <th><?= __('Email') ?></th>
            <th><?= __('User Type Id') ?></th>
            <th><?= __('Created') ?></th>
            <th><?= __('Modified') ?></th>
	        <th class="actions"><?= __('Actions') ?></th>
	    </tr>
	</thead>
	<tbody>
	<?php foreach ($group->users as $users): ?>
	    <tr>
	        <td><strong><?= $this->Html->link(__(h($users->email)), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?></strong></td>
            <td><?= h($users->user_type_id) ?></td>
	        <td><?= h($users->created) ?></td>
	        <td><?= h($users->modified) ?></td>
	        <td class="actions">
	            <?= $this->Html->link(__('<span>Edit</span>'), ['controller' => 'Users', 'action' => 'edit', $users->id], array('class' => 'ion-edit', 'escape' => FALSE)) ?>
	            <?= $this->Form->postLink(__('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path class="icon" d="M128 405.429C128 428.846 147.198 448 170.667 448h170.667C364.802 448 384 428.846 384 405.429V160H128v245.429zM416 96h-80l-26.785-32H202.786L176 96H96v32h320V96z"/></svg><span>Delete</span>'), ['controller' => 'Users', 'action' => 'delete', $users->id], array('class' => 'ion-close-circled', 'escape' => FALSE, 'confirm' => __('Are you sure you want to delete #{0}?', $users->id))) ?>
	        </td>
	    </tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>


<!--
<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Group'), ['action' => 'edit', $group->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Group'), ['action' => 'delete', $group->id], ['confirm' => __('Are you sure you want to delete # {0}?', $group->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Groups'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</div>

<div class="groups view large-10 medium-9 columns">
    <h2><?= h($group->name) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Name') ?></h6>
            <p><?= h($group->name) ?></p>
            <h6 class="subheader"><?= __('Contactname') ?></h6>
            <p><?= h($group->contactname) ?></p>
            <h6 class="subheader"><?= __('Contactphone') ?></h6>
            <p><?= h($group->contactphone) ?></p>
            <h6 class="subheader"><?= __('Contactemail') ?></h6>
            <p><?= h($group->contactemail) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($group->id) ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($group->created) ?></p>
            <h6 class="subheader"><?= __('Modified') ?></h6>
            <p><?= h($group->modified) ?></p>
        </div>
    </div>
</div>

<div class="related row">
    <div class="column large-12">
    <h4 class="subheader"><?= __('Related Projects') ?></h4>
    <?php if (!empty($group->projects)): ?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Client Id') ?></th>
            <th><?= __('Group Id') ?></th>
            <th><?= __('Name') ?></th>
            <th><?= __('Project Type Id') ?></th>
            <th><?= __('Table Name') ?></th>
            <th><?= __('Return Limit') ?></th>
            <th><?= __('Created') ?></th>
            <th><?= __('Updated') ?></th>
            <th><?= __('Startdate') ?></th>
            <th><?= __('Enddate') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($group->projects as $projects): ?>
        <tr>
            <td><?= h($projects->id) ?></td>
            <td><?= h($projects->client_id) ?></td>
            <td><?= h($projects->group_id) ?></td>
            <td><?= h($projects->name) ?></td>
            <td><?= h($projects->project_type_id) ?></td>
            <td><?= h($projects->table_name) ?></td>
            <td><?= h($projects->return_limit) ?></td>
            <td><?= h($projects->created) ?></td>
            <td><?= h($projects->updated) ?></td>
            <td><?= h($projects->startdate) ?></td>
            <td><?= h($projects->enddate) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Projects', 'action' => 'view', $projects->projectid]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Projects', 'action' => 'edit', $projects->projectid]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Projects', 'action' => 'delete', $projects->projectid], ['confirm' => __('Are you sure you want to delete # {0}?', $projects->projectid)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>


<div class="related row">
    <div class="column large-12">
    <h4 class="subheader"><?= __('Related Users') ?></h4>
    <?php if (!empty($group->users)): ?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?= __('Id') ?></th>
            <th><?= __('Group Id') ?></th>
            <th><?= __('User Type Id') ?></th>
            <th><?= __('Email') ?></th>
            <th><?= __('Password') ?></th>
            <th><?= __('Created') ?></th>
            <th><?= __('Modified') ?></th>
            <th><?= __('Active') ?></th>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($group->users as $users): ?>
        <tr>
            <td><?= h($users->id) ?></td>
            <td><?= h($users->group_id) ?></td>
            <td><?= h($users->user_type_id) ?></td>
            <td><?= h($users->email) ?></td>
            <td><?= h($users->password) ?></td>
            <td><?= h($users->created) ?></td>
            <td><?= h($users->modified) ?></td>
            <td><?= h($users->active) ?></td>

            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>

                <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>

                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>

            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
-->
