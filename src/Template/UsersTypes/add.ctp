<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Users Types'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="usersTypes form large-10 medium-9 columns">
    <?= $this->Form->create($usersType) ?>
    <fieldset>
        <legend><?= __('Add Users Type') ?></legend>
        <?php
            echo $this->Form->input('typename');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
