<?php
$key = isset($key) ? $key : '<%= key %>';
?>
<tr>
    <td>
        <?php echo $this->Form->text("criterias.{$key}.name"); ?>
    </td>   
    <td>
        <?php echo $this->Form->text("criterias.{$key}.statval"); ?>
    </td>
    <td class="actions">
        <a href="#" class="remove">Remove Status</a>
    </td>
</tr>