<h2>Reports</h2>
<p class="back"><?= $this->Html->link(__('All Users'), ['action' => 'index'], array('class' => 'ion-chevron-left')) ?></p>

<form id="edit">
	<p>
		<label>Start Time  IST</label>
		<input size="15" name="start" type="text" value="<?php if(isset($_REQUEST['start'])) { echo $_REQUEST['start']; } else { echo date("Y/m/j"); ?> 00:00:00 <?php } ?>" id="datetimepicker_start"/> to <input size="15" name="end" type="text" value="<?php if(isset($_REQUEST['end'])) { echo $_REQUEST['end']; } else { echo date("Y/m/j"); ?> 23:59:59 <?php } ?>" id="datetimepicker_end"/>
	</p>
	
	<p>
		<label>Break out by:</label>
		<select name="period">
			<option value="hour">Hour
			<option value="day">Day	
		</select>
	</p>
	
	<p>
		<label>Projects</label>
		<select name="project_id">
		
		<?php foreach ($userprojects as $projkey => $project) { ?>
				     <option value="<?=$project->project_id?$project->project_id:$project->id?>" <?php if ($project->id == $_REQUEST['project_id']) { ?>selected<?php }?>> <?=$project->name?$project->name:$project->project['name'];?>	
			<?php }?>
		</select>
	</p>
	
	<p><input type="submit" name="report" value="View Report" class="btn"/></p>
</form>


<?php if (isset($reportData)) { 
	
	$dates = array();
	$totalimages = array();
	
	foreach ($reportData as $date=>$numimages) {
		array_push($dates, $date);
		array_push($totalimages, $numimages);
	}
	
?>

	<div style="width: 50%">
		<canvas id="canvas" height="450" width="600"></canvas>
	</div>	

	<?= $this->Html->script('Chart.js')?>

	<script>
		var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var barChartData = {
			labels : ["<?php echo implode($dates,"\",\"")?>"],
			datasets : [
				{
					fillColor : "rgba(220,220,220,0.5)",
					strokeColor : "rgba(220,220,220,0.8)",
					highlightFill: "rgba(220,220,220,0.75)",
					highlightStroke: "rgba(220,220,220,1)",
					data : ["<?php echo implode($totalimages,"\",\"")?>"]
				}
			]		
		}
		window.onload = function(){
			var ctx = document.getElementById("canvas").getContext("2d");
			window.myBar = new Chart(ctx).Bar(barChartData, {
				responsive : true
			});
		}
	</script>
<?php } ?>