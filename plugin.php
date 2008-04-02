<?php
/*
Plugin Name: Access Logs
Plugin URI: http://www.satollo.com/english/wordpress/access-logs
Description: Access Logs generate a access log file in Apache combined format rotated monthly.
Version: 1.0
Author: Satollo
Author URI: http://www.satollo.com
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2008  Satollo (email : satollo@gamil.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function aal_activate()
{
	mkdir(dirname(__FILE__) . '/../../logs');
}

function aal_init()
{
	global $aal_options;
	$href = $_SERVER['REQUEST_URI'];
    if (strpos($href, 'wp-admin') !== false) return;
    if (strpos($href, 'wp-includes') !== false) return;
    
	$referer = $_SERVER['HTTP_REFERER'];
	$host = $_SERVER['REMOTE_ADDR'];
	$ident = $_SERVER['REMOTE_IDENT'];
	$timeStamp = date("d/M/Y:H:i:s O");
	$reqType = $_SERVER['REQUEST_METHOD'];
	$servProtocol = $_SERVER['SERVER_PROTOCOL'];
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$statusCode = "200";
	$size = 0;

	if ($host == "") $host = "-";
	if ($ident == "") $ident = "-";
	if ($auth == "") $auth = "-";
	if ($reqType == "") $reqType = "-";
	if ($servProtocol == "") $servProtocol = "-";

	$referer = '"' . $referer . '"';

	$userAgent = '"' . $userAgent . '"';

	$clfString = $host." ".$ident." ".$auth." [".$timeStamp."] \"".$reqType." ".$href." ".$servProtocol."\" ".$statusCode." ".
	  $size . " " . $referer . " " . $userAgent . "\r\n";

	$logFile = dirname(__FILE__) . '/../../logs/access-' . date("Y-m") . ".log";

	$fileWrite = fopen($logFile, 'a');

	//flock($fileWrite, LOCK_SH);
	fwrite($fileWrite, $clfString);
	//flock($fileWrite, LOCK_UN);
	fclose($fileWrite);
}

add_action('activate_access-logs/plugin.php', 'aal_activate');
add_action('init', 'aal_init');

?>
