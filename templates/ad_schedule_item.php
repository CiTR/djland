<?php
	//This file is used with a POST request. It is not an angular style template
	
	if(isset($_POST['show']) && isset($_POST['index'])){
		$show = $_POST['show'];
		$index = $_POST['index'];
	}else{
		http_response_code(400);
		return "Showtime object required.";
	}
?>
<li id="show_<?php echo $index; ?>" data="<?php echo $show['start_unix']; ?>" >
	<h3 class='text-left'><?php echo $show['name']; ?></h3>
	<h4 class='text-left'><?php echo $show['date']; ?></h4>

	<div id="template_<?php echo $index; ?>">
		<FORM name='<?php echo $show['start_unix']; ?>' class='showtime'>
		<table class='table-condensed ads'>
			<?php foreach($show['ads'] as $ad) : ?>
			<tr  id='<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>' >
				<td>
					<input name='show[<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>][time]' value="<?php echo $ad['time']; ?>" class='ad_time'>
					<input name='show[<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>][time_block]' value='<?php echo $show['start_unix']; ?>' class='invisible'>
					<input name='show[<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>][num]' value='<?php echo $ad['num']; ?>' class='invisible'>
					<input name='show[<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>][id]' value='<?php echo isset($ad['id']) ?$ad['id']:''; ?>' class='invisible'>

				</td>
				<td class='type'>
					<select class='type_select' name='show[<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>][type]' value="<?php echo $ad['type']; ?>">
						<option <?php if($ad['type'] == "announcement") echo 'selected'; ?> value="announcement">Announcement</option>
						<option <?php if($ad['type'] == "ad") echo 'selected'; ?> value='ad'>Ad</option>
						<option <?php if($ad['type'] == "psa") echo 'selected'; ?> value='psa'>PSA</option>
						<option <?php if($ad['type'] == "timely") echo 'selected'; ?> value='timely'>Timely PSA</option>
						<option <?php if($ad['type'] == "ubc") echo 'selected'; ?> value='ubc'>UBC PSA</option>
						<option <?php if($ad['type'] == "community") echo 'selected'; ?> value='community'>Community PSA</option>
						<option <?php if($ad['type'] == "promo") echo 'selected'; ?> value='promo'>Show Promo</option>
						<option <?php if($ad['type'] == "id") echo 'selected'; ?> value='id'>Station ID</option>
					</select>
				</td>
				<td class='name'>
					<?php if($ad['type'] == 'announcement'): ?>
						<input name='show[<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>][name]' class='fullinput name' value="<?php echo $ad['name']; ?>" />
					<?php else: ?>
					<select class='name' name='show[<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>][name]' value="<?php echo $ad['name']; ?>">

					</select>
					<?php endif; ?>
					
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</FORM>
	</div>
</li>
