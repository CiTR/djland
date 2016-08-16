<?php

require_once('../headers/db_header.php');

//Fix Genre
$db->query(
'UPDATE shows SET primary_genre_tags = "Rock / Pop / Indie" WHERE primary_genre_tags = "Rock" or primary_genre_tags = "Pop" or primary_genre_tags = "Indie";
UPDATE shows SET primary_genre_tags = "Hip Hop / R&B / Soul" WHERE primary_genre_tags = "Hip Hop" or primary_genre_tags = "R&B" or primary_genre_tags = "Soul";
UPDATE shows SET primary_genre_tags = "Jazz / Classical" WHERE primary_genre_tags = "Jazz" or primary_genre_tags = "Classical";
UPDATE shows SET primary_genre_tags = "Punk / Hardcore / Metal" WHERE primary_genre_tags = "Punk" or primary_genre_tags = "Hardcore" or primary_genre_tags = "Metal";
UPDATE shows SET primary_genre_tags = "Roots / Blues / Folk" WHERE primary_genre_tags = "Roots" or primary_genre_tags = "Blues" or primary_genre_tags = "Folk";
UPDATE shows SET primary_genre_tags = "Talk" WHERE primary_genre_tags = "Spoken Word";');
