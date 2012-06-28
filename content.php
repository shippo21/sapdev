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
//ini_set('display_errors', 1);
//ini_set('error_reporting', E_ALL | E_STRICT);



//Verbindung
if (!include("database.php"))
    die("database.php could not be loaded!");
if ($db_host == "" || !isset($db_host))
    die("please reinstall this panel");
//MySQL Verbindung wird getestet
require_once 'functions.php';
require_once 'meekrodb.2.0.class.php';

$errors = array();
$notifi = array();
$correc = array();

$connection = mysql_connect($db_host, $db_username, $db_password) or die("database could not be connected");
$db = mysql_select_db($database) or die("database could not be selected");
DB::$host = $db_host;
DB::$user = $db_username;
DB::$password = $db_password;
DB::$dbName = $database;

sendUsageStatisticsIfAllowed();

session_start();

$captcha_sql = mysql_query("SELECT language FROM settings WHERE id='0'");

$language_setting = mysql_result($captcha_sql, 0);
// Check if Language-file exists and include, else load English

if (!file_exists('./pages/messages/' . $language_setting . '.php')) {
    $errors[] = "<h2>The language file could not be found, English is the default language!</h2>";
    $language_setting = 'german'; // Language Change
}
require_once './pages/messages/' . $language_setting . '.php';
// Get variable for include
if (!isset($_GET['include'])) {
    $include_php = 'main';
} else {
    $include_php = filter_var($_GET['include'], FILTER_SANITIZE_STRING);
}
// Logout of Panel


if (isset($_GET['login']) && $_GET['login'] == "logout") {
    $loggedin = FALSE;
    session_destroy();
    header('Location: index.php?login=logout');
}
$loggedin = FALSE;


if (isset($_SESSION['username']) && isset($_SESSION['user_password']) || !empty($_POST['username']) && !empty($_POST['user_password'])) {


    if (isset($_POST['login_submit'])) {
        $loginun = mysql_real_escape_string($_POST['username']);
        $loginpw = mysql_real_escape_string($_POST['user_password']);
    } else {
        $loginun = $_SESSION['username'];
        $loginpw = $_SESSION['user_password'];
    }
    if (isset($_POST['login_submit'])) {
        $captcha_sql = mysql_query("SELECT login_captcha FROM settings WHERE id='0'");

        if (mysql_result($captcha_sql, 0) == "1") {
            if ($_POST['captcha_field'] != $_SESSION['captcha_streamerspanel']) {
                if ($include_php !== "main" || $include_php !== "") {
                    header('Location: index.php?login=captcha&redir=' . $include_php . '');
                    die();
                } else {
                    header('Location: index.php?login=captcha');
                    die();
                }
            }
        }
    }
    $hash = md5($loginun . $loginpw);
    $free_space = 980999;
    $selectuser = mysql_query("SELECT * FROM users WHERE md5_hash='" . mysql_real_escape_string($hash) . "'");
    if (mysql_num_rows($selectuser) == 1) {
        $_SESSION['username'] = $loginun;
        $_SESSION['user_password'] = $loginpw;
        $userdata = mysql_fetch_array($selectuser);
        $loginun = $userdata['username'];
        $user_level = $userdata['user_level'];
        $_SESSION['user_level'] = $user_level;
        $_SESSION['user_tb_id'] = $userdata['id'];

        // Abfrage Server
        if ($user_level == 'dj') {
            $selectuser = mysql_query("SELECT dj_of_user FROM users WHERE md5_hash='" . mysql_real_escape_string($hash) . "'");
            $selectit = mysql_fetch_object($selectuser);

            $user_id = $selectit->dj_of_user;


            // REWRITE LOGINNUM

            $ergebnis = mysql_query("SELECT username FROM users WHERE id = '$user_id'");
            $row = mysql_fetch_object($ergebnis);
            $loginun = $row->username;


        } else {
            $user_id = $userdata['id'];
        }

        $loggedin = TRUE;
        if (isset($_POST['login_submit'])) {
            $correc[] = "<h2>" . $messages["15"] . "</h2>";
        }

        if ($user_level == 'banned') {
            session_destroy();
            $loggedin = FALSE;
        }


    } else {
        session_destroy();
        $loggedin = FALSE;

    }
}
if (isset($loggedin) && $loggedin == TRUE) {
} else {
    if ($include_php !== "main" || $include_php !== "") {
        header('Location: index.php?login=data&redir=' . $include_php . '');
    } else {
        header('Location: index.php?login=data');
    }
}



      // Alter Uplaoder
     /*


if ($include_php == "upload" && isset($_GET['upport'])) {
    $target_path = "uploads/" . $_GET['upport'] . '/';
    $allowedExts = array();
    $maxFileSize = 0;

    $new_free_space = 50;
    function ByteSize($bytes)
    {
        $size = $bytes / 1024;
        if ($size < 1024) {
            $size = number_format($size, 2);
            $size .= ' KB';
        } else {
            if ($size / 1024 < 1024) {
                $size = number_format($size / 1024, 2);
                $size .= ' MB';
            } else if ($size / 1024 / 1024 < 1024) {
                $size = number_format($size / 1024 / 1024, 2);
                $size .= ' GB';
            }
        }
        return $size;
    }

    function getHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $k => $v) {
            if (substr($k, 0, 5) == "HTTP_") {
                $k = str_replace('_', ' ', substr($k, 5));
                $k = str_replace(' ', '-', ucwords(strtolower($k)));
                $headers[$k] = $v;
            }
        }
        return $headers;
    }

    $headers = getHeaders();

    if ($headers['X-Requested-With'] == 'XMLHttpRequest') {
        $fileName = $headers['X-File-Name'];
        $fileSize = $headers['X-File-Size'];
        $ext = substr($fileName, strrpos($fileName, '.') + 1);
        if (in_array($ext, $allowedExts) or empty($allowedExts)) {
            if ($fileSize < $maxFileSize or empty($maxFileSize) ) {
                $input = fopen("php://input", 'r');
                $output = fopen($target_path . $fileName, 'a');
                if ($output != false) {
                    while (!feof($input)) {
                        $buffer = fread($input, 4096);
                        fwrite($output, $buffer);
                    }
                    fclose($output);
                    echo '{"success":true, "file": "' . $target_path . $fileName . '"}';
                } else
                    echo ('{"success":false, "details": "Can\'t create a file handler."}');
                fclose($input);
            } else {
                echo ('{"success":false, "details": "Maximum file size: ' . ByteSize($maxFileSize) . '."}');
            }
            ;
        } else {
            echo ('{"success":false, "details": "File type ' . $ext . ' not allowed."}');
        }
    } else {
        if ($_FILES['file']['name'] != '') {

                $fileName = $_FILES['file']['name'];
                $fileSize = $_FILES['file']['size'];

            $ext = substr($fileName, strrpos($fileName, '.') + 1);
            if (in_array($ext, $allowedExts) or empty($allowedExts)) {
                if ($fileSize < $maxFileSize or empty($maxFileSize) ) {
                    $target_path = $target_path . basename($_FILES['file']['name']);
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                        echo '{"success":true, "file": "' . $target_path . '"}';
                    } else {
                       // echo '{"success":false, "details": "move_uploaded_file failed"}';
                    }
                } else {
                    echo ('{"success":false, "details": "Maximum file size: ' . ByteSize($maxFileSize) . '."}');
                }
                ;
            } else
                echo ('{"success":false, "details": "File type ' . $ext . ' not allowed."}');
        } else
            echo '{"success":false, "details": "No file received."}';
    }
    die();
}


  */



if (isset($_GET['playlist']) && $_GET['playlist'] == "left") {
    if (isset($_GET['portbase'])) {
        $port = $_GET['portbase'];
        $selectowner = mysql_query("SELECT * FROM servers WHERE portbase='" . $port . "' AND owner='" . $loginun . "'");
        if (mysql_num_rows($selectowner) == 1) {
            header("Content-type:text/xml");
            echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
            $listing_start = 1;
            $listing_end = 10000;
            $dirlisting = @scandir(dirname(__FILE__) . '/uploads/' . $port) or die();
            $dirlistingsearch = array(
                '&',
                '<',
                '>',
                '"',
                "'"
            );
            $dirlistingreplace = array(
                '&amp;',
                '&lt;',
                '&gt;',
                '&quot;',
                '&apos;'
            );
            if (!isset($dirlisting[$listing_start]))
                die();
            echo '<tree id="0">';
            for ($i = $listing_start; $i <= $listing_end; $i++) {
                if (!isset($dirlisting[$i])) {
                    continue;
                }

                if (($dirlisting[$i] != ".") && ($dirlisting[$i] != "..") && ($dirlisting[$i] != "")) {
                    $itemId = sprintf('%s/uploads/%d/%s', dirname(__FILE__), $port, str_replace($dirlistingsearch, $dirlistingreplace, $dirlisting[$i]));
                    $itemText = str_replace($dirlistingsearch, $dirlistingreplace, $dirlisting[$i]);
                    printf('<item id="%s" text="%s"/>', $itemId, $itemText);
                }
            }
            echo '</tree>';
            die();
        }
    }
} elseif ((isset($_GET['playlist']) && $_GET['playlist'] == "right") && (isset($_GET['listname']))) {
    if (isset($_GET['portbase'])) {
        $port = $_GET['portbase'];
        $selectowner = mysql_query("SELECT * FROM servers WHERE portbase='" . $port . "' AND owner='" . $loginun . "'");
        if (mysql_num_rows($selectowner) == 1) {
            header("Content-type:text/xml");
            print("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?>");
            if (base64_decode($_GET['listname']) !== "new playlist.lst") {
                $filehandle = fopen("" . dirname(__FILE__) . "/temp/" . $port . "/playlist/" . base64_decode($_GET['listname']) . "", "r");
                $contents = fread($filehandle, filesize("" . dirname(__FILE__) . "/temp/" . $port . "/playlist/" . base64_decode($_GET['listname']) . ""));
                $entrys = explode("\n", $contents);
                $dirlistingsearch = array(
                    '&',
                    '<',
                    '>',
                    '"',
                    "'"
                );
                $dirlistingreplace = array(
                    '&amp;',
                    '&lt;',
                    '&gt;',
                    '&quot;',
                    '&apos;'
                );
            }
            echo ("<tree id='0'>");
            if (base64_decode($_GET['listname']) !== "new playlist.lst") {
                $inta = 0;
                foreach ($entrys as $entry) {
                    $inta++;
                    $entry1 = str_replace(dirname(__FILE__) . "/uploads/" . $port . "/", "", $entry);
                    if ($entry1 != "") {
                        $magix = str_replace($dirlistingsearch, $dirlistingreplace, $entry1);
                        printf('<item child="0" id="%s" text="%s"></item>', $magix, $magix);
                    }
                }
                fclose($filehandle);
            }
            echo ("</tree>");
            die();
        }
    }
}


// additional message insert by $_GET
if (!isset($_GET['message_ext']) or !isset($_GET['message_lang'])) {
} else {
    if ($_GET['message_ext'] == "1") {
        $errors[] = $messages[$_GET['message_lang']];
    }
    if ($_GET['message_ext'] == "2") {
        $notifi[] = $messages[$_GET['message_lang']];
    }
    if ($_GET['message_ext'] == "3") {
        $correc[] = $messages[$_GET['message_lang']];
    }
}

// MySQL connection
$connection = mysql_connect($db_host, $db_username, $db_password) or die($messages["g1"]);
$db = mysql_select_db($database) or die($messages["g2"]);
// ?install_cancel=1 deactivates installcheck
if (file_exists("./install/install.php")) {
    $errors[] = "<h2>" . $messages["16"] . "</h2>";
}
// if including file doesn't exist then load main page
if (file_exists("./pages/" . $include_php . "_bottom.php")) {
    $include_php = $include_php;
} else {
    if (file_exists("./pages/main_bottom.php")) {
        $errors[] = $messages["g3"];
        $include_php = "main";
    } else {
        $errors[] = $messages["g3"];
        $include_php = "_no";
    }
}

// Überprüfung ob User passende Rechte hat diese Seite zu öffnen
if (($include_php == "admserver") || ($include_php == "admradio")) {
    if ($user_level != "Super Administrator") {
        $include_php = "main";
        $errors[] = "<h2>" . $messages["17"] . "</h2>";
    }
}
// check messages on headlines
$newsq = mysql_query("SELECT * FROM headlines order by id DESC LIMIT 20") or die($messages["g4"]);
$newsq_quant = mysql_num_rows($newsq);
if ($user_level == "Super Administrator" && (isset($_GET['action']) && $_GET['action'] == "remove" && isset($_GET['delmessid']))) {
    if (mysql_query(" DELETE FROM notices WHERE id='" . $_GET['delmessid'] . "' ")) {
        $correc[] = "<h2>" . $messages["18"] . "</h2>";
    } else {
        $errors[] = "<h2>" . $messages["19"] . "</h2>";
    }
}

// include functions of php file
if ((file_exists("./pages/" . $include_php . "_top.php")) && ($include_php != "_no")) {
    @include("./pages/" . $include_php . "_top.php");
}

// get all settings of db
$settingsq = mysql_query("SELECT * FROM settings WHERE id='0'") or die($messages["g5"]);
foreach (mysql_fetch_array($settingsq) as $key => $pref) {
    if (!is_numeric($key)) {
        $setting[$key] = stripslashes($pref);
    }
}

// update check
$currentVersion = SAP_VERSION;

if ($setting['update_check'] == 1 && $include_php == 'main') {
    require_once './pages/update.php';
}
if (isset($_GET['request']) && $_GET['request'] == 'html') {
    require_once './pages/' . $include_php . '_bottom.php';
    die();
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
    <script src="./js/jquery-1.5.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="./js/modernizr.2.06.js" type="text/javascript" charset="utf-8"></script>
    <script src="./js/adminpanel.js" type="text/javascript" charset="utf-8"></script>
    <script src="./js/jquery.nyroModal-1.6.2.pack.js" type="text/javascript" charset="utf-8"></script>
    <?php
    if ($include_php == "main") {
        ?>
        <script type="text/javascript">
            $(function () {
                function preloadImg(image) {
                    var img = new Image();
                    img.src = image;
                }

                preloadImg('images/modalwin/ajaxLoader.gif');
            });
        </script>
        <?php
    }
    ?>
    <?php
if ($include_php == "upload") {
    ?>
    <script src="./js/shCore.js" type="text/javascript"></script>
    <script src="./js/shBrushJScript.js"  type="text/javascript" ></script>
    <script src="./js/shBrushXml.js"  type="text/javascript" ></script>

    <!-- SET UP AXUPLOADER  -->
    <script src="./js/jquery-1.5.min.js" type="text/javascript"></script>
    <script src="./js/ajaxupload.js" type="text/javascript"></script>

    <link rel="stylesheet" href="./css/fancyTheme/style.css" type="text/css" media="all" />
    <!-- /SET UP AXUPLOADER  -->


    <link rel="stylesheet" href="./mcss/shCore.css" type="text/css" media="all" />
    <link rel="stylesheet" href="./css/shThemeEclipse.css" type="text/css" media="all" />
    <link rel="stylesheet" href="./css/shCoreDefault.css" type="text/css"/>

    <script type="text/javascript">
        SyntaxHighlighter.all({toolbar:false});
    </script>

    <?php
}

    ?>
    <?php
    if ($include_php == 'playlist') {
        ?>
        <script language="javascript">

            function clearPlaylist() {
                var itemId = this.tree2.rootId;
                var temp = this.tree2._globalIdStorageFind(itemId);
                this.tree2.deleteChildItems(itemId);
            }
            function setValue() {
                var i = 0;
                var j = 0;
                var n = 0;
                arvArray = new Array();
                arvArray = getChilds(this.tree2.htmlNode, arvArray, "<?php
                    echo "/";
                    ?>")
                var arv = arvArray.toString();
                document.treeform.arv.value = arv;
            }
            function getChilds(Childs, arr, label) {
                var i = 0;
                for (i = 0; i < Childs.childsCount; i++) {
                    if (Childs.childNodes[i].childsCount == 0) {
                        if (Childs.childNodes[i].label[0] != "/") {
                            arr.push(label + Childs.childNodes[i].label);
                        }
                        else arr.push(Childs.childNodes[i].label);
                    }
                    else {
                        arr = getChilds(Childs.childNodes[i], arr, label + Childs.childNodes[i].label + "/")
                    }
                }
                return arr;
            }
        </script>
        <?php
    }
    ?>

</head>
<body>
<div id="mainContainer">
<header>
    <div class="header logo">
        <a href="loadContent-main" title=""><img src="./images/logo.png" alt=""/></a>
    </div>
        <span class="header profileStatus"><?php
            echo htmlspecialchars($messages["20"]) . ' <strong>' . htmlspecialchars($_SESSION['username']) . '</strong>&nbsp;(<a href="content.php?login=logout" title="Sign out">' . htmlspecialchars($messages["21"]) . '</a>)</span>';
            ?>
</header>
<div class="clear"></div>
<div id="menuContainer">
    <div id="menuFrame">
        <div id="menuHead">
                <span id="headContent"><?php
                    echo htmlspecialchars($messages["22"]);
                    ?> <b id="menuHead.username"><?PHP
                        echo htmlspecialchars($loginun);
                        ?></b></span>
            <?php
            if ($user_level == 'Super Administrator') {
                $noticesq = mysql_query("SELECT * FROM notices");
                if (mysql_num_rows($noticesq) == 0) {
                    echo '<span id="headContent_under">' . htmlspecialchars($messages["23"]) . '</span>';
                } else {
                    $noticesqquant = mysql_num_rows($noticesq);
                    if ($noticesqquant == 1) {
                        echo '<span id="headContent_under">' . htmlspecialchars($messages["24"]) . ' <b id="menuHead.amount">' . htmlspecialchars($noticesqquant) . "</b> " . htmlspecialchars($messages["25"]) . "</span>";
                    } else {
                        echo '<span id="headContent_under">' . htmlspecialchars($messages["26"]) . ' <b id="menuHead.amount">' . htmlspecialchars($noticesqquant) . "</b> " . htmlspecialchars($messages["27"]) . " </span>";
                    }
                }
            } elseif ($user_level == 'User') {
                echo '<span id="headContent_under">Shoutcast Admin Panel 3 - ' . htmlspecialchars($messages["28"]) . '</span>';
            } else {
                echo '<span id="headContent_under">Shoutcast Admin Panel 3 - ' . htmlspecialchars($messages["add28"]) . '</span>';
            }
            ?>
        </div>
        <div id="navHead">

            <h4><?php
                echo htmlspecialchars($messages["29"]);
                ?></h4>
            <h5><?php
                echo htmlspecialchars($messages["30"]);
                ?></h5>
        </div>
        <?php

        if ($user_level == 'User' OR $user_level == 'Super Administrator') {
            ?>
            <nav class="navFirst">
                <ul class="navMenu">
                    <ul class="navMenu">
                        <li><a href="loadContent-main" title="">Schnell&uuml;bersicht</a></li>
                    </ul>
                    <li><a href="loadContent-contact" title=""><?php
                        echo htmlspecialchars($messages["31"]);
                        ?></a></li>
                    <li><a href="loadContent-public" title=""><?php
                        echo htmlspecialchars($messages["32"]);
                        ?></a></li>
                    <li><a href="loadContent-account" title=""><?php
                        echo htmlspecialchars($messages["33"]);
                        ?></a></li>

                    <?php  if ($user_level == 'User') { ?>
                    <li><a href="loadContent-admuser" title=""><?php
                        echo htmlspecialchars($messages["add33"]);
                        ?></a></li>
                    <?php } ?>

                    <li><a href="loadContent-server" title=""><?php
                        if ($user_level == 'Super Administrator') {
                            echo htmlspecialchars($messages["add34"]);
                        } else {
                            echo htmlspecialchars($messages["34"]);
                        }
                        ?></a></li>


                </ul>
            </nav>

            <?php
        }
        if ($setting['os'] == 'linux') {
            ?>


            <div id="navHeadSub">
                <h4><?php
                    echo htmlspecialchars($messages["35"]);
                    ?></h4>
                <h5><?php
                    echo htmlspecialchars($messages["36"]);
                    ?></h5>
            </div>


            <nav class="navBottom">
                <ul class="navMenu">
                    <li><a href="loadContent-music" title=""><?php

                        if ($user_level == 'dj') {

                        } else {
                            echo htmlspecialchars($messages["37"]);
                        }
                        ?></a></li>
                    <li><a href="loadContent-autodj" title=""><?php
                        echo htmlspecialchars($messages["38"]);
                        ?></a></li>
                </ul>
            </nav>
            <?php
        }
        ?>
        <?php
        if ($user_level == 'Super Administrator') {
            ?>
            <div id="navHeadSub_2">
                <h4><?php
                    echo htmlspecialchars($messages["39"]);
                    ?></h4>
                <h5><?php
                    echo htmlspecialchars($messages["40"]);
                    ?></h5>
            </div>
            <nav class="navBottom">
                <ul class="navMenu">
                    <li><a href="loadContent-admserver" title=""><?php
                        echo htmlspecialchars($messages["41"]);
                        ?></a></li>
                    <li><a href="loadContent-admradio" title=""><?php
                        echo htmlspecialchars($messages["42"]);
                        ?></a></li>
                    <li><a href="loadContent-admuser" title=""><?php
                        echo htmlspecialchars($messages["43"]);
                        ?></a></li>

                    <li><a href="loadContent-admnews" title=""><?php
                        echo htmlspecialchars($messages["nws1"]);
                        ?></a></li>

                </ul>
            </nav>
            <?php
        }
        ?>
        <div id="navHeadSub_3">
            <h4><?php
                echo htmlspecialchars($messages["44"]);
                ?></h4>
            <h5><?php
                echo htmlspecialchars($messages["45"]);
                ?></h5>
        </div>
        <div id="infoBox">
            <table class="ip_table">
                <tbody>
                    <tr>
                        <td class="ip_table"><?php
                            echo $messages["46"];
                            ?></td>
                        <td class="ip_table_under"><?PHP
                            echo ($_SESSION['username']);
                            ?></td>
                    </tr>
                    <tr>
                        <td class="ip_table"><?php
                            echo $messages["47"];
                            ?></td>
                        <td class="ip_table_under"><?PHP
                            echo ($_SERVER['SERVER_NAME']);
                            ?></td>
                    </tr>
                    <tr>
                        <td class="ip_table"><?php
                            echo $messages["48"];
                            ?></td>
                        <td class="ip_table_under"><?php
                            echo $currentVersion;
                            ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="rightFrame">
    <div id="contentload">
    </div>
    <?PHP


    if (count($errors) > 0) {
        echo array_reduce($errors, function($initial, $value)
        {
            return $initial . sprintf('<div class="error">%s</div>', $value);
        }, '');
    }

    if (count($notifi) > 0) {
        echo array_reduce($notifi, function($initial, $value)
        {
            return $initial . sprintf('<div class="notifi">%s</div>', $value);
        }, '');
    }

    if (count($correc) > 0) {
        echo array_reduce($correc, function($initial, $value)
        {
            return $initial . sprintf('<div class="correct">%s</div>', $value);
        }, '');
    }

    echo '<section id="content">';
    echo '<div class="box">';
    require_once './pages/' . $include_php . '_bottom.php';
    echo '</div>';
    echo '</section>';
    ?>
</div>
<div class="clear"></div>
<footer>
    <p>
        Streamers Admin Panel | djcrackhome | Dave | <a href="http://www.streamerspanel.com/" target="_blank">http://www.streamerspanel.com</a>
        | <a href="http://www.nagualmedia.de/" target="_blank">Design by Zephon</a> | Translated by: <i><?php

        ?></i> | <a href="http://www.facebook.com/streamers.admin.panel" target="_blank"><img
        src="./images/facebook.png" alt=""></a><a href="http://www.twitter.com/streamerspanel" target="_blank"><img
        src="./images/twitter.png" alt=""></a>
    </p>
</footer>
</div>
</body>
</html>
