<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Project Statuses'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="projectStatuses form large-10 medium-9 columns">
    <?= $this->Form->create($projectStatus) ?>
    <fieldset>
        <legend><?= __('Add Project Status') ?></legend>
        <?php
            echo $this->Form->input('projectid');
            echo $this->Form->input('name');
            echo $this->Form->input('statval');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
