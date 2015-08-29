<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

if( permission_level() >= $djland_permission_levels['dj']){

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
        <body class='wallpaper'>
        <div ng-app="djland.editShow">
            <script src='js/jquery-1.11.3.min.js'></script>
            <script src='js/constants.js'></script>
            <script src="js/angular.js"></script>
            <script src="js/shows/edit.js"></script>
            <script src="js/api.js"></script>
            <script>
                var member_id = "<?php echo $_SESSION['sv_id']; ?>";
                var username = "<?php echo $_SESSION['sv_username']; ?>";
            </script>
            <div id='wrapper' ng-controller="editShow as show" ng-show='show.info.id'>
               Select show to edit: <select ng-model="show.show_value" ng-change="show.changeShow()" ng-options="id as show.name for (id,show) in show.member_shows">
                        </select>
                <h4 class='text-left double-padded-top'> Show name </h4>
                <input readonly id='show_name' ng-model='show.info.name'/>
                <h4 class='text-left double-padded-top'>Show Host/Operator</h4>
                <input class='wideinput' ng-model='show.info.host'>
                <h4 class='text-left double-padded-top'>Primary Genre</h4>
                <select ng-model='show.info.primary_genre_tags' ng-options='value for (key,value) in show.primary_genres'></select>
                <h4 class='text-left'>Secondary Genres</h4>
                <input class='wideinput' ng-model='show.info.secondary_genre_tags'/>
                <h4 class='text-left double-padded-top'>Show Alert</h4>
                <input ng-model='show.info.alerts'/>
                <h4 class='text-left double-padded-top'>Show Description</h4>
                <textarea class='col1' rows='10' style="margin-bottom:16px" ng-model='show.info.show_desc'></textarea>
                <h4 class='text-left double-padded-top'>Website</h4>
                <input class='wideinput' ng-model='show.info.website'>
                <h4 class='text-left double-padded-top'>Show Image</h4>
                <input class='wideinput' id='show_image' ng-model='show.info.show_img'>
                <div ng-controller="FileUploadCtrl">
                    <div  class="row">
                        <label for="fileToUpload">Either Choose files, or Drag Files</label><br />
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
                {{show.show_img}}
                <h4 class='text-left double-padded-top'>Social Media Links</h4>
                <table class='table-condensed'>
                    <tr><td>Social Media Type<td>URL<td>Add/Remove</tr>
                    <tr><td><td><td><button ng-click='show.addFirst()' ng-hide='show.social.length > 0'>+</button></td></tr>
                    <tr ng-repeat='social in show.social track by $index'>
                    <td><input ng-model='social.social_name'></td>
                    <td><input ng-model='social.social_url'></td>
                    <td>
                        <button ng-click='show.addSocial($index)'>+</button>
                        <button ng-click='show.removeSocial($index)'>-</button>
                    </td>
                </tr>
                </table>
                <h4 class='text-left double-padded-top'></h4>
                <button ng-click="show.save();" >Save Show</button>
            </div>
        </div>
    </body></html>
<?php

}else{
    header("Location: main.php");
}?>
