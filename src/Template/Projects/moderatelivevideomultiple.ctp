<?php
	if (isset($this->request->params['pass'][1]) &&  $this->request->params['pass'][1] == "escalated") {
		$escalated = 1;
	} else {
		$escalated = 0;
	}	

    if ($tag_project == 1) {
	    $tagged = "tags";
    }   
?>
<input type="hidden" name="tn" id="tn" value="<?=$project->table_name?>" /> 
<input type="hidden" name="endpoint" id="endpoint" value="<?=$project->endpoint?>" /> 
<input type="hidden" name="modid" id="modid" value="<?=$_SESSION['Auth']['User']['id']?>" /> 
<input type="hidden" name="slideid" id="slideid" value="0" /> 
<?php if ($confirm) { ?>
<input type="hidden" name="confirm[]" id="confirm" value="<?=implode(',',array_keys($confirm))?>" />
<input type="hidden" name="confirmname[]" id="confirmname" value="<?=implode(',',$confirm)?>" />
<input type="hidden" name="confirmskip" id="confirmskip" value="0" />
<?php } ?>
<div id="overlayBG"></div>
<div id="overlayProfanityBG"></div>
<div id="modliveVid">
<iframe width="33%" height="900px" src="https://<?=$_SERVER["HTTP_HOST"]?>/projects/moderate/<?=$project->id?>?formultiple=1"></iframe>
<iframe width="33%" height="900px" src="https://<?=$_SERVER["HTTP_HOST"]?>/projects/moderate/<?=$project->id?>?formultiple=1&sleep=2"></iframe>
<iframe width="33%" height="900px" src="https://<?=$_SERVER["HTTP_HOST"]?>/projects/moderate/<?=$project->id?>?formultiple=1&sleep=4"></iframe>
</div>
