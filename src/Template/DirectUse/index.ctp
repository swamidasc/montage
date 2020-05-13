<?= $this->Form->create('tags',['type'=>'get'])?>
<?= $this->Form->create('start_date')?>
<?= $this->Form->create('end_date')?>
<button>Search<button>
<?= $this->Form->end();?>

<?= $this->Flash->render(); ?>
<?= $this->fetch('content'); ?>