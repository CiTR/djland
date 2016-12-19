
<h4>Migration Steps.<h4>
<p>
1. Run First set of Database migrations <a href='db_migrations.php' target='_blank'>Update Table Schema</a>
	<ul>
		<li>Adds Foreign Keys
		<li>Adds Missing Tables
	</ul>
</p><p>	
2. Place up to date burli XML files into burli-xml folder
</p><p>	
3. Run Ingest Podcast XML script <a href='../api/podcasting/parse_xmls.php' target='_blank'>Ingest XML files<a>
</p><p>	
6. (Ensure PHP max script execution > 3 minutes). Connect playsheets to podcast episodes <a href='../api/podcasting/connect-playsheets-with-episodes.php' target='_blank'>Connect Podcasts to Playsheets</a>
</p><p>	
7. Run Second set of Database migrations <a href='post_parse_migration.php' target='_blank'>Fix Tables</a>
	<ul>
		<li>Deletes Songs Table
	 	<li>Moves podcast title,summary,description to playsheet
	</ul>
</p>
<p>
8. Run XML writer <a href='../api2/public/channels/write_xml' target='_blank'>Write new XML files</a>
</p>	