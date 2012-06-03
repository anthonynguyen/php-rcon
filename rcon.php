<?php
// Define colours here:
$c0 = "000000";
$c1 = "FF0000";
$c2 = "00FF00";
$c3 = "FFFF00";
$c4 = "0000FF";
$c5 = "00FFFF";
$c6 = "FF00FF";
$c7 = "CCCCCC";

$ck0 = array("0", "8", "h", "p", "x", "H", "P", "X", "(", "`", "@");
$ck1 = array("1", "9", "a", "i", "q", "y", "A", "I", "Q", "Y", ")", "!");
$ck2 = array("2", "b", "j", "r", "z", "B", "J", "R", "Z", "*", ":", "\"");
$ck3 = array("3", "c", "k", "s", "C", "K", "S", "+", "#", "[", "{", ";", "^"); 
$ck4 = array("4", "d", "l", "t", "D", "L", "T", ",", "/", "<", "|", "$");
$ck5 = array("5", "e", "m", "u", "E", "M", "U", "%", "=", "]", "}", "-");
$ck6 = array("6", "f", "n", "v", "F", "N", "V", ".", "&", ">", "~", ".");
$ck7 = array("7", "g", "o", "w", "G", "O", "W", "�", "\\", "?", "'", "�", "�", "�", "�", "�", "�", "_");

$colours = array_fill_keys($ck0, $c0);
$colours += array_fill_keys($ck1, $c1);
$colours += array_fill_keys($ck2, $c2);
$colours += array_fill_keys($ck3, $c3);
$colours += array_fill_keys($ck4, $c4);
$colours += array_fill_keys($ck5, $c5);
$colours += array_fill_keys($ck6, $c6);
$colours += array_fill_keys($ck7, $c7);

if (isset($_GET["address"]) and isset($_GET["port"]) and isset($_GET["pw"]) and isset($_GET["command"])) {
	$ip = gethostbyname($_GET["address"]);
	$port = intval($_GET["port"]);
	isset($ip) or die("Invalid address.");
	isset($port) or die("Invalid port.");
	$password = $_GET["pw"];
	$command = $_GET["command"];
} else {
	$badparams = "";
	isset($_GET["address"]) or $badparams .= "address, ";
	isset($_GET["port"]) or $badparams .= "port, ";
	isset($_GET["pw"]) or $badparams .= "password, ";
	isset($_GET["command"]) or $badparams .= "command, ";
	die("Bad parameters: " . trim($badparams, ", ") . ".");
}

$s = fsockopen("udp://" . $ip, $port, $errno, $errstr, 3) or die("Could not connect.");
fwrite($s, "\xFF\xFF\xFF\xFFrcon " . $password . " " . $command);
$response = fread($s, 32768);
fclose($s);

$strp = "^7" . substr($response, 10);

function colourise($p) {
	global $colours;
	if (strlen($p) == 0) {
		return $p;
	} else {
		$f = substr($p, 0, 1);
		if (array_key_exists($f, $colours)) {
			$p = '<font color="#' . $colours[$f] . '">' . substr($p, 1) . "</font>";
		} else {
			$p = "^" . $p;
		}
		return $p;
	}
}

$strp = explode("^", $strp);
$clrd = array_map("colourise", $strp);
$clrd = implode($clrd);

echo($clrd);
?>