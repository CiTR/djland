<?php


session_start();

require("headers/security_header.php");

require("headers/function_header.php");

require("headers/menu_header.php");


printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=style.css type=text/css>");

?>

<title>DJLAND | Charting</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
  
 
</head>
<body>

<?php

		print_menu();
       
       
       
       
       
        
        $START_DATE = "2012/01/01";
        $END_DATE = "2012/02/08";
        $two_fridays_ago = date("l, F j, Y",strtotime('-1 week last friday'));
        $last_thursday = date("l, F j, Y",strtotime('last thursday'));
        $START_DATE = date("Y/n/j",strtotime($two_fridays_ago));
        $END_DATE = date("Y/n/j",strtotime($last_thursday));
        $weekday = date("w");
        $month = date("m");
        $day = date("d");
       //	echo "<center>";
        echo "<table>";
//        echo "<tr><td colspan=3 class=\"rawdata\"><br/>formatted song view with counts - from ".$START_DATE." to ".$END_DATE.".  Showing top ".$NUM_DISPLAYED." results. <br/><br/>";
         echo "<tr><td><span><br/>Album view by show weekday - from ".$two_fridays_ago." to ".$last_thursday." <br/>
         Dark Blue rows are playlisted.<br/>";
        echo "</span></td></tr>";
        echo "<tr>";
		echo 
		"<td><span class='chartbanner'><span class='chartSong'>Song</span>
		<span class='chartArtist'>Artist</span> 
        <span class='chartAlbum'>Album</span>
        <span class='chartShow'>Show Name</span>
        <span class='chartDate'>Date</span>
        <span class='chartCC'>CC</span></span>
        </td></tr>
        <tr> <td><br/></td> </tr>";
        
        
           
        

        
        $resultSongs = mysqli_query($db,"SELECT playitems.song_id
        , songs.id
        , songs.artist
        , songs.song
        , count(songs.id) AS Plays
        , songs.title
      
        FROM
        playitems
        LEFT OUTER JOIN songs
        ON playitems.song_id = songs.id
        WHERE
        playitems.show_date >= '".$START_DATE."'
        AND playitems.show_date <= '".$END_DATE."'
        GROUP BY
        songs.id
        ORDER BY 
        plays desc");
        
        
        $resultAlbums = mysqli_query($db,"SELECT playitems.show_date
        , songs.title
        , songs.artist
        , shows.name AS \"Show Name\"
        , playitems.is_playlist
        , songs.song
		, playitems.is_canadian
        FROM
        songs
        INNER JOIN playitems
        ON songs.id = playitems.song_id
        INNER JOIN shows
        ON playitems.show_id = shows.id
        WHERE
        playitems.show_date >= '".$START_DATE."'
        AND playitems.show_date <= '".$END_DATE."' "); 
    
    
        $counter = 0;
        /*if it is from a playlist, give it a dark blue background for the row*/
        while(($row = mysqli_fetch_row($resultAlbums))){
            if($row[4]==true){
                echo "<tr class=\"playlisted\">";
            } else {
                
                echo "<tr>";   
            }
        /*if nothing was entered, it will enter a string*/
        /*===change string here===*/
        $no_info="--";
        /* SONG */
        $row[5]=trim($row[5]);
        if(empty($row[5]))
        { echo "<td><span class='chartSong'>".$no_info."</span>";}
        else
        { echo "<td><span class='chartSong'>".$row[5]."</span>";}
        /* ARTIST */
        $row[2]=trim($row[2]);
        if(empty($row[2]))
        { echo "<span class='chartArtist'>".$no_info."</span>";}
        else
        { echo "<span class='chartArtist'>".$row[2]."</span>";}
        /* ALBUM */
        $row[1]=trim($row[1]);
        if(empty($row[1]))
        { echo "<span class='chartAlbum'>".$no_info."</span>";}
        else
        { echo "<span class='chartAlbum'>".$row[1]."</span>";}
        /* SHOW */
        $row[3]=trim($row[3]);
        if(empty($row[3]))
        { echo "<span class='chartShow'>".$no_info."</span>";}
        else
        { echo "<span class='chartShow'>".$row[3]."</span>";}
        /* DATE */
        $row[0]=trim($row[0]);
        if(empty($row[0]))
        { echo "<span class='chartDate'>".$no_info."</span>";}
        else
        { echo "<span class='chartDate'>".$row[0]."</span>";}
        
        /* CANCON */
        if($row[6]==true)
        { echo "<span class='chartCC'><img src='images/CAN.png'></span>";}
     
        
        
        echo"</td></tr>";
            $counter++;
        }
    echo "</table></body>";
    
?>