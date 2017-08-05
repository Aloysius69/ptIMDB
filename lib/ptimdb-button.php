<?php
/***********************************************************************************/
/*	ptalbum-button V0.27 Alpha
/*	by Alakhnor
/*	required for ptalbum
/***********************************************************************************/

$wpconfig = realpath("../../../../wp-config.php");

if (!file_exists($wpconfig))  {
	echo "Could not found wp-config.php. Error in path :\n\n".$wpconfig ;	
	die;	
}// stop when wp-config is not there

require_once($wpconfig);
require_once(ABSPATH.'/wp-admin/admin.php');

// check for rights
if(!current_user_can('edit_posts')) die;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ptalbum Browser</title>
<link rel="stylesheet" href="<?php echo get_settings('siteurl') ?>/wp-admin/wp-admin.css?version=<?php bloginfo('version'); ?>" type="text/css" />
<script type="text/javascript" src="jquery.js"></script>
<style type="text/css">
#IMDbSearch { line-height: 1.1em; padding: 10px; margin-bottom: 10px; width: 100px; text-align: center; background-color: #ddd}
#IMDbSearch:hover {cursor: pointer; background-color: #000; color: #FFF}
#IMDbWrapper { overflow: auto; height: 150px; }
</style>

<script type="text/javascript">
        $(document).ready(function() {
		$("#IMDbSearch").click( function() {
			var thetext= $('#imdbtext').val();
			$("#IMDbWrapper").load("imdb_search.php?qrystr="+thetext);
		});

          });

	function decompressed(text) {
		var textout = unserialize(text);
		return textout;
	}

	function search_imdb() {

	 	if (document.inputform.imdbsearch.value != '') {
			var thetext= document.inputform.imdbsearch.value;

			mceWindow = window.opener;
			if(mceWindow.tinyMCE) {
				mceWindow.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, thetext);
			} else {
				edCanvas = mceWindow.document.getElementById('content');
				mceWindow.edInsertContent(edCanvas, thetext);
			}
		}
		window.close();
	}

</script>
</head>
<body>

<div class="wrap">
	<legend><?php _e('Search for IMBd', 'ptalbum') ;?></legend>
	<form name="selectform" method="post" action="" >
		<fieldset class="options">
			<table>
				<tr>
					<td><?php echo 'Enter search'; ?></td>
					<td><input id="imdbtext" name="imdbtext" type="text" size=40 /></td>
				</tr>
			</table>
		</fieldset>
	</form>
	<div id="IMDbSearch">Search</div>
	<div id="IMDbWrapper"></div>
</div>
</body>
</html>
<?php

?>