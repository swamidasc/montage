<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Projects Types'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="projectsTypes form large-10 medium-9 columns">
    <?= $this->Form->create($projectsType) ?>
    <fieldset>
        <legend><?= __('Add Projects Type') ?></legend>
        <?php
            echo $this->Form->input('name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
