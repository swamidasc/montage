<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $projectStatus->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $projectStatus->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Project Statuses'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="projectStatuses form large-10 medium-9 columns">
    <?= $this->Form->create($projectStatus) ?>
    <fieldset>
        <legend><?= __('Edit Project Status') ?></legend>
        <?php
            echo $this->Form->input('projectid');
            echo $this->Form->input('name');
            echo $this->Form->input('statval');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
