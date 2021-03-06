<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Users Projects'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="usersProjects form large-10 medium-9 columns">
    <?= $this->Form->create($usersProject) ?>
    <fieldset>
        <legend><?= __('Add Users Project') ?></legend>
        <?php
            echo $this->Form->input('id');
            echo $this->Form->input('user_id');
            echo $this->Form->input('project_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
