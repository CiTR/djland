<?php
	//This file is used with a POST request. It is not an angular style template
	
	if( isset($_POST['playitem']) && isset($_POST['index']) ){
		$playitem = $_POST['playitem'];
		$index = $_POST['index'];
		$socan = isset($_POST['socan']) ? $_POST['socan'] : false;
		$crtc = isset($_POST['crtc']) ? $_POST['crtc'] : '30';
		$lang = isset($_POST['lang']) ? $_POST['lang'] : '20';  
	}else{
		http_response_code(400);
		echo "Playitem object required.";
		return false;
	}
?>

<tr class='playitem' id="playitem_<?php echo $index; ?>">
	<td><input name='id' class='hidden' value='<?php echo $index +"1"; ?>'></input></td>
	<td><input name='artist' class='required' onchange='update()' value='<?php echo isset($playitem["artist"]) ? $playitem["artist"]:""; ?>'></input></td>
	<td><input name='song' class='required' onchange='update()' value='<?php echo isset($playitem["song"]) ? $playitem["song"]:""; ?>'></input></td>
	<td><input name='album' class='required'onchange='update()' value='<?php echo isset($playitem["album"]) ? $playitem["album"]:""; ?>'></input></td>
<?php if($socan==true): ?>
	<td><input name='composer' class='required' onchange='update()' value='<?php echo isset($playitem["composer"]) ? $playitem["composer"]:""; ?>'></input></td>
	<td><button type='button'  class='cue'>CUE</button type='button' ></td>
	<select name='song_length_minute' class='required smalltext'>
	<?php for($i = 0; $i < 60; $i++): ?>
		<option value='<?php echo $i; ?>' <?php echo isset($playitem['length_minute']) && $i == $playitem['length_minute'] ? 'selected':''; ?> ><?php echo str_pad($i,2,'0',STR_PAD_LEFT); ?></option>
	<?php endfor; ?>
	</select>:
    <select name='song_length_second' class='required smalltext'>
	<?php for($i = 0; $i < 60; $i++): ?>
		<option value='<?php echo $i; ?>' <?php echo isset($playitem['length_second']) && $i == $playitem['length_second'] ? 'selected':''; ?> ><?php echo str_pad($i,2,'0',STR_PAD_LEFT); ?></option>
	<?php endfor; ?>
    </select>
    <button type='button'  class='end_cue'>END</button type='button' ></td>
<?php endif; ?>
	<td><button type='button'  name='is_playlist' class="box playlist pad-top <?php echo $playitem['is_playlist']==1? 'filled' : ''; ?>"></button type='button' ></td>
	<td><button type='button'  name='is_canadian' class="box canadian pad-top <?php echo $playitem['is_canadian']==1? 'filled' : ''; ?>"></button type='button' ></td>
	<td><button type='button'  name='is_fem' class="box femcon pad-top <?php echo $playitem['is_fem']==1? 'filled' : ''; ?>"></button type='button' ></td>
	<td><button type='button'  name='is_inst' class="box instrumental pad-top <?php echo $playitem['is_inst']==1? 'filled' : ''; ?>"></button type='button' ></td>
	<td><button type='button'  name='is_part' class="box partial pad-top <?php echo $playitem['is_part']==1? 'filled' : ''; ?>"></button type='button' ></td>
	<td><button type='button'  name='is_hit' class="box hit pad-top <?php echo $playitem['is_hit']==1? 'filled' : ''; ?>"></button type='button' ></td>
<?php if($socan==true): ?>
	<td><button type='button'  name='is_background' class="box background pad-top <?php echo $playitem['is_background']==1? 'filled' : ''; ?>"></button type='button' ></td>
	<td><button type='button'  name='is_theme' class="box theme pad-top <?php echo $playitem['is_theme']==1? 'filled' : ''; ?>"></button type='button' ></td>
<?php endif; ?>
	<td><select name='crtc_category' class='smalltext'>
		<option value='20' <?php echo isset($playitem['crtc_category']) && $playitem['crtc_category'] == '20' ? 'selected':''; ?>>20</option></td>
		<option value='30' <?php echo isset($playitem['crtc_category']) && $playitem['crtc_category'] =='30' ? 'selected':''; ?>>30</option></td>
	</select>
	<td><input name='lang' class="smalltext" value='<?php echo isset($playitem['lang']) ? $playitem['lang'] : ""; ?>'></input></td>
	<td><button type='button'  class='add'><img src='images/collapsed.png'></button type='button' ></td>
	<td><button type='button'  class='remove'><img src='images/expanded.png'></button type='button' ></td>
	<td><div class='hand side-padded'>&#x21D5;</div></td>
</tr>


