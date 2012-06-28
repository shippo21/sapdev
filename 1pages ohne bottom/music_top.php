<?PHP
/**
 * Streamers Admin Panel
 *
 * Originally written by Sebastian Graebner <djcrackhome>
 * Fixed and edited by David Schomburg <dave>
 *
 * The Streamers Admin Panel is a web-based administration interface for
 * Nullsoft, Inc.'s SHOUTcast Distributed Network Audio Server (DNAS),
 * and is intended for use on the Linux-distribution Debian.
 *
 * LICENSE: This work is licensed under the Creative Commons Attribution-
 * ShareAlike 3.0 Unported License. To view a copy of this license, visit
 * http://creativecommons.org/licenses/by-sa/3.0/ or send a letter to
 * Creative Commons, 444 Castro Street, Suite 900, Mountain View, California,
 * 94041, USA.
 *
 * @author     Sebastian Graebner <djcrackhome@streamerspanel.com>
 * @author     David Schomburg <dave@streamerspanel.com>
 * @copyright  2009-2012  S. Graebner <djcrackhome> D. Schomburg <dave>
 * @license    http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-ShareAlike 3.0 Unported License
 * @link       http://www.streamerspanel.com

 */

if (stripos($_SERVER['PHP_SELF'], 'content.php') === false) {
    die ("You can't access this file directly...");
}

$settingsq = mysql_query("SELECT * FROM settings WHERE id='0'") or die($messages["g5"]);
foreach(mysql_fetch_array($settingsq) as $key => $pref) {
	if (!is_numeric($key)) {
		$setting[$key] = stripslashes($pref);
	}
}

if ($_GET['error']=="port") {
	$errors[] = "<h2>".$messages["385"]."</h2>";
}
if ($_GET['error']=="access") {
	$errors[] = "<h2>".$messages["386"]."</h2>";
}
if ($_GET['error']=="dir") {
	$errors[] = "<h2>".$messages["387"]."</h2>";
}
if ($_GET['error']=="space") {
	$errors[] = "<h2>".$messages["388"]."</h2>";
}
if ($_GET['error']=="dirlisting") {
	$errors[] = "<h2>".$messages["389"]."</h2>";
}
if ($_GET['error']=="sc_trans_access") {
	$correc[] = "<h2>".$messages["390"]."</h2>";
}
