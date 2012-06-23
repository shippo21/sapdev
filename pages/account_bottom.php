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

$userq = mysql_query("SELECT * FROM users WHERE md5_hash='$hash'");
foreach(mysql_fetch_array($userq) as $key => $pref) {
	if (!is_numeric($key)) {
		if ($pref != "")
			$userdata[$key] = $pref;
		else
			$userdata[$key] = "";
	}
}
?>
		<h2><?php echo $messages["54"];?></h2>
		<div class="contact_top_menu">
			<div class="tool_top_menu">
				<div class="main_shorttool"><?php echo $messages["55"];?></div>
				<div class="main_righttool">
					<h2><?php echo $messages["56"];?></h2>
					<p><?php echo $messages["57"];?></p>
					<p>&nbsp;</p>
				</div>
			</div>
			<form method="post" action="content.php?include=account">
				<fieldset>
					<legend><?php echo $messages["58"];?></legend>
					<div class="input_field">
						<label for="a"><?php echo $messages["59"];?></label>
						<input class="mediumfield" name="username" type="text" value="<?php echo $userdata['username'];?>" disabled="disabled" />
						<span class="field_desc"><?php echo $messages["60"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["61"];?></label>
						<input class="mediumfield" name="u_user_password" type="password" value="<?php echo $userdata['user_password'];?>" />
						<span class="field_desc"><?php echo $messages["62"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["63"];?></label>
						<input class="mediumfield" name="u_cuser_password" type="password" value="" />
						<span class="field_desc"><?php echo $messages["64"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["65"];?></label>
						<input class="mediumfield" name="level" type="text" value="<?php echo $userdata['user_level'];?>" disabled="disabled"/>
						<span class="field_desc"><?php echo $messages["66"];?></span>
					</div>
					<div class="input_field">
						<label for="a"><?php echo $messages["67"];?></label>
						<input class="mediumfield" name="u_name" type="text" value="<?php echo $userdata['name'];?>" />
						<span class="field_desc"><?php echo $messages["68"];?></span>
					</div>
					<div class="input_field">
						<label for="a6"><?php echo $messages["69"];?></label>
						<input class="mediumfield" name="u_surname" type="text" value="<?php echo $userdata['surname'];?>" />
						<span class="field_desc"><?php echo $messages["70"];?></span>
					</div>
					<div class="input_field">
						<label for="a7"><?php echo $messages["71"];?></label>
						<input class="mediumfield" name="u_user_email" type="text" value="<?php echo $userdata['user_email'];?>" />
						<span class="field_desc"><?php echo $messages["72"];?></span>
					</div>
					<div class="input_field">
						<label for="a8"><?php echo $messages["73"];?></label>
						<input class="mediumfield" name="u_contact_number" type="text" value="<?php echo $userdata['contact_number'];?>" />
						<span class="field_desc"><?php echo $messages["74"];?></span>
					</div>
					<div class="input_field">
						<label for="a10"><?php echo $messages["75"];?></label>
						<input class="mediumfield" name="u_mobile_number" type="text" value="<?php echo $userdata['mobile_number'];?>" />
						<span class="field_desc"><?php echo $messages["76"];?></span>
					</div>
					<div class="input_field">
						<label for="a11"><?php echo $messages["77"];?></label>
						<input class="mediumfield" name="u_age" type="text" value="<?php echo $userdata['age'];?>" />
						<span class="field_desc"><?php echo $messages["78"];?></span>
					</div>
					<div class="input_field">
						<label for="a12"><?php echo $messages["79"];?></label>
						<input class="mediumfield" name="admin" type="text" value="<?php echo nl2br($userdata['account_notes']);?>" />
						<span class="field_desc"><?php echo $messages["80"];?></span>
					</div>
					<input class="submit" type="submit" name="submit" value="<?php echo $messages["81"];?>" />
				</fieldset>
			</form>
		</div>