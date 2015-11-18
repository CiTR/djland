<?php
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
		<table class='table-condensed'>
			<tr >
				<td id='template_<?php echo $index ?>' >
					<input name="" value='<?php echo $show['start']; ?>'></input>
				</td>
				<td>
					<select id="template_ad_type_<?php echo $index ?>">
						<option value="announcement">Announcement</option>
						<option value='ad'>Ad</option>
						<option value='psa'>PSA</option>
						<option value='timely'>Timely PSA</option>
						<option value='ubc'>UBC PSA</option>
						<option value='community'>Community PSA</option>
						<option value='promo'>Show Promo</option>
						<option value='id'>Station ID</option>
					</select>
				</td>
			</tr>
		</table>
		<button id="insert_<?php echo $index; ?>" type='button'>Insert Ad</button>

		<table class='table-condensed'>
			<?php foreach($show['ads'] as $ad) : ?>
			<tr id='<?php echo 'show_'.$index.'_'.($ad['num']-1); ?>' >
				<td><input name='time' value="<?php echo $ad['time']; ?>"></td>
				<td class='type'>
					<select name='type' value="<?php echo $ad['type']; ?>">
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
				<td>
					<?php if($ad['type'] == 'announcement'): ?>
						<input class='fullinput name' value="<?php echo $ad['name']; ?>" />
					<?php else: ?>
					<select class='name' value="<?php echo $ad['name']; ?>">

					</select>
					<?php endif; ?>
					
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
</li>
