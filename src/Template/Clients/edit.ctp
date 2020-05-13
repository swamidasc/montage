<h2>Edit Client</h2>
<p class="back"><?= $this->Html->link(__('All Clients'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>

<?= $this->Form->create($client, array('id' => 'edit')) ?>
    <?php
        echo $this->Form->input('companyname', array('label' => 'Company Name'));
        echo $this->Form->input('contactname', array('label' => 'Contact Name'));
        echo $this->Form->input('contactphone', array('label' => 'Contact Phone'));
        echo $this->Form->input('contactemail', array('label' => 'Contact Email'));
    ?>
    <?= $this->Form->button(__('Update'), array('class' => 'btn')) ?>
	<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $client->id], array('class' => 'btn grey delete', 'confirm' => __('Are you sure you want to delete #{0}?', $client->id))) ?>	
<?= $this->Form->end() ?>