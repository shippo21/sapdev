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
		<h2><?php echo $messages["188"];?> <?PHP echo ($_SERVER['SERVER_ADDR']);?></h2>
		<div class="contact_top_menu">
			<div class="tool_top_menu">
				<div class="main_shorttool"><?php echo $messages["189"];?></div>
				<div class="main_righttool">
					<h2><?php echo $messages["190"];?></h2>
					<p><?php echo $messages["191"];?></p>
				</div>
			</div>
			<form method="post" action="content.php?include=admserver&action=savesettings">	
				<fieldset>
					<legend><?php echo $messages["192"];?></legend>
					<div class="input_field">
						<label for="a"><?php echo $messages["193"];?></label>
						<input class="mediumfield" name="host_add" type="text" value="<?php echo $setting['host_add'];?>" />
						<span class="field_desc"><?php echo $messages["194"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["195"];?></label>
						<input class="mediumfield" name="os" type="text" value="<?php echo $setting['os'];?>" disabled="disabled" />
						<span class="field_desc"><?php echo $messages["196"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["197"];?></label>
						<input class="mediumfield" name="ssh_user" type="text" value="<?php echo base64_decode($setting['ssh_user']);?>" />
						<span class="field_desc"><?php echo $messages["198"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["199"];?></label>
						<input class="mediumfield" name="ssh_pass" type="password" value="***" />
						<span class="field_desc"><?php echo $messages["200"];?></span>
					</div>
                    <div class="input_field">
                    <label for="a"><?php echo$messages["admsrv_5"];?></label>
                    <select class="formselect_loca" name="rootsucess">
                        <option value="ssh2"<?php if ($setting['shellset']=='ssh') echo " selected=\"selected\"";?>><?php echo$messages["admsrv_6"];?></option>
                        <option value="shellexec"<?php if ($setting['shellset']=='shellexec') echo " selected=\"selected\"";?>><?php echo$messages["admsrv_7"];?></option>
                    </select>
                    <span class="field_desc"><?php echo$messages["admsrv_8"];?></span>
                    </div>
					<div class="input_field">
						<label for="a"><?php echo $messages["201"];?></label>
						<input class="mediumfield" name="ssh_port" type="text" value="<?php echo $setting['ssh_port'];?>" />
						<span class="field_desc"><?php echo $messages["202"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["203"];?></label>
						<input class="mediumfield" name="dir_to_cpanel" type="text" value="<?php echo $setting['dir_to_cpanel'];?>" />
						<span class="field_desc"><?php echo $messages["204"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["205"];?></label>
						<input class="mediumfield" name="title" type="text" value="<?php echo $setting['title'];?>" />
						<span class="field_desc"><?php echo $messages["206"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["207"];?></label>
						<input class="mediumfield" name="slogan" type="text" value="<?php echo $setting['slogan'];?>" />
						<span class="field_desc"><?php echo $messages["208"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["211"];?></label>
						<?php
						echo '<select class="formselect_loca" name="language">';
						define('entries_per_page',100);
						if (!isset($_GET['filecount']) or !is_numeric($_GET['filecount'])) $offset = 1;
						else $offset = $_GET['filecount'];
						if ($offset == 1) {
							$listing_start = $offset * entries_per_page - entries_per_page;
						}
						else {
							$listing_start = $offset * entries_per_page - entries_per_page + 3;
						}
						$listing_end = $offset * entries_per_page + 2;
						$dirlisting = @scandir("./pages/messages/") or $errors[] = "";
						if (!isset($dirlisting[$listing_start])) $errors[] = "";
						for($i=$listing_start;$i<=$listing_end;$i++) {
							if (($dirlisting[$i]!=".") and ($dirlisting[$i]!="..") and ($dirlisting[$i]!="")) {
								echo "<option";
								if (substr($dirlisting[$i], 0, -4) == $setting['language']) {
									echo " selected=\"selected\"";
								}
								echo " value=\"".substr($dirlisting[$i], 0, -4)."\">".ucfirst(substr($dirlisting[$i], 0, -4))."</option>";
							}
						}
						echo '</select>';?>
						<span class="field_desc"><?php echo $messages["212"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["213"];?></label>
						<input class="mediumfield" name="php_exe" type="text" value="<?php echo $setting['php_exe'];?>" />
						<span class="field_desc"><?php echo $messages["214"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["215"];?></label>

                        <select class="formselect_loca" name="update_check">
                            <option value="1"<?php if ($setting['update_check']=='1') echo " selected=\"selected\"";?>><?php echo $messages["dd9"]; ?></option>
                            <option value="0"<?php if ($setting['update_check']=='0') echo " selected=\"selected\"";?>><?php echo $messages["dd10"]; ?></option>
                        </select>
						<span class="field_desc"><?php echo $messages["216"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["217"];?></label>
                        <select class="formselect_loca" name="adj_config">
                            <option value="1"<?php if ($setting['adj_config']=='1') echo " selected=\"selected\"";?>><?php echo $messages["dd11"]; ?></option>
                            <option value="0"<?php if ($setting['adj_config']=='0') echo " selected=\"selected\"";?>><?php echo $messages["dd12"]; ?></option>
                        </select>
						<span class="field_desc"><?php echo $messages["218"];?></span>
					</div>
					<div class="input_field">
                        <label for="a"><?php echo $messages["219"];?></label>
                        <select class="formselect_loca" name="scs_config">
                            <option value="1"<?php if ($setting['scs_config']=='1') echo " selected=\"selected\"";?>><?php echo $messages["dd13"]; ?></option>
                            <option value="0"<?php if ($setting['scs_config']=='0') echo " selected=\"selected\"";?>><?php echo $messages["dd14"]; ?></option>
                        </select>
						<span class="field_desc"><?php echo $messages["220"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["223"];?></label>
						<input class="mediumfield" name="display_limit" type="text" value="<?php echo $setting['display_limit'];?>" />
						<span class="field_desc"><?php echo $messages["224"];?></span>
					</div>
                    <div class="input_field">
                    <label for="a"><?php echo $messages["221"];?></label>
                    <select class="formselect_loca" name="login_captcha">
                        <option value="1"<?php if ($setting['login_captcha']=='1') echo " selected=\"selected\"";?>><?php echo $messages["dd15"]; ?></option>
                        <option value="0"<?php if ($setting['login_captcha']=='0') echo " selected=\"selected\"";?>><?php echo $messages["dd16"]; ?></option>
                    </select>
                    <span class="field_desc"><?php echo $messages["220"];?></span>
                    </div>

                    <div class="input_field">
                        <label for="a"><?php echo$messages["admsrv_9"];?></label>
                        <select class="formselect_loca" name="servernews">
                            <option value="1"<?php if ($setting['server_news']=='1') echo " selected=\"selected\"";?>><?php echo$messages["admsrv_10"];?></option>
                            <option value="0"<?php if ($setting['server_news']=='0') echo " selected=\"selected\"";?>><?php echo$messages["admsrv_11"];?></option>
                        </select>
                        <span class="field_desc"><?php echo$messages["admsrv_12"];?></span>
                    </div>

                    <input class="submit" type="submit" value="<?php echo $messages["225"];?>" />


				</fieldset>
			</form>
		</div>