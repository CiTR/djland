<?php


$library_post_key = 'something';
$library_post_val = 'something-else';



include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/menu_header.php");


if (permission_level() >= $djland_permission_levels['volunteer']['level']) {
?>
    <html>

    <head>
        <meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
        <link rel='stylesheet' href='css/style.css' type='text/css'>
        <title>dupe nukem!</title>
    </head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <body class='wallpaper'>

        <?php print_menu() ?>
        <h1>dupe nuke-em!</h1>
        <p>select from the options and then click LOAD to load some duplicate albums that can be nuked! (It will take a couple minutes to load the dupes)</p>
        <form id='dupenuker'><input type="hidden" name="action" value="dupenuking"></input>
            <input type="hidden" name="'.$library_post_key.'" value="'.$library_post_val.'"></input>
            Date Range:<br />
            <input id='date_start' name='date_start' value='2012-00-00'></input> to
            <input id='date_end' name='date_end' value='2013-00-00'></input><br />
            Exclude Text: (character sequences between the semicolons will not be included in the duplicate search)<br />
            <input id='exclude' name='exclude' value=" ;  ;s/t;"></input><br />
            <br /><br /><a id='nukem' name='submit' value='submit'>nuke \'em!</a>
        </form>
        <div id="result">&nbsp;</div>
    <?php } ?>
    <script src="js/jquery.form.js"></script>
    <script>
        $(function() {

            var options = {
                target: '#result', // target element(s) to be updated with server response
                beforeSubmit: showLoading, // pre-submit callback
                success: showResponse, // post-submit callback
                url: './form-handlers/library-handler.php',
                type: 'POST'
                // other available options:
                //url:       url         // override for form's 'action' attribute
                //type:      type        // 'get' or 'post', override for form's 'method' attribute
                //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                //clearForm: true        // clear all form fields after successful submit
                //resetForm: true        // reset the form after successful submit

                // $.ajax options can be used here too, for example:
                //timeout:   3000
            };



            $('#nukem').click(function() {

                $('#dupenuker').ajaxSubmit(options)

            });

            function showLoading() {
                $('#result').html('loading.......');

            }

            function showResponse(responseText, statusText, xhr, $form) {
                $('#result').html(responseText);
            }
        });
    </script>

    </body>