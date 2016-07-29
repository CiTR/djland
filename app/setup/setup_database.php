<html>
	<head>
        <link rel='stylesheet' href='../css/bootstrap.min.css'></script>
	</head>
	<body>
		<h1>Tables Created</h1>
		<table class='table-striped'>
		<?php
			require($_SERVER['DOCUMENT_ROOT'].'/headers/db_header.php');
			require($_SERVER['DOCUMENT_ROOT'].'/headers/run_sql.php');
			
			//foreach on the data_structures folder.
			$data_structures = scandir($_SERVER['DOCUMENT_ROOT'].'/setup/database_structures');

			foreach($data_structures as $key=>$data_structure){
				if(isSQL($data_structure)) echo "<tr><td>".$data_structure."</td>";
				
				$result = run_sql_file($data_strucure);
				echo "<td>".$success['success']."</td>";
				//run_sql_file($data_structure);
			}
		?>
		</table>
	</body>
</html>