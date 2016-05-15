<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

if( permission_level() < $djland_permission_levels['dj']['level']){
	    header("Location: main.php");
	}
?>
    <html>
    <head>
        <meta name=ROBOTS content="NOINDEX, NOFOLLOW">
        <base href='shows.php'>
        <link rel="stylesheet" href="css/style.css" type="text/css">
        <title>DJ LAND | Shows</title>
        <?php
        print_menu();
        ?>
        <body class='wallpaper' ng-app="djland.editShow" >
        <div ng-controller="editShow as show">
            <script src='js/jquery-1.11.3.min.js'></script>
            <script src='js/constants.js'></script>
            <script src="js/angular.js"></script>
            <script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>
            <script src="js/shows/edit.js"></script>
            <script src="js/api.js"></script>
            <script src="js/utils.js"></script>
            <script>
                var member_id = "<?php echo $_SESSION['sv_id']; ?>";
                var username = "<?php echo $_SESSION['sv_username']; ?>";
            </script>
            <div class='text-center' ng-show='show.loading == true'><img class='rounded' width ='300' height='20' src='images/loading.gif'/></div>
            <div ng-hide="show.member_shows || show.loading == true" class='text-center'>You have no shows assigned to this account. Please ask a staff member to assign you to your show</div>
            <div id='wrapper' ng-show='show.info'>


               Select show to edit:
                <select ng-model="show.show_value" ng-change="show.updateShow()" >
                    <option ng-repeat="item in show.member_shows | orderBy:'name'" value="{{item.id}}">{{item.name}}</option>
                </select>
                <button type='button' ng-show='show.is_admin' ng-click='show.newShow()'>Create a New Show</button>
                <h4 class='text-left double-padded-top'> Show name </h4>
                <div ng-switch on='show.is_admin'>
                    <div ng-switch-when="true">
                        <input id='show_name' ng-change='show.shared.setShowName(show.info.name)' ng-model='show.info.name'/>
                    </div>
                    <div ng-switch-when="false">
                        <input readonly id='show_name' ng-model='show.info.name'/>
                    </div>
                </div>

                <div ng-show="show.is_admin">
                    <h4 class='text-left double-padded-top'>Is show active?</h4>
                    <input type='checkbox' ng-model="show.info.active" ng-true-value="1" ng-false-value="0"/>
                    <h4 class='text-left double-padded-top'>Member Owners</h4>
                    <ul id='member_access_list'>
                        <li ng-repeat='member in show.show_owners track by $index'>
                            {{member.firstname + " " + member.lastname}}
                            <button type='button' ng-click='show.removeOwner($index)'>Remove</button>
                        </li>
                    </ul>
                    <select id='member_access_select'>
                        <option ng-repeat="member in show.member_list | orderBy:'lastname'" value='{{member.id}}' >{{member.firstname +" "+ member.lastname}} </option>
                    </select>
                    <button ng-click='show.addOwner()' type='button'>Add</button>
                </div>
                <h4 class='text-left double-padded-top'>Show Host(s)</h4>
                <input class='wideinput' ng-model='show.info.host'>
                <h4 class='text-left double-padded-top'>Primary Genres</h4>
                <ul>
                    <li ng-repeat='genre in show.primary_genres track by $index'>
                        {{genre}}
                        <button type='button' ng-click='show.removePrimaryGenre($index)'>Remove</button>
                    </li>
                </ul>
                <select ng-model='show.primary_genre_select'>
                        <option ng-repeat="genre in show.genres track by $index | orderBy:'genre.toString()'" value='{{$index}}' >{{genre}} </option>
                </select>
                <button ng-click='show.addPrimaryGenre()' type='button'>Add Primary Genre</button>
                <h4 class='text-left double-padded-top'>Secondary Genres</h4>
                <ul>
                    <li ng-repeat='genre in show.secondary_genres track by $index'>
                        {{genre}}
                        <button type='button' ng-click='show.removeSecondaryGenre($index)'>Remove</button>
                    </li>
                </ul>
                <input placeholder="Secondary Genre Name" ng-model='show.secondary_genre_input'/>
                <button ng-click='show.addSecondaryGenre()' type='button'>Add Secondary Genre</button>
                <h4 class='text-left double-padded-top'>Show Alert</h4>
                <textarea class='alert' ng-model='show.info.alerts'></textarea>
                <h4 class='text-left double-padded-top'>Show Description</h4>
                <textarea class='description' ng-model='show.info.show_desc'></textarea>
                <h4 class='text-left double-padded-top'>Website</h4>
                <input class='wideinput' ng-model='show.info.website'>
                <h4 class='text-left double-padded-top'>Show Image</h4>
                <input readonly class='wideinput' id='show_image' ng-model='show.info.show_img'>
                <div class='double-padded-top' ng-controller="FileUploadCtrl">
                    <div  class="row">
                        <label for="fileToUpload">Either choose files, or drag files</label><br/>
                        <input type="file" ng-model-instant id="fileToUpload" multiple onchange="angular.element(this).scope().setFiles(this)" />
                    </div>
                    <div  id="dropbox" class="dropbox" ng-class="dropClass"><span>{{dropText}}</span></div>
                    <div ng-show="files.length">
                        <div ng-repeat="file in files.slice(0)">
                            <span>{{file.webkitRelativePath || file.name}}</span>
                            (<span ng-switch="file.size > 1024*1024">
                            <span ng-switch-when="true">{{file.size / 1024 / 1024 | number:2}} MB</span>
                            <span ng-switch-default>{{file.size / 1024 | number:2}} kB</span>
                            </span>)
                        </div>
                    <input type="button" ng-click="uploadFile()" value="Upload" />
                    <div ng-show="progressVisible">
                        <div class="percent">{{progress}}%</div>
                            <div class="progress-bar">
                                <div class="uploaded" ng-style="{'width': progress+'%'}"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class='text-left double-padded-top'>Social Media Links</h4>
                <button ng-click='show.addFirstSocial()' ng-hide='show.socials.length > 0'>+</button>
                <table class='table-condensed'>
                    <tr><td>Social Media Type<td>URL<td>Add/Remove</tr>
                    <tr social ng-repeat='social in show.socials track by $index'></tr>
                </table>
                <div ng-show='show.is_admin'>
                    <h4 class='text-left double-padded-top'>Podcast Feedburner URL</h4>
                    <input class='wideinput' ng-model='show.info.rss'>
                    <h4 class='text-left'>Podcast Title</h4>
                    <input class='wideinput' ng-model='show.info.podcast_title'>
                    <h4 class='text-left'>Podcast XML</h4>
                    <input class='wideinput' ng-model='show.info.podcast_slug'>
                    <h4 class='text-left double-padded-top'>Sponsor</h4>
                    <label for 'sponsor_name'>Name</label><input name='sponsor_name' ng-model='show.info.sponsor_name'>
                    <label for 'sponsor_url'>URL</label><input name='sponsor_url' ng-model='show.info.sponsor_url'>
                    <h4 class='text-left double-padded-top'>Show Times (Current week: {{show.current_week}})</h4>
                    <button ng-click='show.addFirstShowTime()' ng-hide='show.show_times.length > 0'>Add Show Time </button>
                    <table class='table'>
                        <tr><th> Start Day<th>Start Time<th>End Day<th>End Time<th>Alternation Week <th>+/-</tr>
                            <tr showtime ng-repeat='showtime in show.show_times track by $index'></tr>
                    </table>
                    <h4 class='text-left double-padded-top'>Default Language</h4>
                    <input ng-model='show.info.lang_default'>
                    <h4 class='text-left double-padded-top'>Default CRTC Category</h4>
                    <select ng-model='show.info.crtc_default'>
                        <option value='20'>20</option><option value='30'>30</option>
                    </select>
                    <h4 class='text-left double-padded-top'>Show Requirements</h4>

                    <table >
                        <tr><td>Playlist<td><input class='smallinput' name='playist' ng-model='show.info.pl_req'>%</tr>
                        <tr><td>Cancon (20)<td><input class='smallinput' name='cancon_20' ng-model='show.info.cc_20_req'>%</tr>
			<tr><td>Cancon (30)<td><input class='smallinput' name='cancon_30' ng-model='show.info.cc_30_req'>%</tr>
                        <tr><td>Female<td><input class='smallinput' name='femcon' ng-model='show.info.fem_req'>%</tr>
                        <tr><td>Indie<td><input class='smallinput' name='indy' ng-model='show.info.indy_req'>%</tr>
                    </table>
                    <h4 class='text-left double-padded-top'>Staff Notes</h4>
                    <textarea class='col1' rows='10' style="margin-bottom:16px" ng-model='show.info.notes'></textarea>
                </div>
                <h4 class='text-left double-padded-top'></h4>
                <button ng-click="show.save();" >Save Show</button>
            </div>
        </div>
    </body></html>
