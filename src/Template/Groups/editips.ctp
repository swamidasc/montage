<h2><?= __('Edit Access IPs') ?></h2>


<?= $this->Form->create($group, array('id' => 'editips')) ?>
    <?php
        echo $this->Form->input('allowedips', array('label' => 'Allowed IPs'));        
    ?>
    <?= $this->Form->button(__('Update'), array('class' => 'btn')) ?>
<?= $this->Form->end() ?>