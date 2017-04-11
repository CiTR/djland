<html lang="en-US">
<head>
	<meta charset="utf-8">
	<title>Submit Online | CiTR</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='http://fonts.googleapis.com/css?family=Lato:400,400italic,700|PT+Serif:400,700,400italic' rel='stylesheet' type='text/css'>

	<script type='text/javascript' src='./js/jquery-ui/external/jquery/jquery.js'></script>

	<link rel="stylesheet" media="all" href="http://www.citr.ca/wp-content/themes/citr/style.css" />
	<link rel="stylesheet" href="./station-js/trackform.css" />

	<!--[if lt IE 9]>
	<script src="http://www.citr.ca/wp-content/themes/citr/assets/bower_components/respond/dest/respond.min.js"></script>
	<![endif]-->

	<script type='text/javascript' src='http://www.citr.ca/wp-content/themes/citr/assets/bower_components/modernizr/modernizr-custom.js'></script>
	<link rel="shortcut icon" href="http://www.citr.ca/citr.ico" />
	<link rel="shortcut icon" href="http://citr.ca/wp-content/themes/citr/assets/img/icons/favicon-48.png" />
	<link rel="shortcut icon" href="http://citr.ca/wp-content/themes/citr/assets/img/icons/favicon-48@2x.png" />
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="http://citr.ca/wp-content/themes/citr/assets/img/icons/favicon-76.png" />
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="http://citr.ca/wp-content/themes/citr/assets/img/icons/favicon-60@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="http://citr.ca/wp-content/themes/citr/assets/img/icons/favicon-76@2x.png">
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="http://citr.ca/wp-content/themes/citr/assets/img/icons/favicon-60@3x.png">
	<script type = 'text/javascript' src='./station-js/online-submission.js'></script>
  <script type='text/javascript' src='./js/musicsubmissions/functions.js'></script>

			<script type="text/javascript">
			window._wpemojiSettings = {"baseUrl":"http:\/\/s.w.org\/images\/core\/emoji\/72x72\/","ext":".png","source":{"concatemoji":"\/wp-includes\/js\/wp-emoji-release.min.js?ver=4.2.4"}};
			!function(a,b,c){function d(a){var c=b.createElement("canvas"),d=c.getContext&&c.getContext("2d");return d&&d.fillText?(d.textBaseline="top",d.font="600 32px Arial","flag"===a?(d.fillText(String.fromCharCode(55356,56812,55356,56807),0,0),c.toDataURL().length>3e3):(d.fillText(String.fromCharCode(55357,56835),0,0),0!==d.getImageData(16,16,1,1).data[0])):!1}function e(a){var c=b.createElement("script");c.src=a,c.type="text/javascript",b.getElementsByTagName("head")[0].appendChild(c)}var f,g;c.supports={simple:d("simple"),flag:d("flag")},c.DOMReady=!1,c.readyCallback=function(){c.DOMReady=!0},c.supports.simple&&c.supports.flag||(g=function(){c.readyCallback()},b.addEventListener?(b.addEventListener("DOMContentLoaded",g,!1),a.addEventListener("load",g,!1)):(a.attachEvent("onload",g),b.attachEvent("onreadystatechange",function(){"complete"===b.readyState&&c.readyCallback()})),f=c.source||{},f.concatemoji?e(f.concatemoji):f.wpemoji&&f.twemoji&&(e(f.twemoji),e(f.wpemoji)))}(window,document,window._wpemojiSettings);
		</script>
		<style type="text/css">
img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 .07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}
</style>
<link rel="stylesheet" href="http://citr.ca/wp-content/plugins/tablepress/css/default.min.css?ver=1.6.1">
<script type='text/javascript' src='http://citr.ca/wp-includes/js/jquery/jquery.js?ver=1.11.2'></script>
<script type='text/javascript' src='http://citr.ca/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.2.1'></script>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
<script type='text/javascript' src='js/jquery-ui-1.11.3.min.js'></script>
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />

<!-- set the datepicker date format -->
<script>
  $(function() {
      $( "#date-released" ).datepicker({ dateFormat: "yy-mm-dd" });
  });
</script>


	<link rel="canonical" href="http://www.citr.ca/submissions/">
</head>
<div class="wrap nav-target" role="document">
    <main class="main" role="main">
      <article class="container-fluid">
	<div class="row">
		<div class="entry-content col-sm-8 col-sm-offset-2">
	<div class="entry-content-inner">

		<p>Items with a &#9733; are required.</p>
		<form action="/api2/public/submission" method="POST" enctype="multipart/form-data" id="data">
			<div class="album-row">
			<div style="width:50%;float:left;">
				&#9733; Artist / Band name: <input id="artist-name" type="text" style="width:95%;margin-bottom:30px" placeholder="The Ultimate Supergroup" name='artist'>
			</div>
			<div style="width:50%;float:right;">
				&#9733; Contact email: <input type="text" id="contact-email" style="width:100%;margin-bottom:30px;" placeholder="ultimate@example.com" name='email'>
			</div>
		</div>
		<div class="album-row">
			<div style="width:50%;float:left;">
				Record label: <input type="text" id="record-label" style="width:95%;margin-bottom:30px" placeholder="Stardust Records" name='label'>
			</div>
			<div style="width:50%;float:right;">
				&#9733; Home city: <input type="text" id="home-city" style="width:100%;margin-bottom:30px;" placeholder="London, England" name='location'>
			</div>
		</div>
		<div class="album-row">
			<div style="width:50%;float:left;">
				Member / Artist names: <input type="text" id="member-names" style="width:95%;margin-bottom:30px" placeholder="David Bowie, Aretha Franklin, Psy" name='credit'>
			</div>
			<div style="width:50%;float:right;">
				&#9733; Album name: <input type="text" id="album-name" style="width:100%;margin-bottom:30px;" placeholder="Ziggy and Friends" name='title'>
			</div>
		</div>
		<div class="album-row">
			<div style="width: 50%;float:left;">
				&#9733; Genre: <select name="genre" id="genre-picker" style="width:95%;margin-bottom:30px;">
					<!-- TODO: populate this with present genres from DB -->
					<option>Electronic</option>
					<option>Experimental</option>
					<option>Hip Hop / R&amp;B / Soul</option>
					<option>International</option>
					<option>Jazz/Classical</option>
					<option>Punk / Hardcore / Metal</option>
					<option>Rock / Pop / Indie</option>
					<option>Roots / Blues / Folk</option>
					<option>Talk</option>
				</select>
			</div>
			<div style="width: 50%;float:right;">
				Date released: <input type="text" id="date-released" style = "width:100%;margin-bottom:30px;" class="datepicker" name='releasedate' readonly>
			</div>
		</div>
		<div class="album-row">
      <div class="fem-can-van">
        <label>
          <input type="checkbox" id="female-artist" style="margin-right:20px" name='femcon' />
          FemCon: Self-identifying female in 2 of the 4 MPWR categories
          <span class="tooltip-target">?</span>
          <span class="tooltip-box">
            <p>
              <strong>M</strong>usic composed by a self-identified female
            </p>
            <p>
              <strong>P</strong>erformer of music or lyrics is self-identified female
            </p>
            <p>
              <strong>W</strong>ords written by a self-identified female
            </p>
            <p>
              <strong>R</strong>ecording done by or or produced by a self-identified female
            </p>
          </span>
        </label>
      </div>
			<div>
				<label>
          <input type="checkbox" id="canada-artist" style="margin-right:20px;" name='cancon' />
          CanCon: You fullfill at least 2 of the 4 MAPL categories
          <span class="tooltip-target">?</span>
          <span class="tooltip-box">
            <p>
              <strong>M</strong>usic composed by a Canadian
            </p>
            <p>
              <strong>A</strong>rtist performing music or lyrics is Canadian
            </p>
            <p>
              <strong>P</strong>erformance is recorded or live broadcast in Canada
            </p>
            <p>
              <strong>L</strong>yrics written by a Canadian
            </p>
          </span>
        </label>
			</div>
			<div>
				<label>
          <input type="checkbox" id="vancouver-artist" style="margin-right:20px" name='local' />
          Local: You / your band is located in the Greater Vancouver Area
          <!--
          <span class="tooltip-target">?</span>
          -->
          <span class="tooltip-box">You / your band is located in the Greater Vancouver Area</span>
        </label>
			</div>
		</div>
		<br>Comments: <textarea rows="4" id="comments-box" style="width:100%;margin-bottom:20px;" placeholder="Please tell us about yourself, your album, or things to think about as we listen to your songs." name='description'></textarea>

  	<p>We accept .jpg or .png files of at least 500 by 500 pixels.</p>
    <!--
  	<input type="file" id="album-art-input-button" style="display:none" name='art_url' />
  	<button type="button" id="album-art-button" class="submission-button">
  		Add Album Art (Optional)
  	</button>
  	<output id="album-viewer"></output>

  	<script type="text/javascipt">
  		$('#album-art-button').click(function(event){
        event.preventDefault();
        $('#album-art-input-button').trigger('click');
      });
  	</script>
  -->

    <input type="file" id="album-art-input-button" style="display:none"  name="art_url" />
    <button type="button" id="album-art-button" class="submission-button">
      Add Ablum Art (Optional)
    </button>

  	<output id="album-viewer"></output>

    <script type="text/javascript">
      $('#album-art-button').click(function(event){
        event.preventDefault();
        $('#album-art-input-button').trigger('click');
      });
    </script>

		<p>Please submit a minimum of four 320kbps MP3 files.</p>

		<div id="submit-field"></div>

		<input type="file" id="new-track-button-input" style="display:none"  name="songlist" multiple/>
		<button type="button" id="new-track-button" class="submission-button">
			Add files
		</button>

		<script type="text/javascript">
			$('#new-track-button').click(function(event){
        event.preventDefault();
        $('#new-track-button-input').trigger('click');
      });
		</script>

  	<div id="submit-button-div">
  		<button id="submit-button" class="submission-button" type="submit">
  			SUBMIT
  		</button>

      <script type="text/javascript">
        $('#submit-button').click(function(event) {
          event.preventDefault();
          // submitForm();
        });
      </script>


    </div>
  </form>

</div>

<script type='text/javascript'>
/* <![CDATA[ */
var impression_object = {"ajax_url":"http:\/\/www.citr.ca\/wp-admin\/admin-ajax.php"};
/* ]]> */
</script>
<script type='text/javascript' src='http://citr.ca/wp-content/plugins/adrotate/library/jquery.adrotate.dyngroup.js'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var click_object = {"ajax_url":"http:\/\/www.citr.ca\/wp-admin\/admin-ajax.php"};
/* ]]> */
</script>
<script type='text/javascript' src='http://citr.ca/wp-content/plugins/adrotate/library/jquery.adrotate.clicktracker.js'></script>
<!-- AdRotate JS -->
<script type="text/javascript">
jQuery(document).ready(function(){
if(jQuery.fn.gslider) {
	jQuery('.g-2').gslider({ groupid: 2, speed: 20000 });
}
});
</script>

</body>
</html>
