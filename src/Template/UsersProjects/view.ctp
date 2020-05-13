<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Users Project'), ['action' => 'edit', $usersProject->userid]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users Project'), ['action' => 'delete', $usersProject->userid], ['confirm' => __('Are you sure you want to delete # {0}?', $usersProject->userid)]) ?> </li>
        <li><?= $this->Html->link(__('List Users Projects'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Project'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="usersProjects view large-10 medium-9 columns">
    <h2><?= h($usersProject->userid) ?></h2>
    <div class="row">
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($usersProject->id) ?></p>
            <h6 class="subheader"><?= __('User Id') ?></h6>
            <p><?= $this->Number->format($usersProject->user_id) ?></p>
            <h6 class="subheader"><?= __('Project Id') ?></h6>
            <p><?= $this->Number->format($usersProject->project_id) ?></p>
        </div>
    </div>
</div>
