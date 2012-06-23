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

session_start();
// save random captcha value in browsersession
$_SESSION['captcha_streamerspanel'] = rand(10000, 99999);
//	include database
if (!include("database.php"))
    die("database.php could not be loaded!");
if ($db_host == "" || !isset($db_host))
    die("please reinstall this panel");
//MySQL Verbindung wird getestet
$connection = mysql_connect($db_host, $db_username, $db_password) or die("database could not be connected");
$db = mysql_select_db($database) or die("database could not be selected");
// Language File check on DB
$captcha_sql = mysql_query("SELECT language FROM settings WHERE id='0'");
$language_setting = mysql_result($captcha_sql, 0);
// Check if Language-file exists and include, else load English
if (file_exists("./pages/messages/" . $language_setting . ".php")) {
    $language_setting = $language_setting;
} else {
    $errors[] = "<h2>The language file could not be found, German is the default language!</h2>";
    $language_setting = "german";
}
include "./pages/messages/" . $language_setting . ".php";
//	if an error occured by logging in, check which login and then echo error
if (isset($_GET['login']) && $_GET['login'] == "data") {
    $error[] = "<h2>" . $messages["1"] . "</h2>";
}
if (isset ($_GET['login']) && $_GET['login'] == "captcha") {
    $error[] = "<h2>" . $messages["2"] . "</h2>";
}
if (isset ($_GET['login']) && $_GET['login'] == "logout") {
    $correct[] = "<h2>" . $messages["3"] . "</h2>";
}
// if user is already logged in, than redirect to content
$loggedin = FALSE;
if (isset($_SESSION['username']) && isset($_SESSION['user_password'])) {
    $loginun = $_SESSION['username'];
    $loginpw = $_SESSION['user_password'];
}
// get all settings of db
$settingsq = mysql_query("SELECT * FROM settings WHERE id='0'") or die($messages["g5"]);
foreach (mysql_fetch_array($settingsq) as $key => $pref) {
    if (!is_numeric($key)) {
        $setting[$key] = stripslashes($pref);
    }
}
$hash = md5($loginun . $loginpw);
$selectuser = mysql_query("SELECT * FROM users WHERE md5_hash='" . mysql_real_escape_string($hash) . "'");
if (mysql_num_rows($selectuser) == 1) {
    $_SESSION['username'] = $loginun;
    $_SESSION['user_password'] = $loginpw;
    $userdata = mysql_fetch_array($selectuser);
    $loginun = $userdata['username'];
    $user_level = $userdata['user_level'];
    $user_id = $userdata['id'];
    $loggedin = TRUE;
}
if (isset($loggedin) && $loggedin == TRUE) {
    header('Location: content.php');
}
?>
<!DOCTYPE HTML>
<html class="no-js">
<head>
    <title><?php
        echo htmlspecialchars($setting['title']) . ' - ' . htmlspecialchars($setting['slogan']);
        ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="icon" href="./images/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="./css/framework.css"/>
    <!--[if IE]>
    <style type="text/css">
        input.loginfield {
            padding: 9px 6px 0px 6px !important;
            height: 24px !important;
            width: 161px !important;
        }
    </style>
    <![endif]-->
</head>
<body>

<div id="mainContainer">
    <div id="header_top">
        <div class="header logo_login">
            <a href="index.php" title=""><img src="images/logo.png" alt=""/></a>
        </div>
    </div>
    <div id="primary_login">
        <?PHP
        $errors = array();
        if (count($errors) > 0) {
            echo array_reduce($errors, function($initial, $value)
            {
                return $initial . sprintf('<div class="error">%s</div>', $value);
            }, '');
        }
        $correc = array();
        if (count($correc) > 0) {
            echo array_reduce($correc, function($initial, $value)
            {
                return $initial . sprintf('<div class="correct">%s</div>', $value);
            }, '');
        }

        // Server News Modul
        $server_news = mysql_query("SELECT server_news FROM settings WHERE id='0'");
        if (mysql_result($server_news, 0) == "1") {
            ?>
             <div id="content">
        <div class="box">
            <h2>Server News</h2>
            <?php
            $news_reporter = mysql_query("SELECT * FROM news ORDER BY id DESC LIMIT 2");
            while($row = mysql_fetch_object($news_reporter))
            {
                echo "<h3><b>".$row->titel."</b></h3>";
                echo "<p>".$row->text."</p>";
                echo "<br>";

            }
            echo '</div> </div>';
        }



        ?>



        <div id="content">
            <div class="box">
                <h2><?php
                    echo $messages[4];
                    ?></h2>

                <p><?php
                    echo $messages[5];
                    ?></p>

                <form method="post" action="content.php<?php
                if (isset($_GET['redir'])) {
                    echo "?include=" . $_GET['redir'];
                }
                ?>">
                    <fieldset>
                        <legend><?php
                            echo $messages["6"];
                            ?></legend>
                        <div class="input_field">
                            <label for="a"><?php
                                echo $messages["7"];
                                ?></label>
                            <input class="loginfield" name="username" type="text" autocomplete="off"/>
							<span class="field_desc"><?php
                                echo $messages["8"];
                                ?></span>
                        </div>
                        <div class="input_field">
                            <label for="b"><?php
                                echo $messages["9"];
                                ?></label>
                            <input class="loginfield" name="user_password" type="password" autocomplete="off"/>
							<span class="field_desc"><?php
                                echo $messages["10"];
                                ?></span>
                        </div>
                        <?php
                        $captcha_sql = mysql_query("SELECT login_captcha FROM settings WHERE id='0'");
                        if (mysql_result($captcha_sql, 0) == "1") {
                            ?>
                            <div class="input_field">
                                <label for="a"><?php
                                    echo $messages["11"];
                                    ?></label>
                                <input class="loginfield" name="captcha_field" type="text" pattern="[0-9]{5}"
                                       autocomplete="off"/>
                                <span class="field_desc"><span class="captchaspan"><img class="field_desc"
                                                                                        src="captcha/picture.php"></span></span>
                            </div>
                            <?php
                        }
                        ?>
                        <center>
                            <input type="hidden" name="login_submit"/>
                            <input class="loginsubmit" type="submit" value="<?php
                            echo $messages["12"];
                            ?>"/>
                            <input class="loginsubmit" type="reset" value="<?php
                            echo $messages["13"];
                            ?>"/>
                        </center>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
        <div class="clear"></div>
        <footer>
            <p>
                Steamers Admin Panel | Dev by: djcrackhome & Dave | <a href="http://www.streamerspanel.com/"
                                                                       target="_blank">http://www.streamerspanel.com</a> |
                <a href="http://www.nagualmedia.de/" target="_blank">Design by Zephon</a> | <a
                href="http://www.facebook.com/streamers.admin.panel" target="_blank"><img src="./images/facebook.png"
                                                                                          alt=""></a><a
                href="http://www.twitter.com/streamerspanel" target="_blank"><img src="./images/twitter.png" alt=""></a>
            </p>
        </footer>
    </div>
</body>
</html>