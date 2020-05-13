<h2>Add Client</h2>
<p class="back"><?= $this->Html->link(__('Cancel'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>

<?= $this->Form->create($client, array('id' => 'edit')) ?>
    <?php
        echo $this->Form->input('companyname', array('label' => 'Company Name'));
        echo $this->Form->input('contactname', array('label' => 'Contact Name'));
        echo $this->Form->input('contactphone', array('label' => 'Contact Phone'));
        echo $this->Form->input('contactemail', array('label' => 'Contact Email'));
    ?>
    <?= $this->Form->button(__('Add Client'), array('class' => 'btn')) ?>
<?= $this->Form->end() ?>