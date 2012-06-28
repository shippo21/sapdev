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

?>
<h2><?php echo $messages["86"];?></h2>
<div class="contact_top_menu">
<div class="tool_top_menu">
    <div class="main_shorttool"><?php echo $messages["87"];?></div>
    <div class="main_righttool">
        <h2><?php echo $messages["88"];?></h2>

        <p><?php echo $messages["89"];?></p>

        <p>&nbsp;</p>
    </div>
</div>
<table cellspacing="0" cellpadding="0">
    <thead>
    <tr>
        <th><?php echo $messages["90"];?></th>
        <th><?php echo $messages["91"];?></th>
        <th><?php echo $messages["92"];?></th>
        <th><a class="selector" href="content.php?include=admradio&action=new"><?php echo $messages["93"];?></a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (mysql_num_rows($listq) == 0) {
        echo "<tr>
							<td colspan=\"5\">" . $messages["94"] . "</td>
							</tr>";
    }
    else {
        while ($data = mysql_fetch_array($listq)) {
            echo '<tr>
                        		<td><a href="http://' . $setting['host_add'] . ':' . $data['portbase'] . '/" target="_blank">' . $data['owner'] . '</a></td>
      		                  	<td><a href="http://' . $setting['host_add'] . ':' . $data['portbase'] . '/" target="_blank">' . $data['portbase'] . '</a></td>
            	            	<td><div class="space_show" style="background-position:';
            if (file_exists("./uploads/" . $data['portbase'] . "/")) {
                $dir = "./uploads/" . $data['portbase'] . "/";
                -$filesize = 0;
                if (is_dir($dir)) {
                    if ($dp = opendir($dir)) {
                        while ($filename = readdir($dp)) {
                            if (($filename == '.') || ($filename == '..'))
                                continue;
                            $filedata = stat($dir . "/" . $filename);
                            $filesize += $filedata[7];
                        }
                        $actual_dir_size = $filesize / 1024;
                    }
                }
            }
            $negative_background_pos = ($actual_dir_size / $data['webspace']) * 120;
            echo '-' . $negative_background_pos . 'px 0px;"></div></td>
								<td><a class="delete" href="content.php?include=admradio&view=' . $data["id"] . '&action=delete">' . $messages["95"] . '</a>
								 <!---
								<a class="selector" href="content.php?include=admradio&view=' . $data["id"] . '&action=restart">' . $messages["96"] . '</a>
								--->
								<a class="edit" href="content.php?include=admradio&action=update&view=' . $data["id"] . '">' . $messages["97"] . '</a></td>
								</tr>';
        }
    }
    ?>
    </tbody>
</table>
<ul class="paginator">
    <?php
    if (mysql_num_rows($listq) == 0) {
    }
    else {
        $page = mysql_num_rows(mysql_query("SELECT * FROM servers"));
        $i = 0;
        $page = mysql_num_rows(mysql_query("SELECT * FROM servers"));
        while ($page > "0") {
            echo "<li><a href=\"content.php?include=admradio&page=servers&p=";
            if (($p / $limit) == $i) {
                echo "";
            }
            echo "$i\">$i</a></li>";
            $i++;
            $page -= $limit;
        }
    }
    ?>
</ul>
<?php if (isset($_GET['action']) && ($_GET['action'] === 'new' || $_GET['action'] === 'update')): ?>
<br/>
<h2><?php echo $messages["98"];?></h2>
<form method="post" action="content.php?include=admradio&<?php if ($_GET['action'] == "new") {
    echo 'new=server';
} elseif ($_GET['action'] == "update") {
    echo 'new=update&action=update&view=' . $_GET['view'] . '';
} ?>">
    <?php
    if ($_GET['action'] == "update") {
        $updateget_data = mysql_query("SELECT * FROM servers WHERE id='" . $_GET['view'] . "'");
        while ($updateget_var = mysql_fetch_array($updateget_data)) {
            $updateget_var_t_owner = $updateget_var['owner'];
            $updateget_var_t_adminpassword = $updateget_var['adminpassword'];
            $updateget_var_t_password = $updateget_var['password'];
            $updateget_var_t_portbase = $updateget_var['portbase'];
            $updateget_var_t_maxuser = $updateget_var['maxuser'];
            $updateget_var_t_bitrate = $updateget_var['bitrate'];
            $updateget_var_t_sitepublic = $updateget_var['sitepublic'];
            $updateget_var_t_autopid = $updateget_var['autopid'];
            $updateget_var_t_logfile = $updateget_var['logfile'];
            $updateget_var_t_screenlog = $updateget_var['screenlog'];
            $updateget_var_t_realtime = $updateget_var['realtime'];
            $updateget_var_t_showlastsongs = $updateget_var['showlastsongs'];
            $updateget_var_t_tchlog = $updateget_var['tchlog'];
            $updateget_var_t_weblog = $updateget_var['weblog'];
            $updateget_var_t_webspace = $updateget_var['webspace'];
            $updateget_var_t_w3clog = $updateget_var['w3clog'];
            $updateget_var_t_w3cenable = $updateget_var['w3cenable'];
            $updateget_var_t_srcip = $updateget_var['srcip'];
            $updateget_var_t_destip = $updateget_var['destip'];
            $updateget_var_t_yport = $updateget_var['yport'];
            $updateget_var_t_relayport = $updateget_var['relayport'];
            $updateget_var_t_namelookups = $updateget_var['namelookups'];
            $updateget_var_t_relayserver = $updateget_var['relayserver'];
            $updateget_var_t_autodumpusers = $updateget_var['autodumpusers'];
            $updateget_var_t_autodumpsourcetime = $updateget_var['autodumpsourcetime'];
            $updateget_var_t_contentdir = $updateget_var['contentdir'];
            $updateget_var_t_introfile = $updateget_var['introfile'];
            $updateget_var_t_titleformat = $updateget_var['titleformat'];
            $updateget_var_t_urlformat = $updateget_var['urlformat'];
            $updateget_var_t_publicserver = $updateget_var['publicserver'];
            $updateget_var_t_metainterval = $updateget_var['metainterval'];
            $updateget_var_t_allowrelay = $updateget_var['allowrelay'];
            $updateget_var_t_allowpublicrelay = $updateget_var['allowpublicrelay'];
        }
    }
    ?>
<fieldset>
<legend><?php echo $messages["99"];?></legend>
<div class="input_field">
    <label for="a"><?php echo $messages["100"];?></label>
    <?php
    $users_query = mysql_query("SELECT * FROM users");
    echo '<select class="formselect_loca" name="owner">';
    while ($data = mysql_fetch_array($users_query)) {
        echo "<option";
        if ($_GET['action'] == "update") {
            if ($data['username'] == $updateget_var_t_owner)
                echo " selected";
        }
        else {
            if ($data['username'] == $loginun)
                echo " selected";
        }
        echo ">" . $data['username'] . "</option>\n";
    }
    echo '</select>';
    ?>
    <span class="field_desc"><?php echo $messages["101"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["102"];?></label>
    <input class="mediumfield" name="adminpassword" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_adminpassword;
    } else {
        echo $messages["103"];
    }?>"/>
    <span class="field_desc"><?php echo $messages["104"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["105_pre"];?></label>
    <input class="mediumfield" name="password" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_password;
    } else {
        echo $messages["103"];
    }?>"/>
    <span class="field_desc"><?php echo $messages["105"];?></span>
</div>
<div class="input_field">
    <?php
    $nextportq = mysql_query("SELECT portbase FROM servers order by portbase DESC");
    if (mysql_num_rows($nextportq) >= 1)
        $nextport = mysql_result($nextportq, 0) + 2;
    else
        $nextport = "8000";
    ?>
    <label for="a"><?php echo $messages["106"];?></label>
    <input class="mediumfield" name="portbase" type="text"<?php if ($_GET['action'] == "update") {
        echo ' disabled="disabled"';
    }?> value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_portbase;
    } else {
        echo $nextport;
    }?>"/>
    <span class="field_desc"><?php echo $messages["107"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["136"];?></label>
    <input class="mediumfield" name="w3clog" type="text" readonly="readonly"
           value="<?php if ($_GET['action'] == "update") {
               $updateget_var_t_w3clog = str_replace("/var/www/virtual/sap.dashtec.de/htdocs/logs/", "", $updateget_var_t_w3clog);
               echo $updateget_var_t_w3clog;
           } else {
               echo "sc_$nextport.log";
           }?>"/>
    <span class="field_desc"><?php echo $messages["137"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["122"];?></label>
    <input class="mediumfield" readonly="readonly" name="logfile" type="text"
           value="<?php if ($_GET['action'] == "update") {
               // echo $updateget_var_t_logfile;
               $updateget_var_t_logfile = str_replace("/var/www/virtual/sap.dashtec.de/htdocs/logs/", "", $updateget_var_t_logfile);
               echo $updateget_var_t_logfile;
           } else {
               echo "sc_$nextport.log";
           }?>"/>
    <span class="field_desc"><?php echo $messages["123"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["108"];?></label>
    <input class="mediumfield" name="maxuser" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_maxuser;
    } else {
        echo "32";
    }?>"/>
    <span class="field_desc"><?php echo $messages["109"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["110"];?></label>
    <select class="formselect_loca" name="bitrate">
        <option value="320000"<?php if ($updateget_var_t_bitrate == '320000') echo " selected=\"selected\"";?>>320
            kbps
        </option>
        <option value="256000"<?php if ($updateget_var_t_bitrate == '256000') echo " selected=\"selected\"";?>>256
            kbps
        </option>
        <option value="192000"<?php if ($updateget_var_t_bitrate == '192000') echo " selected=\"selected\"";?>>192
            kbps
        </option>
        <option value="160000"<?php if ($updateget_var_t_bitrate == '160000') echo " selected=\"selected\"";?>>160
            kbps
        </option>
        <option value="128000"<?php if ($updateget_var_t_bitrate == '128000') echo " selected=\"selected\"";?>>128
            kbps
        </option>
        <option value="96000"<?php if ($updateget_var_t_bitrate == '96000') echo " selected=\"selected\"";?>>96 kbps
        </option>
        <option value="64000"<?php if ($updateget_var_t_bitrate == '80000') echo " selected=\"selected\"";?>>64 kbps
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["111"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["112"];?></label>
    <select class="formselect_loca" name="sitepublic">
        <option value="1"<?php if ($updateget_var_t_sitepublic == '1') echo " selected=\"selected\"";?>>Panel
            öffentlich
        </option>
        <option value="0"<?php if ($updateget_var_t_sitepublic == '0') echo " selected=\"selected\"";?>>Panel nicht
            öffentlich
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["113"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["114"];?></label>
    <?php
    echo '<select class="formselect_loca" name="autopid">
						<option';
    if ($updateget_var_t_autopid != "9999999") {
        echo ' selected="selected" value="' . $updateget_var_t_autopid . '"';
    }
    echo '>' . $messages["115"] . '</option>
						<option value="9999999"';
    if ($updateget_var_t_autopid == "9999999") {
        echo ' selected="selected"';
    }
    echo '>' . $messages["116"] . '</option></select>';
    ?>
    <span class="field_desc"><?php echo $messages["117"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["118"];?></label>
    <input class="mediumfield" name="webspace" type="text" value="<?php if ($_GET['action'] == "update") {
        echo ($updateget_var_t_webspace / 1024);
    } else {
        echo "20";
    }?>"/>
    <span class="field_desc"><?php echo $messages["119"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["120"];?></label>
    <input class="mediumfield" name="traffic" type="text" value="" disabled="disabled"/>
    <span class="field_desc"><?php echo $messages["121"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["124"];?></label>
    <select class="formselect_loca" name="realtime">
        <option value="1"<?php if ($updateget_var_t_realtime == '1') echo " selected=\"selected\"";?>>Streaminfos
            ausgeben
        </option>
        <option value="0"<?php if ($updateget_var_t_realtime == '0') echo " selected=\"selected\"";?>>Streaminfos aus
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["125"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["126"];?></label>
    <select class="formselect_loca" name="screenlog">
        <option value="1"<?php if ($updateget_var_t_screenlog == '1') echo " selected=\"selected\"";?>>Streaminfos
            ausgeben
        </option>
        <option value="0"<?php if ($updateget_var_t_screenlog == '0') echo " selected=\"selected\"";?>>Streaminfos aus
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["127"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["128"];?></label>
    <input class="mediumfield" name="showlastsongs" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_showlastsongs;
    } else {
        echo "10";
    }?>"/>
    <span class="field_desc"><?php echo $messages["129"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["130"];?></label>
    <select class="formselect_loca" name="tchlog">
        <option value="1"<?php if ($updateget_var_t_screenlog == '1') echo " selected=\"selected\"";?>>YP Tracks
            aufnehmen im Log
        </option>
        <option value="0"<?php if ($updateget_var_t_screenlog == '0') echo " selected=\"selected\"";?>>YP Tracks
            ignorieren
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["131"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["132"];?></label>
    <select class="formselect_loca" name="weblog">
        <option value="1"<?php if ($updateget_var_t_weblog == '1') echo " selected=\"selected\"";?>>Webaktivitäten
            geloggt
        </option>
        <option value="0"<?php if ($updateget_var_t_weblog == '0') echo " selected=\"selected\"";?>>Webaktivitäten nicht
            geloggt
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["133"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["134"];?></label>
    <select class="formselect_loca" name="w3cenable">
        <option value="1"<?php if ($updateget_var_t_w3cenable == '1') echo " selected=\"selected\"";?>>W3C Log ein
        </option>
        <option value="0"<?php if ($updateget_var_t_w3cenable == '0') echo " selected=\"selected\"";?>>W3C Log aus
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["135"];?></span>
</div>

<div class="input_field">
    <label for="a"><?php echo $messages["138"];?></label>
    <input class="mediumfield" name="srcip" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_srcip;
    } else {
        echo "ANY";
    }?>"/>
    <span class="field_desc"><?php echo $messages["139"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["140"];?></label>
    <input class="mediumfield" name="destip" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_destip;
    } else {
        echo "ANY";
    }?>"/>
    <span class="field_desc"><?php echo $messages["141"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["142"];?></label>
    <input class="mediumfield" name="yport" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_yport;
    } else {
        echo "80";
    }?>"/>
    <span class="field_desc"><?php echo $messages["143"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["144"];?></label>
    <select class="formselect_loca" name="namelookups">
        <option value="1"<?php if ($updateget_var_t_namelookups == '1') echo " selected=\"selected\"";?>>DNS erkennung
            ein
        </option>
        <option value="0"<?php if ($updateget_var_t_namelookups == '0') echo " selected=\"selected\"";?>>DNS erkennung
            aus
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["145"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["146"];?></label>
    <input class="mediumfield" name="relayport" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_relayport;
    } else {
        echo "0";
    }?>"/>
    <span class="field_desc"><?php echo $messages["147"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["148"];?></label>
    <input class="mediumfield" name="relayserver" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_relayserver;
    } else {
        echo "";
    }?>"/>
    <span class="field_desc"><?php echo $messages["149"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["150"];?></label>
    <input class="mediumfield" name="autodumpusers" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_autodumpusers;
    } else {
        echo "0";
    }?>"/>
    <span class="field_desc"><?php echo $messages["151"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["152"];?></label>
    <input class="mediumfield" name="autodumpsourcetime" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_autodumpsourcetime;
    } else {
        echo "30";
    }?>"/>
    <span class="field_desc"><?php echo $messages["153"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["154"];?></label>
    <input class="mediumfield" name="contentdir" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_contentdir;
    } else {
        echo "";
    }?>"/>
    <span class="field_desc"><?php echo $messages["155"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["156"];?></label>
    <input class="mediumfield" name="introfile" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_introfile;
    } else {
        echo "";
    }?>"/>
    <span class="field_desc"><?php echo $messages["157"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["158"];?></label>
    <input class="mediumfield" name="titleformat" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_titleformat;
    } else {
        echo "Radio Station Name: %s";
    }?>"/>
    <span class="field_desc"><?php echo $messages["159"];?></span>
</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["urlformat"];?></label>
					<input class="mediumfield" name="urlformat" type="text" value="<?php if ($_GET['action'] == "update") { echo $updateget_var_t_urlformat; } else { echo ""; }?>" />
					<span class="field_desc"><?php echo $messages["urlformat1"];?></span>
				</div> 
<div class="input_field">
    <label for="a"><?php echo $messages["160"];?></label>
    <input class="mediumfield" name="publicserver" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_publicserver;
    } else {
        echo "default";
    }?>"/>
    <span class="field_desc"><?php echo $messages["161"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["162"];?></label>
    <select class="formselect_loca" name="allowrelay">
        <option value="1"<?php if ($updateget_var_t_allowrelay == '1') echo " selected=\"selected\"";?>>Relay ein
        </option>
        <option value="0"<?php if ($updateget_var_t_allowrelay == '0') echo " selected=\"selected\"";?>>Relay aus
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["163"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["164"];?></label>
    <select class="formselect_loca" name="allowpublicrelay">
        <option value="1"<?php if ($updateget_var_t_allowpublicrelay == '1') echo " selected=\"selected\"";?>>Server
            öffentlich
        </option>
        <option value="0"<?php if ($updateget_var_t_allowpublicrelay == '0') echo " selected=\"selected\"";?>>Server
            Privat
        </option>
    </select>
    <span class="field_desc"><?php echo $messages["165"];?></span>
</div>
<div class="input_field">
    <label for="a"><?php echo $messages["166"];?></label>
    <input class="mediumfield" name="metainterval" type="text" value="<?php if ($_GET['action'] == "update") {
        echo $updateget_var_t_metainterval;
    } else {
        echo "32768";
    }?>"/>
    <span class="field_desc"><?php echo $messages["167"];?></span>
</div>
<input class="submit" type="submit" value="<?php echo $messages["168"];?>"/>
</fieldset>
</form>
    <?php endif ?>
</div>