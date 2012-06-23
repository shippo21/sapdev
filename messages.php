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

if (!include("database.php")) die("database.php could not be loaded!");
if ($db_host == "" || !isset($db_host)) die("please reinstall this panel");

//MySQL Verbindung wird getestet
$connection = mysql_connect($db_host, $db_username, $db_password) or die ("database could not be connected");
$db = mysql_select_db($database) or die ("database could not be selected");
// Language File check on DB
$captcha_sql = mysql_query("SELECT language FROM settings WHERE id='0'");
$language_setting = mysql_result($captcha_sql,0);
// Check if Language-file exists and include, else load English
if (file_exists("./pages/messages/".$language_setting.".php")) {
	$language_setting = $language_setting;
}
else {
	$errors[] = "<h2>The language file could not be found, English is the default language!</h2>";
	$language_setting = "english";
}	
include "./pages/messages/".$language_setting.".php";
// Get variable for messagerequest
if (!isset($_GET['id'])) {	
	$id = "error";
}
else {	
	$id = $_GET['id'];	
}
// Thanks to CWH Underground, should be good now
// That is the fix for http://www.milw0rm.com/exploits/5813
$id = str_replace('/', '', $id);
$id=strip_tags($id);
if ($id == "error") {
	echo "<h2>".$messages["50"]."</h2>";
}
else {
	$noticeq = mysql_query("SELECT * FROM notices WHERE id='".$id."'");
	$notice = mysql_fetch_array($noticeq);
	echo "<h2>".$messages["51"]." ".$notice['username']."<hr /></h2>
		<h3>".$messages["52"].": <i>".$notice['reason']."</i></h3>
		<h3>".$messages["53"].": ".nl2br($notice['message'])."</h3>
		<p>[".$notice['ip']."]</p>";
}

