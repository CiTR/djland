
<h1>Tables Created</h1>
<table class='table-striped'>
<?php
	require(dirname($_SERVER['DOCUMENT_ROOT'])."/config.php");
	require($_SERVER['DOCUMENT_ROOT'].'/headers/run_sql.php');

	//foreach on the data_structures folder.
	$data_structure_path = dirname($_SERVER['DOCUMENT_ROOT']).'/setup/database_structures';
	$data_structures = scandir($data_structure_path);

	foreach($data_structures as $key=>$data_structure){
	if(isSQL($data_structure)) echo "<tr><td>".$data_structure."</td>";
	 	$result = run_sql_file($data_structure_path."/".$data_structure);
		echo "<td>".print_r($result)."</td>";
	}
?>
</table>
