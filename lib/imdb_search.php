<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* class.imdb_search.php (php4)
* Fetch search results from the internet movie database - imdb.com
* @author       Bernd Essl <bernd@ak47.at>
* @copyright    Bernd Essl <bernd@ak47.at>
* @license      license   http://gnu.org/copyleft/gpl.html GNU GPL
* @version      SVN: 1
* @link         http://ak-47.at/
*/
if (isset($_GET['qrystr'])) $query = $_GET['qrystr']; else return;


$query = cleanUpQuery($query);
if ($query) {

	$imdb_searchform = 'http://imdb.com/find?q=';
	$imdb_site_result = file_get_contents ($imdb_searchform.$query);

	if (preg_match('/No Matches/i', $imdb_site_result)) {
		//nothing found
	} else {
		$title = array();
		$pattern = '/<a href="\/title\/tt\d{1,8}\/" onclick="set_args\(\'(.+)\',\d{1,3},\d\)">([^<]+)<\/a>/';
		$pattern = '/<br><a href="\/title\/tt([0-9]+%?)\/"(.*?)>(.*?)<\/a>/';
		if (preg_match_all($pattern,$imdb_site_result,$results, PREG_SET_ORDER )) {
        		foreach ($results as $result) :
        		        $value = serialize(array( 'link' => stripslashes(str_replace('<br>','',$result[0])), 'title' => $result[3]));
        			$title[$result[1]] = $value;

       			endforeach;
       			$title = ($title);
       			ksort($title);
       			$title = array_unique($title);
		} else {
			$pattern = '/\/trailers\/title\/tt([0-9]+%?)\/trailers/';
			if (preg_match($pattern,$imdb_site_result,$results)) {
				$key = $results[1];
				$http = 'http://imdb.com/title/tt'.$key.'/';
			}

			$pattern = '/<title>(.*?)<\/title>/';
			if (preg_match($pattern,$imdb_site_result,$results)) {
				$titre = $results[1];
				$link = '<a href="'.$http.'" title="'.$titre.'">'.$titre.'</a>';
        		        $value = serialize(array( 'link' => stripslashes($link), 'title' => $titre));
				$title[$key]= $value;
			}
		}
	}
}

// echo serialize($title);

foreach ($title as $key => $output) :
        $out = unserialize($output);
        echo 'IMDb id: '.$key.' | Link: '.$out['link'].'<br />';
endforeach;

function cleanUpQuery($q) {
        return urlencode($q);
}

?>