<h2><?=$training==1?"Training ":""?> Quality Control Tests</h2>
<?php if ($role < 2) { ?>

<?php } ?>
<table id="projects" cellpadding="0" cellspacing="0">
    <tbody>
    <p><a href="/qualitytests/upload">Upload an Image</a></p>
    
    
    <form id="UCSetForm" method="POST">
     <tr>
     	<th>Project: </th>
     	<th><?php
     	
     		if (isset($_REQUEST['projectid'])) {
	     		$projdefault = $_REQUEST['projectid'];
     		} else {
	     		$projdefault = 0;
     		}
     	
			echo $this->Form->input(
			'projectid', 
			[
			'type' => 'select',
			'multiple' => false,
			'options' => $projects, 
			'empty' => true,
			'label' => '',
			'onchange' => "reloadUsers(this);",
			'default' => $projdefault
			]);	     	
	     	?>
     	</th>    	
	 </tr>
	 <?php if (isset($usersprojects)) { ?>
	 <tr>
	 	<th>Moderator:</th>
	 	<th>
	 	
	 	<?php	 	
     		if (isset($_REQUEST['qcuserid'])) {
	     		$qcuserdefault = $_REQUEST['qcuserid'];
     		} else {
	     		$qcuserdefault = 0;
     		}	 		
	 	
			echo $this->Form->input(
			'qcuserid', 
			[
			'type' => 'select',
			'multiple' => false,
			'options' => $usersprojects, 
			'empty' => true,
			'label' => '',
			'default' => $qcuserdefault
			]);	   	
	 	?>
	
	 	<input type="hidden" id="tn" name="tn" value="<?=$tn[$projdefault]?>" />  	
	 	</th>
	 </tr>
	 </form>
	 <?php } ?>
     <tr>
     	<td align="center" colspan="2">
     	     	
     	<div class="checkQAImage">
	     		<form id="imageCheck">
	     			<div id="imageQAResults2"></div>
					<p><input id="imgurl" type="text" placeholder="Paste a URL to an <?=$ptype?>..." /></p>
					<input id="sendmoderate" type="submit" value="Submit <?=ucfirst($ptype)?>" class="btn"/>
				</form>
				<div id="imageQAProcessing">
					<img src="/webroot/img/loading.gif" />
					<p>
						<strong>The <?=$ptype?> has been submitted to the queue, please wait while it is moderated.</strong>
				</p>
				</div>
				<div id="imageQAResults">
					<center>
					<div id="QAloadImage"></div>
					<?php foreach ($labels as $labelkey => $label) {?>
						<h3 id="imageStatus<?=$labelkey?>" style="display:none" >The <?=$ptype?> was moderated as: <?=$label?></h3>	
					<?php } ?>
					
					
					
					
					<p><a target="_blank" href="#" id="QAReportLink">See who moderated the <?=$ptype?></a></p>
					<a href="javascript:void(0);" id="tryAgain" class="btn grey">Try Again</a>
					</center>
				</div>
	     </div>	
     	</td>
	 </tr>
    </tbody>
 </table>   
 </form>
 
<script>
function reloadUsers(){
    document.getElementById("UCSetForm").submit();
}
</script>
    	
			
    
    
