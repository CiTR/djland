
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
3. Run Ingest Podcast XML script <a href='../api/podcasting/ingest_xml.php' target='_blank'>Ingest XML files<a>
</p><p>	
4. Run Channel->Show connector <a href='../api/podcasting/connect-shows-with-channels.php' target='_blank'>Connect Shows to Channels</a>
</p><p>	
5. Manually connect the 10-15 remaining channels to shows.

Make sure you drop channels to reset autoincrement!
<pre>
UPDATE `podcast_channels` SET `show_id`='183' WHERE `id`='210';
UPDATE `podcast_channels` SET `show_id`='154' WHERE `id`='58';
UPDATE `podcast_channels` SET `show_id`='284' WHERE `id`='62';
UPDATE `podcast_channels` SET `show_id`='284' WHERE `id`='88';
UPDATE `podcast_channels` SET `show_id`='343' WHERE `id`='99';
UPDATE `podcast_channels` SET `show_id`='294' WHERE `id`='161';
UPDATE `podcast_channels` SET `show_id`='14' WHERE `id`='193';
UPDATE `podcast_channels` SET `show_id`='233' WHERE `id`='179';
</pre>

</p><p>	
6. (Ensure PHP max script execution > 3 minutes). Connect playsheets to podcast episodes <a href='../api/podcasting/connect-playsheets-with-episodes.php' target='_blank'>Connect Podcasts to Playsheets</a>
</p><p>	
7. Run XML writer <a href='../api2/public/channels/write_xml' target='_blank'>Write new XML files</a>
</p><p>	

8. Run Second set of Database migrations <a href='db_migrations_2.php' target='_blank'>Fix Tables</a>
	<ul>
		<li>Deletes Songs Table
	 	<li>Moves podcast title,summary,description to playsheet
	</ul>
</p>