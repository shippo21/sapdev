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

$limit = $setting['display_limit'];
if (!isset($_POST['p']))
$p = 0;
else
$p = $_POST['p'] * $limit;
$l = $p + $limit;
if ($_GET['action'] == "newuser" && $_GET['function'] == "update") {

    if (mysql_num_rows(mysql_query("SELECT * FROM users WHERE username='".$_POST['eusername']."'")) == 1) {
		$errors[] = "<h2>".$messages["266"]."</h2>";
	}
	else {
		$id = mysql_result(mysql_query("SELECT id FROM users order by id DESC"),0) + 1;
		if ($_POST['euser_password']!=$_POST['ceuser_password']) {
			$notifi[] = "<h2>".$messages["267"]."</h2>";
		}
		else {
            if ( $_SESSION['user_level'] == 'User'){
                if (mysql_query("INSERT INTO users (
			username,
			user_password,
			md5_hash,
			user_level,
			user_email,
			contact_number,
			mobile_number,
			account_notes,
			name,
			surname,
			age,
			dj_of_user
			)
			    VALUES(

			'".$_POST['eusername']."',
			'".$_POST['euser_password']."',
			'".md5(strtolower($_POST['eusername'].$_POST['euser_password']))."',
			'".$_POST['euser_level']."',
			'".$_POST['euser_email']."',
			'".$_POST['econtact_number']."',
			'".$_POST['emobile_number']."',
			'".$_POST['eaccount_notes']."',
			'".$_POST['ename']."',
			'".$_POST['esurname']."',
			'".$_POST['eage']."',
			'".$_SESSION['user_tb_id']."') "))

                {
                    $correc[] = "<h2>".$messages["268"]."</h2>";
                }
                else {
                    $errors[] = "<h2>".$messages["269"]."</h2>";
                }
            }else{

                if (mysql_query("INSERT INTO users (
			username,
			user_password,
			md5_hash,
			user_level,
			user_email,
			contact_number,
			mobile_number,
			account_notes,
			name,
			surname,
			age )
			    VALUES(

			'".$_POST['eusername']."',
			'".$_POST['euser_password']."',
			'".md5(strtolower($_POST['eusername'].$_POST['euser_password']))."',
			'".$_POST['euser_level']."',
			'".$_POST['euser_email']."',
			'".$_POST['econtact_number']."',
			'".$_POST['emobile_number']."',
			'".$_POST['eaccount_notes']."',
			'".$_POST['ename']."',
			'".$_POST['esurname']."',
			'".$_POST['eage']."') "))

                {
                    $correc[] = "<h2>".$messages["268"]."</h2>";
                }
                else {
                    $errors[] = "<h2>".$messages["269"]."</h2>";
                }


            }
		}
	}
}


if ($_GET['action'] == "edit") { 

    if (!empty($_POST['seluser'])){
        $dj_of_user = $_POST['seluser'];
         mysql_query("UPDATE users SET dj_of_user= '$dj_of_user' WHERE id='".$_GET['id']."'");
    }


	$user = mysql_query("SELECT username FROM users WHERE id='".$_GET['id']."'");
	if (mysql_num_rows($user)==0) {
		$errors[] = "<h2>".$messages["270"]."</h2>";
		$user_check = "1";
	}
	else {
		$user_check = "0";
	}


	if ($_GET['function'] == "update" && isset($_GET['id']) && $user_check !== "1") {
		if (mysql_query("UPDATE users SET md5_hash='".md5(mysql_result($user,0).$_POST['euser_password'])."', user_level='".$_POST['euser_level']."', contact_number='".$_POST['econtact_number']."', mobile_number='".$_POST['emobile_number']."', user_email='".$_POST['euser_email']."', name='".$_POST['ename']."', surname='".$_POST['esurname']."', age='".$_POST['eage']."', account_notes='".$_POST['eaccount_notes']."' WHERE id='".$_GET['id']."'")) {
			$correc[] = "<h2>".$messages["271"]."</h2>";
		}
		else {
			$errors[] = "<h2>".$messages["272"]."</h2>";
		}

	}
	if ($_GET['function'] == "delete" && isset($_GET['id']) && $user_check !== "1") {
		$userid = mysql_query("SELECT username FROM users WHERE id='".$_GET['id']."'");
		$userslevel = mysql_query("SELECT user_level FROM users WHERE id='".$_GET['id']."'");
		if (mysql_result($userid,0)==$loginun) {
			$errors[] = "<h2>".$messages["273"]."</h2>";
		}
		else {
			if (mysql_result($userslevel,0) == "Super Administrator") {
				$errors[] = "<h2>".$messages["274"]."</h2>";
			}
			else {
				if (mysql_query("DELETE FROM users WHERE id='".$_GET['id']."'")) {
					$correc[] = "<h2>".$messages["275"]."</h2>";
					$user_check = "1";
				}
				else {
					$errors[] = "<h2>".$messages["276"]."</h2>";
				}
			}
		}
	}
}
