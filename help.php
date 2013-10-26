<?php
session_start();
require("headers/security_header.php");
require("headers/function_header.php");
require("headers/menu_header.php");
require("headers/socan_header.php");
$SOCAN_FLAG;



printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=citr.css type=text/css>");
print("<title>DJland help</title>");
print("<script src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>");
print("<link rel='stylesheet' href='http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css' />");
print("  <script src='http://code.jquery.com/ui/1.10.2/jquery-ui.js'></script>");
print("<script type='text/javascript' src='./js/QA.js'></script>");
print("<script src='http://malsup.github.com/jquery.form.js'></script> ");
print("</head>");

//Data Setup
print_menu();
$SOCAN_FLAG=socanCheck($db);


//separate classifications( ex. show editing, ad editing, playsheet, sam)

//Contains Questions for Showlist Editing, and the text they contain
$showlist = array(
"title" =>array("Showlist Editing"),
"question"=>array("How do I add a show?" , 
"How do I make a show that runs every other week?") , 
"answer"=>array("You can add a show by clicking on the 'shows' tab in the navigation pane etc.",
"in the add show form, look in the 'Times' section (it will tell you what week this is) and select either week 1 or week 2.") 
);
$ads = array(
"title" =>array("Ad Scheduling"), 
"question"=>array("How do I add an ad?","How can I figure out if programmers are playing their ads?" ) , 
"answer"=>array("You can add an ad by clicking on the 'shows' tab in the navigation pane etc. and the selecting an ad for each slot in that show, or specifying no ad. Then click the save button (make sure it goes back to the normal colour before leaving the page <strong>OR IT WILL NOT SAVE</strong>.", 
"Click on 'Ad Report', and select a date range. It will be sorted by show, and the left box contains what the programmer sees (if there is a check, they have said they've played it in their show). The box to the right shows what SAM logged for that period of time. Make sure to check the times played in SAM to see if it is a duplicate play.") 
);
$playsheet = array(
"title" =>array("Playsheet"), 
"question"=>array("I am a spoken word show, what do I do since I play no songs?",
"How do I add a row?",
"I can't submit my playsheet, why?",
"What do I do with the ad section?",
"If I played a song in SAM, can I import it directly to my playsheet along with artist, album, song info?",
"I was told to add a podcast marker to help me find my show's audio. Where is this?"), 
"answer"=>array("Fill in the spoken word section. Approximate how long you talked for (using the hour and minute drop downs), then delete the empty rows (using the '-' button) to be able to submit.<br><img src='images/SPOKENWORD.png'>", 
"Click the plus button located to the far right, or 'Add five rows' to add five at a time",
"You probably haven't filled out all the fields, make sure you have filled out Song, Artist, and Album. If you have an empty row, delete it. If you haven't found all the info to fill in (ie. album) you can save it as a draft and come back to it.",
"Play your scheduled ads around the time it suggests, and once you have played it check it off. <strong>We log the ads that are played, so this is important.</strong><br><img src='images/CheckAds.png'>",
"Yes, you can add SAM plays one at a time by clicking the SAM plays button in the top right, the '+' beside the track you want. You can also import all the songs from a period of time with the 'SAM period' button.<br><img src='images/SAMinPLAYSHEET.png'> ",
"Click the 'Add Time Marker' button<br><img src='images/ADDINGPODCAST.png'>")
);
$SAM  = array(
"title" =>array("SAM"), 
"question"=>array(
"I've just sat down and SAM looks different, things are missing!",
"Why is there no sound going on air?",
"How do I play a track?",
"Everything I play sounds really weird, what is wrong?",
"After a song finishes, nothing happens. It won't play the next song automatically.",
"I've loaded all of my songs into the queue, and it keeps playing a random son instead, what do I do?" ) , 
"answer"=>array(
"Click on layout (found in the top menu bar), then hover over load, and click 'load default'. It should now go back to normal.<br> <img src='images/LAYOUT.png'>",
"Check the faders on the board for SAM A and SAM B are up and turned on, also make sure that 'Air' is lighter on Deck A, and 'Cue' is lighter on Deck B. If there's still no sound, check the volume faders within SAM.<br><img src='images/AIRandCUE.png'>", 
"Drag it to the queue first, then to a deck and hit play, to play songs from the queue. In the Auto DJ mode, once a song from the queue is playing and comes to an end, the next song from the queue will load automatically. Once the queue is empty, it will continue playing songs from our library in a special sequence ensuring we have no dead air. <br><img src='images/ADDtoPLAYLIST.png'><br><img src='images/PLAYLISTtoDECK.png'>",
"Most likely someone has changed the 'pitch' and 'tempo' settings. This can be found to the right of the decks. Ensure they look like the image below (click on P to change pitch, T to change tempo).<br> <img src='images/PITCHandTEMPO.png'>",
"Click on the triangle in the top menu, and select 'AutoDJ'. <br><img src='images/AutoDJ.png'>",
"Drag the first song of your queue to the deck that isn't playing anything, and it will then play from your queue from now on!"
)
);
$editLibrary = array(
"title" =>array("Editing the Library"), 
"question"=>array("How do I add a song?","How do I edit a song?","There are lots of duplicates, how do I remove them?" ) , 
"answer"=>array("Click update library, and enter the information", "Find an entry through the library, click on the Catalogue number to edit, change the info and hit update<br><img src='images/EDITSONG.png'>","Click on 'Library' and under the search options, there is a 'duplicate finder'. Click delete on an album that is a duplicate to remove if from the system.") 
);
$memberAdd = array(
"title" =>array("User Management"), 
"question"=>array("How do I add a user?","How do I edit a user's information, or disable a user's account?","How do I change a user's password?" ) , 
"answer"=>array("Click on the 'Users' tab, and click 'add new user' at the top", "Find a user from the drop down list, click 'edit' at under the list, change status to disabled or enabled, and finally click 'edit user' to save the changes.","You will find the password change input fields within the edit user area.") 
);
$reporting = array(
"title" =>array("CRTC Reports"), 
"question"=>array("How do I create a report?", 
"What is the easiest way to find out if a show is meeting it's CanCon requirements?") , 
"answer"=>array("Click on the 'Report2' tab, select a start and an end time (if it is a socan, make sure the broadcast day start and end are correctly set).",
"Click on the 'Report' tab, select a time period to check over, and a show. Hit generate report. Any requirement highlighted in orange has not been met.") 
);
$report = array(
"title" =>array("Show Reports"),
"question"=>array("How do I know if I am making my CanCon requirements?"),
"answer"=>array("Click on the 'Report' tab at the top of the page. Find your show, select a date range to check. Click 'Generate Report' to view the report.")
); 
$socan = array(
"title" =>array("SOCAN Period Help"),
"question"=>array("How do I know the time I played a song?","What do the CUE and END buttons do?","What else is different in SOCAN?"),
"answer"=>array("You can either play the song in SAM, and the use the SAM tool in the playsheet to add your songs (it will pull the time played and duration for you!) or use the CUE and END buttons to cue the start of your song, and end of your song (it will calculate the duration for you)","CUE and END buttons are in case you did not play a track through SAM, it helps you estimate both the start time (when you hit cue) and the duration (calculated for you when you hit end)","You have to check the extra fields 'background' and 'theme' if you played a song as either of these.")
); 



$data = array($playsheet, $SAM, $report);
if($SOCAN_FLAG)
{
array_push( $data, $socan);
}
if(is_member("editlibrary")){
array_push( $data, $editLibrary );
}
if(is_member("addshow")){
array_push($data, $showlist);
array_push($data, $reporting);
array_push($data, $ads);
}
if(is_member("member") && get_username() != "citrdjs") {
array_push($data, $memberAdd);
}



// echo $showlistEditing[question][0];
// echo $showlistEditing[answer][0];
$numObjects = sizeOf($data);




print("<body>");
print("<div id='wrapper'>");

print("<h1><center>DJLand Help</center></h1>");
echo "<center>(Click on a topic to start)</center>";
for($i=0;$i<$numObjects;$i++){
$numObjectsInside = sizeOf($data[$i]['question']);

echo "<div class=QAcontainer id=QAcontainer".$i."><img id='QAicon".$i."' class='QAicon collapsed' src='images/collapsed.png'>".$data[$i][title][0]."</div>";

for($j=0;$j<$numObjectsInside;$j++){
echo "<div class=QAelement id=QAelement".$i." name=element".$j." style='display:none;'>";
echo "<div class=QAquestion id=QAquestion".$i." name=QAquestion".$j."><img id='QAicon".$i."' name='QAicon".$j."' class='QAicon collapsed' src='images/collapsed.png' >Q: ".$data[$i][question][$j]."</div>";
echo "<div class=QAanswer id=QAanswer".$i." name=QAanswer".$j." style='display:none;'>A: ".$data[$i][answer][$j]."</div>";
echo "</div>";
}
}
echo $SOCAN_FLAG;

print("<p style='position:relative; bottom:10px; text-align:center;'>
If you would like to see something added to this page, contact 
<a href='mailto:TechnicalAssistant@citr.ca'> the Technical Assistant. 
</a> 
</p>
");



print("</div>");
print("</body>");

print("</html>");
?>
