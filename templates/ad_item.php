<?php
	//This file is used with a POST request. It is not an angular style template
	
	if(isset($_POST['num']) && isset($_POST['index'])){
		$type = $_POST['type'];
		$time = $_POST['time'];
		$index = $_POST['index'];
		$num = $_POST['num'];
	}else{
		http_response_code(400);
		return "Did not pass required values.";
	}
?>

<tr  id='<?php echo 'show_'.$index.'_'.$num; ?>' >
				<td>
					<input name='show[<?php echo 'show_'.$index.'_'.$num; ?>][time]' value="<?php echo $time; ?>" class='ad_time'>
					<input name='show[<?php echo 'show_'.$index.'_'.$num; ?>][time_block]' value='<?php echo $show['start_unix']; ?>' class='invisible'>
					<input name='show[<?php echo 'show_'.$index.'_'.$num; ?>][num]' value='<?php echo $num; ?>' class='invisible'>
					<input name='show[<?php echo 'show_'.$index.'_'.$num; ?>][id]' value='' class='invisible'>

				</td>
				<td class='type'>
					<select class='type_select' name='show[<?php echo 'show_'.$index.'_'.$num; ?>][type]' value="<?php echo $type; ?>">
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
						<input name='show[<?php echo 'show_'.$index.'_'.$num; ?>][name]' class='fullinput name' value="<?php echo $ad['name']; ?>" />
					<?php else: ?>
					<select class='name' name='show[<?php echo 'show_'.$index.'_'.$num; ?>][name]' value="<?php echo $ad['name']; ?>">

					</select>
					<?php endif; ?>
					
				</td>
			</tr>