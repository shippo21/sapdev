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

if ($user_level == "Super Administrator") {
	if ($_GET['action'] == "savesettings") {
		foreach($_POST as $key => $pref) {

            $_POST[$key] = addslashes($pref);

		}


        if ($_POST['ssh_pass'] != '***'){
            if (mysql_query("UPDATE settings SET host_add='".$_POST['host_add']."',dir_to_cpanel='".addslashes($_POST['dir_to_cpanel'])."',title='".$_POST['title']."', slogan='".$_POST['slogan']."', scs_config='".$_POST['scs_config']."', adj_config='".$_POST['adj_config']."', php_mp3='".$_POST['php_mp3']."', php_exe='".$_POST['php_exe']."', display_limit='".$_POST['display_limit']."', update_check='".$_POST['update_check']."', ssh_user='".base64_encode($_POST['ssh_user'])."', language='".$_POST['language']."', ssh_pass='".base64_encode($_POST['ssh_pass'])."', ssh_port='".$_POST['ssh_port']."', login_captcha='".$_POST['login_captcha']."', shellset='".$_POST['rootsucess']."', server_news='".$_POST['servernews']."'")) {

                // Anlegen einer htaccess Datein für Plesk User
                //$htaccess_cont = @fopen(".htaccess","w+");
                //@fputs($htaccess_cont,"php_value upload_max_filesize ".$_POST['php_mp3']."M\r\nphp_value post_max_size ".$_POST['php_mp3']."M\r\nphp_value max_execution_time ".$_POST['php_exe']."\r\nphp_value max_input_time ".$_POST['php_exe']."");
                //@fclose($htaccess_cont);


                $correc[] = "<h2>".$messages["226"]."</h2>";
            }
            else {
                $errors[] = "<h2>".$messages["227"]."</h2>";
            }
        }else{
            if (mysql_query("UPDATE settings SET host_add='".$_POST['host_add']."',dir_to_cpanel='".addslashes($_POST['dir_to_cpanel'])."',title='".$_POST['title']."', slogan='".$_POST['slogan']."', scs_config='".$_POST['scs_config']."', adj_config='".$_POST['adj_config']."', php_mp3='".$_POST['php_mp3']."', php_exe='".$_POST['php_exe']."', display_limit='".$_POST['display_limit']."', update_check='".$_POST['update_check']."', ssh_user='".base64_encode($_POST['ssh_user'])."', language='".$_POST['language']."', ssh_port='".$_POST['ssh_port']."', login_captcha='".$_POST['login_captcha']."', shellset='".$_POST['rootsucess']."', server_news='".$_POST['servernews']."'")) {

                // Anlegen einer htaccess Datein für Plesk User
                //$htaccess_cont = @fopen(".htaccess","w+");
                //@fputs($htaccess_cont,"php_value upload_max_filesize ".$_POST['php_mp3']."M\r\nphp_value post_max_size ".$_POST['php_mp3']."M\r\nphp_value max_execution_time ".$_POST['php_exe']."\r\nphp_value max_input_time ".$_POST['php_exe']."");
                //@fclose($htaccess_cont);


                $correc[] = "<h2>".$messages["226"]."</h2>";
            }
            else {
                $errors[] = "<h2>".$messages["227"]."</h2>";
            }
        }



	}
}
