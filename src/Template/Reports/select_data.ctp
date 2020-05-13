<html>
<body>
<h1> OCR False Positive Report</h1>
<br>
<?= $this->Form->create(null,['type'=>'get']) ?>
<?php /*$this->Form->control('search') */?>
<?= $this->Form->input('date', ['class' => 'datepicker-input', 'type' => 'text', 'format' => 'Y-m-d', 'default' => date('Y-m-d'), 'value' => !empty($item->date) ? $item->date->format('Y-m-d') : date('Y-m-d')]); ?>
<button>Search</button>
<?= $this->Form->end()?>

<?php 
if($data){
  ?>
  <table>
  <thead>
 
    <tr>
    <th>Modified Date</th>
    <th>Img ID</th>
    <th>Img URL</th>
    <th>Profane</th>
    <th>Verdict Applied</th>
    <th>Moderator ID</th>
    </tr>
  </thead>
  <tbody>
  <?php
  //print_r($data);
    foreach($data as $res){
      ?>
      <tr>
      <td><?= $res[4]?></td> 
      <td><?= $res[0]?></td>
      <td> 
      <a href="<?= $res[1] ?>" target="_blank"><?= $res[1] ?> </a>
      </td>
      <td><?= $res[2]?></td>
      <td><?= $res[3]?></td>         
      <td><?= $res[5]?></td> 
  </tr>
<?php
    
    }
    ?>
    </tbody>
    </table>
<?php
}

?>
</body>
</html>

