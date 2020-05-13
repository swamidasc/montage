<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Projects Labels'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="projectsLabels form large-10 medium-9 columns">
    <?= $this->Form->create($projectsLabel) ?>
    <fieldset>
        <legend><?= __('Add Projects Label') ?></legend>
        <?php
            echo $this->Form->input('status_id');
            echo $this->Form->input('name');
            echo $this->Form->input('multiple');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
