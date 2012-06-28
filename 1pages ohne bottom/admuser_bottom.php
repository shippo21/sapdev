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
<h2 xmlns="http://www.w3.org/1999/html"><?php echo $messages["228"];?> <?php if (isset($port)){echo $port;} ?></h2>
		<div class="tool_top_menu">
			<div class="main_shorttool"><?php echo $messages["229"];?></div>
			<div class="main_righttool">
				<h2><?php echo $messages["230"];?></h2>
				<p><?php echo $messages["231"];?></p>
				<p>&nbsp;</p>
			</div>
		</div>
		<table cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th><?php echo $messages["232"];?></th>
					<th><?php echo $messages["233"];?></th>
					<th><a class="selector" href="content.php?include=admuser&action=newuser"><?php echo $messages["234"];?></a></th>
				</tr>
			</thead>
			<tbody>
					<?php
                    // If User = Super Administrator Show all USER!
                    if ( $_SESSION['user_level'] == 'Super Administrator'){
                        $get_users = mysql_query("SELECT * FROM users order by id ASC limit $p,$limit");
                        while($data = mysql_fetch_array($get_users)) {
                            $get_servers = mysql_query("SELECT * FROM servers WHERE owner='".$data['username']."'");
                            echo "<tr>
							<td>".$data['username']."</td>
							<td>".mysql_num_rows($get_servers)." ".$messages["237"]."</td>
							<td><a class=\"delete\" href=\"content.php?include=admuser&action=edit&id=".$data['id']."&function=delete\">".$messages["235"]."</a><a class=\"edit\" href=\"content.php?include=admuser&action=edit&id=".$data['id']."\">".$messages["236"]."</a></td>
							</tr>";
                        }
                    }else{
                        $get_users = mysql_query("SELECT * FROM users WHERE dj_of_user = '".$_SESSION['user_tb_id']."' order by id ASC limit $p,$limit");
                        while($data = mysql_fetch_array($get_users)) {
                            $get_servers = mysql_query("SELECT * FROM servers WHERE owner='".$data['username']."'");
                            echo "<tr>
							<td>".$data['username']."</td>
							<td>".mysql_num_rows($get_servers)." ".$messages["237"]."</td>
							<td><a class=\"delete\" href=\"content.php?include=admuser&action=edit&id=".$data['id']."&function=delete\">".$messages["235"]."</a><a class=\"edit\" href=\"content.php?include=admuser&action=edit&id=".$data['id']."\">".$messages["236"]."</a></td>
							</tr>";
                        }
                    }

					?>
			</tbody>
		</table>
		<ul class="paginator">
			<?php
			$i = 0;
			$page = mysql_num_rows(mysql_query("SELECT * FROM users"));
			while($page > "0") {
				echo "<li><a href=\"content.php?include=admuser&?p=";
				if (($p / $limit) == $i){
					echo "";
				}
				echo "$i\">$i</a></li>";
				$i++;
				$page -= $limit;
			}
			?>
		</ul>
        <?php 
		if ((isset($_GET['action']) && $_GET['action'] == "edit" && $user_check!=="1") || (isset($_GET['action']) && $_GET['action'] == "newuser")) {
			if ($_GET['action'] !== "newuser") {
				$userq = mysql_query("SELECT * FROM users WHERE id='".$_GET['id']."'");
				foreach(mysql_fetch_array($userq) as $key => $pref) {
					if (!is_numeric($key)) {
						if ($pref != "") {
							$userdata[$key] = $pref;
						}
						else {
							$userdata[$key] = "none";
						}
					}
				}
			}

		?>
		<br />

		<h2><?php if ($_GET['action'] == "newuser") { echo $messages["238"]; } else { echo $messages["239"]; }?></h2>
		<form method="post" action="content.php?include=admuser&action=<?php if ($_GET['action'] == "newuser") { echo "newuser"; } else { echo "edit"; }?>&function=update<?php if ($_GET['action'] == "edit") { echo "&id=".$_GET['id']; }?>">
			<fieldset>
				<legend><?php echo $messages["240"];?></legend>
				<div class="input_field">
					<label for="a"><?php echo $messages["241"];?></label>
					<input class="mediumfield" name="eusername" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['username']."\" disabled=\"disabled\""; }?>" />
					<span class="field_desc"><?php echo $messages["242"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["243"];?></label>
					<input class="mediumfield" name="euser_password" type="password" value="<?php if ($_GET['action'] == "edit") { echo $userdata['user_password']; }?>" />
					<span class="field_desc"><?php echo $messages["244"];?></span>
				</div>
				<?php if ($_GET['action'] == "newuser") {?>
				<div class="input_field">
					<label for="a"><?php echo $messages["245"];?></label>
					<input class="mediumfield" name="ceuser_password" type="password" value="" />
					<span class="field_desc"><?php echo $messages["246"];?></span>
				</div>
				<?php } ?>
				<div class="input_field">
					<label for="a"><?php echo $messages["265"];?></label>
					<?php
					if ($_GET['action'] == "newuser") {

                       echo '<select class="formselect_loca" name="euser_level">';
                        if ($_SESSION['user_level'] == 'Super Administrator'){
                        echo'
						<option value="Super Administrator">'.$messages["247"].'</option>
						<option selected="selected" value="User">'.$messages["248"].'</option>';
                        };
                        echo'
						<option selected="selected" value="dj">'.$messages["add247"].'</option>
						</select>';
					}
					else {
                        echo '<select class="formselect_loca" name="euser_level">';

                        // HIDE Super Admin und User if Login = User
                        if ($_SESSION['user_level'] == 'Super Administrator'){
                        ?>
                         <option value="Super Administrator" <? if($userdata['user_level'] == 'Super Adminsitrator') echo'selected="selected"' ?>>Super Adminstrator</option>
                         <option value="User" <? if($userdata['user_level'] == 'User') echo'selected="selected"'  ?>>User</option>
                        <?php }else{};?>

                        <option value="dj" <? if($userdata['user_level'] == 'dj') echo'selected="selected"' ?>>Dj-Moderator</option>
                        <option value="banned" <? if($userdata['user_level'] == 'banned') echo'selected="selected"' ?>>Disable</option>
                        </select>
                        <span class="field_desc">DJ bitte einem Benutzer zuordnen!</span>
                        <?php
                    }

                    if ($_GET['action'] != "newuser" && $userdata['user_level'] != 'User'){
					?>
                <div class="input_field">
                    <label for="a">Benutzerzuordnung</label>
                    <select class="formselect_loca" name="seluser">
                        <option>Bitte Benutzer wählen!</option>
                        <?php
                    $userlist= mysql_query("SELECT * FROM users WHERE user_level ='User'");
                    while($lsituser = mysql_fetch_object($userlist)) {
                        echo "<option value=".$lsituser->id.">".$lsituser->username."</option>";
                    }

                    ?>
                    </select>
                    <span class="field_desc">Zu welchem Benutzer gehört der DJ</span>
                </div>

                    <?php } ?>


				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["250"];?></label>
					<input class="mediumfield" name="econtact_number" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['contact_number']; }?>" />
					<span class="field_desc"><?php echo $messages["251"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["252"];?></label>
					<input class="mediumfield" name="emobile_number" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['mobile_number']; }?>" />
					<span class="field_desc"><?php echo $messages["253"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["254"];?></label>
					<input class="mediumfield" name="euser_email" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['user_email']; }?>" />
					<span class="field_desc"><?php echo $messages["255"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["256"];?></label>
					<input class="mediumfield" name="ename" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['name']; }?>" />
					<span class="field_desc"><?php echo $messages["257"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["258"];?></label>
					<input class="mediumfield" name="esurname" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['surname']; }?>" />
					<span class="field_desc"><?php echo $messages["259"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["260"];?></label>
					<input class="mediumfield" name="eage" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['age']; }?>" />
					<span class="field_desc"><?php echo $messages["261"];?></span>
				</div>
				<div class="input_field">
					<label for="a"><?php echo $messages["262"];?></label>
					<input class="mediumfield" name="eaccount_notes" type="text" value="<?php if ($_GET['action'] == "edit") { echo $userdata['account_notes']; }?>" />
					<span class="field_desc"><?php echo $messages["263"];?></span>
				</div>
				<input class="submit" type="submit" value="<?php echo $messages["264"];?>" />
			</fieldset>
		</form>
		<?php }?>