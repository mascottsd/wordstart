<?php
function strContains($haystack, $needle)
{
     return strpos($haystack, $needle) !== false;
}

function startsWith($haystack, $needle)
{
     return (substr($haystack, 0, strlen($needle)) === $needle);
}

function endsWith($haystack, $needle)
{
		$length = strlen($needle);
    return !$length ? true : (substr($haystack, -$length) === $needle);
}


$findCh = $_REQUEST['q'];
$matches = array();
if (!strlen($findCh)) {
	die("[]");
}
// https://raw.githubusercontent.com/dwyl/english-words/master/words.txt
$fname = "./words.txt";
//$fname = "/usr/share/dict/words";
$myfile = fopen($fname, "r") or die("Unable to open file!");
$maxMatches = 100;
$cnt = 0;
while (($line = fgets($myfile)) !== false) {
	$line = trim($line);
	if (startsWith($line, $findCh)) {
		$cnt++;
		if ($cnt < $maxMatches)
			$matches[] = $line;
	}
}
//echo fread($myfile, filesize($fname));
fclose($myfile);

echo json_encode( array(cnt => $cnt, matches => $matches) );
?>