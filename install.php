<?php
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

if (version_compare(phpversion(), '5.3.0') < 0) {
    echo 'needs at least php 5.3';
    exit;
}

require_once 'functions.php';
include "./pages/messages/german.php";
require_once 'install.requirements.php';

if (isset($_POST['sql_dns'])) {
    if (!$connection = mysql_connect($_POST['sql_dns'], $_POST['sql_user'], $_POST['sql_pass'])) {
        $errors[] = "<h2>" . $messages["i1"] . "</h2>";
    }
    else {
        if (!$db = mysql_select_db($_POST['sql_daba'])) {
            $errors[] = "<h2>" . $messages["i1"] . "</h2>";
        }
        else {
            //DATABASE.php anlegen
            $dbconfig = '<?php
            $db_host = "' . $_POST['sql_dns'] . '";
            $db_username = "' . $_POST['sql_user'] . '";
            $db_password = "' . $_POST['sql_pass'] . '";
            $database = "' . $_POST['sql_daba'] . '";
            ';
            $datei = fopen("database.php", "w");
            fwrite($datei, $dbconfig);


            if (!mysql_query("CREATE TABLE IF NOT EXISTS `headlines` ( `id` int(11) NOT NULL auto_increment, `username` varchar(100) NOT NULL default '', `title` varchar(100) NOT NULL default '', `text` text NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ")) {
                $errors[] = "<h2>MySQL: headlines could not be created!</h2>";
            }

            if (!mysql_query("CREATE TABLE IF NOT EXISTS `news` ( `id` int(11) NOT NULL auto_increment, `titel` varchar(255) NOT NULL default '', `text` text NOT NULL, PRIMARY KEY  (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ")
            ) {
                $errors[] = "<h2>" . $messages["i2"] . "</h2>";
            }


            if (!mysql_query("CREATE TABLE IF NOT EXISTS `notices` ( `id` int(11) NOT NULL auto_increment, `username` varchar(100) NOT NULL default '', `reason` varchar(100) NOT NULL default '', `message` varchar(10240) NOT NULL, `ip` varchar(100) NOT NULL default '', `time` varchar(100) NOT NULL default '', PRIMARY KEY  (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ")
            ) {
                $errors[] = "<h2>" . $messages["i2"] . "</h2>";
            }

            if (!mysql_query("CREATE TABLE IF NOT EXISTS `servers` ( `id` int(11) NOT NULL auto_increment, `owner` varchar(100) NOT NULL default '', `maxuser` varchar(100) NOT NULL default '', `portbase` int(11) NOT NULL default '0', `bitrate` varchar(100) NOT NULL default '',  `adminpassword` varchar(100) NOT NULL default '', `password` varchar(100) NOT NULL default '',  `sitepublic` varchar(100) NOT NULL default '1', `logfile` varchar(100) NOT NULL default '../logs/sc_{port}.log',  `realtime` varchar(100) NOT NULL default '1',   `screenlog` varchar(100) NOT NULL default '0', `showlastsongs` varchar(100) NOT NULL default '10', `tchlog` varchar(100) NOT NULL default 'Yes', `weblog` varchar(100) NOT NULL default 'no', `w3cenable` varchar(100) NOT NULL default 'Yes', `w3clog` varchar(100) NOT NULL default 'sc_w3c.log', `srcip` varchar(100) NOT NULL default 'ANY', `destip` varchar(100) NOT NULL default 'ANY', `yport` varchar(100) NOT NULL default '80', `namelookups` varchar(100) NOT NULL default '0', `relayport` varchar(100) NOT NULL default '0', `relayserver` varchar(100) NOT NULL default 'empty', `autodumpusers` varchar(100) NOT NULL default '0', `autodumpsourcetime` varchar(100) NOT NULL default '30', `contentdir` varchar(100) NOT NULL default '', `introfile` varchar(100) NOT NULL default '', `titleformat` varchar(100) NOT NULL default '', `urlformat` varchar(200) NOT NULL default 'http://', `publicserver` varchar(100) NOT NULL default 'default', `allowrelay` varchar(100) NOT NULL default 'Yes', `allowpublicrelay` varchar(100) NOT NULL default 'Yes', `metainterval` varchar(100) NOT NULL default '32768', `suspended` varchar(100) NOT NULL default '', `abuse` int(11) NOT NULL default '0', `pid` varchar(100) NOT NULL default '', `autopid` varchar(100) NOT NULL, `webspace` varchar(100) NOT NULL, `serverip` varchar(100) NOT NULL, `serverport` varchar(100) NOT NULL, `streamtitle` varchar(100) NOT NULL, `streamurl` varchar(100) NOT NULL, `shuffle` int(1) NOT NULL default '1', `samplerate` varchar(100) NOT NULL, `channels` int(1) NOT NULL default '2', `genre` varchar(100) NOT NULL, `quality` int(1) NOT NULL default '1', `crossfademode` varchar(100) NOT NULL, `crossfadelength` varchar(100) NOT NULL, `useid3` int(1) NOT NULL default '1', `public` int(1) NOT NULL default '1', `aim` varchar(100) NOT NULL, `icq` varchar(100) NOT NULL, `irc` varchar(100) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ")
            ) {
                $errors[] = "<h2>" . $messages["i3"] . "</h2>";
            }

            if (!mysql_query("CREATE TABLE IF NOT EXISTS `settings` ( `id` int(11) NOT NULL default '0', `title` varchar(50) NOT NULL, `slogan` varchar(50) NOT NULL default '', `display_limit` int(11) NOT NULL default '10', `host_add` varchar(100) NOT NULL default '192.168.0.1', `os` varchar(100) NOT NULL default '', `dir_to_cpanel` varchar(100) NOT NULL default '', `scs_config` varchar(1) NOT NULL, `adj_config` varchar(1) NOT NULL, `php_mp3` varchar(50) NOT NULL default '10', `php_exe` varchar(50) NOT NULL default '250', `update_check` varchar(1) NOT NULL default '1', `login_captcha` varchar(1) NOT NULL default '1', `ssh_user` varchar(256) NOT NULL, `ssh_pass` varchar(256) NOT NULL, `ssh_port` varchar(11) NOT NULL default '22', `language` varchar(256) NOT NULL,`shellset` varchar(20),`server_news` varchar(1) NOT NULL, PRIMARY KEY  (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 ")
            ) {
                $errors[] = "<h2>" . $messages["i4"] . "</h2>";
            }

            if (!mysql_query("CREATE TABLE IF NOT EXISTS `users` ( `id` int(11) NOT NULL auto_increment, `username` varchar(100) NOT NULL default '', `user_password` varchar(50) NOT NULL default '', `md5_hash` varchar(100) NOT NULL default '', `user_level` varchar(100) NOT NULL default '', `user_email` varchar(200) NOT NULL default '', `contact_number` varchar(15) NOT NULL, `mobile_number` varchar(15) NOT NULL, `account_notes` text NOT NULL, `name` varchar(50) NOT NULL default '', `surname` varchar(50) NOT NULL default '', `age` varchar(3) NOT NULL, `dj_of_user` varchar(5) NOT NULL, PRIMARY KEY  (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ")
            ) {
                $errors[] = "<h2>" . $messages["i5"] . "</h2>";
            }

            if (!mysql_query("REPLACE INTO `notices` (`id`, `username`, `reason`, `message`, `ip`, `time`) VALUES (78,
			'Shoutcast Admin', 'Welcome - German Welcome Test Message',
			'" . $messages["i0"] . "', '127.0.0.1', '')")
            ) {
                $errors[] = "<h2>" . $messages["i6"] . "</h2>";
            }
            if (isset($_POST['server_os']) && $_POST['server_os'] == "linux") {
                $dir_new = $_POST['server_dir'] . "/";
            }
            if (!mysql_query("REPLACE INTO `settings` (`id`, `title`, `slogan`, `display_limit`, `host_add`, `os`, `dir_to_cpanel`, `scs_config`, `adj_config`, `php_mp3`, `php_exe`, `update_check`, `login_captcha`, `ssh_user`, `ssh_pass`, `ssh_port`, `language`,`shellset`, `server_news`) VALUES (0, '" . $_POST['server_title'] . "', 'Public', 10, '" . $_POST['server_dns'] . "', 'linux', '" . dirname(__FILE__) . "/', '0', '0', '20', '230', '1', '1', '" . base64_encode($_POST['server_sshuser']) . "', '" . base64_encode($_POST['server_sshpass']) . "', '" . $_POST['server_sshport'] . "', '" . $_POST['server_lang'] . "', '" . $_POST['server_shell'] . "',1)")) {
                $errors[] = "<h2>" . $messages["i7"] . "</h2>";
            }

            if (!mysql_query("REPLACE INTO `users` (`id`, `username`,`user_password`,`md5_hash`, `user_level`, `user_email`, `contact_number`, `mobile_number`, `account_notes`, `name`, `surname`, `age`) VALUES (1, '" . $_POST['user'] . "', '" . $_POST['pass'] . "', '" . md5($_POST['user'] . $_POST['pass']) . "', 'Super Administrator', 'admin@domain.com', 'none', '0', 'Default Administrator', 'Max', 'Mustermann', 'non') ")) {
                $errors[] = "<h2>" . $messages["i8"] . "</h2>";
            }

            if (isset($_POST['allow_usage_statistics'])) {
                $fileHandle = fopen('.isAllowedToSendUsageStatistics', 'w');
                fclose($fileHandle);
            }

        }
    }
}
$cwd = str_replace("\\", "/", getcwd());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
    <title><?php echo $messages["g0"];?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="./css/install.css"/>
</head>
<body>
<div id="container">
<div id="header_top">
    <div class="header logo">
        <a href="#" title=""><img src="images/logo.png" alt=""/></a>
    </div>
    <div class="header top_nav">
        <span class="session"><?php echo $messages["i10"];?> <a href="http://www.streamerspanel.com" title="Sign out">cancel</a></span>
    </div>
</div>
<div id="sidebar">
    <div id="navigation">
        <div class="sidenav">
            <div class="nav_info">
                <span><?php echo $messages["i10"];?></span><br/>
                <span class="nav_info_messages"><?php echo $messages["i12"];?></span>
            </div>
            <div class="navhead_blank">

                <span><?php echo $messages["i11"];?></span>
                <span><?php echo SAP_VERSION ?></span>
            </div>
            <div class="subnav_child">
                <ul class="submenu">
                    <li><b><?php echo $messages["i172"];?></b></li>

                    <?php foreach ($requirements as $requirement): ?>
                    <?php if (call_user_func($requirement['function']) === true): ?>
                        <li class="success">
                            <b>
                                <?php echo $messages[$requirement['success_message']] ?>
                            </b>
                        </li>
                        <?php else: ?>
                        <li class="failure">
                            <b>
                                <?php echo $messages[$requirement['failure_message']] ?>
                            </b>
                        </li>
                        <?php endif ?>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="navhead">
                <span><?php echo $messages["i29"];?></span>
                <span>ip und versionsinfo</span>
            </div>
            <div class="subnav">
                <table cellspacing="0" cellpadding="0" class="ip_table">
                    <tbody>
                    <tr>
                        <td class="ip_table"><?php echo $messages["i30"];?></td>
                        <td class="ip_table_under"><?PHP echo ($_SERVER['REMOTE_ADDR']);?></td>
                    </tr>
                    <tr>
                        <td class="ip_table"><?php echo $messages["i31"];?></td>
                        <td class="ip_table_under"><?PHP echo ($_SERVER['SERVER_ADDR']);?></td>
                    </tr>
                    <tr>
                        <td class="ip_table"><?php echo $messages["i32"];?></td>
                        <td class="ip_table_under"><?php echo $messages["g01"];?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="primary">
    <?php
    if (count($errors) > 0 ) {
        foreach ($errors as $errors_cont)
            $errors_list .= "<div>" . $errors_cont . "</div>";
        echo ('<div class="error"><h2><center>' . $errors_list . '</center></h2></div>');
    }
    else {
        if ($_POST['sql_dns']) {
            echo '<div class="correct"><h2><center><h2><a href="index.php">' . $messages["i9"] . '</center></h2></div>';
        }
    }
    ?>
    <?PHP
    $errors = array();
    $correc = array();
    $notifi = array();

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
    ?>
    <div id="content">

        <div class="box">
            <h2><?php echo $messages["i33"];?></h2>

            <div class="tool_top_menu">
                <div class="main_shorttool">
                    <p><?php echo $messages["i34"];?></p>
                    <ul>
                        <li><?php echo $messages["i35"];?></li>
                        <li><?php echo $messages["i36"];?></li>
                        <li><?php echo $messages["i37"];?></li>
                        <li><?php echo $messages["i38"];?></li>
                        <li><?php echo $messages["i39"];?></li>
                        <li><?php echo $messages["i40"];?></li>
                        <li><?php echo $messages["i41"];?></li>
                    </ul>
                </div>
                <div class="main_right">
                    <h2><?php echo $messages["i42"];?></h2>

                    <p><?php echo $messages["i43"];?></p>
                </div>
            </div>
            <form action="install.php" method="post">
                <fieldset>
                    <legend><?php echo $messages["i44"];?></legend>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i45"];?></label>
                        <input type="text" name="sql_dns" class="mediumfield" value="localhost"/>
                        <span class="field_desc"><?php echo $messages["i46"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i47"];?></label>
                        <input name="sql_user" type="text" class="mediumfield"/>
                        <span class="field_desc"><?php echo $messages["i48"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i49"];?></label>
                        <input name="sql_pass" type="password" class="mediumfield"/>
                        <span class="field_desc"><?php echo $messages["i49"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i50"];?></label>
                        <input name="sql_daba" type="text" class="mediumfield"/>
                        <span class="field_desc"><?php echo $messages["i51"];?></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend><?php echo $messages["i52"];?></legend>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i53"];?></label>
                        <input name="user" type="text" class="mediumfield"/>
                        <span class="field_desc"><?php echo $messages["i54"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i55"];?></label>
                        <input name="pass" type="password" class="mediumfield"/>
                        <span class="field_desc"><?php echo $messages["i56"];?></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend><?php echo $messages["i57"];?></legend>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i58"];?></label>
                        <input type="text" name="server_dir" class="mediumfield" value="<?php echo $cwd;?>"/>
                        <span class="field_desc"><?php echo $messages["i59"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i60"];?></label>
                        <input type="text" name="server_dns" class="mediumfield"
                               value="<?php echo $_SERVER["HTTP_HOST"];?>"/>
                        <span class="field_desc"><?php echo $messages["i61"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i62"];?></label>
                        <input type="text" name="server_title" class="mediumfield" value="My Radio"/>
                        <span class="field_desc"><?php echo $messages["i63"];?></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend><?php echo $messages["i64"];?></legend>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i65"];?></label>
                        <input type="text" name="server_sshuser" class="mediumfield"/>
                        <span class="field_desc"><?php echo $messages["i66"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i67"];?></label>
                        <input type="password" name="server_sshpass" class="mediumfield"/>
                        <span class="field_desc"><?php echo $messages["i66"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["i68"];?></label>
                        <input type="text" name="server_sshport" class="smallfield" value="22"/>
                        <span class="field_desc"><?php echo $messages["i68"];?></span>
                    </div>
                    <div class="input_field">
                        <label for="a"><?php echo $messages["issh1"];?></label>
                        <select class="formselect_loca" name="server_shell">
                            <option value="ssh2"><?php echo $messages["issh2"];?></option>
                            <option value="ssh2"><?php echo $messages["issh3"];?></option>
                            <option value="shellexec"><?php echo $messages["issh4"];?></option>
                        </select>
                        <span class="field_desc"><?php echo $messages["issh5"];?></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Other settings</legend>
                    <div>
                        <label for="a"><?php echo $messages['install_allow_usage_statistics_label'] ?></label>
                        <input type="checkbox" name="allow_usage_statistics" checked="checked"/>
                        <span
                            class="field_desc"><?php echo $messages['install_allow_usage_statistics_description'] ?></span>
                    </div>
                    <br/>

                    <div class="input_field">
                        <select name="server_lang">
                            <option value="german" selected="selected">German (de) - Official Language*</option>
                            <option value="english">English (en)</option>
                        </select>
                        <span class="field_desc">Language which the panel will run with</span>
                    </div>
                </fieldset>
                <br/>
                <input class="submit" type="submit" value="Install"/>
            </form>
        </div>
    </div>
</div>
<div class="clear"></div>
<div id="footer">
    <p>StreamersAdminPanel | djcrackhome | dave | <a
        href="http://www.streamerspanel.com">http://www.streamerspanel.com</a> | <a href="http://www.nagualmedia.de/">Design
        by Zephon</a></p>
</div>
</div>
</body>
</html>
