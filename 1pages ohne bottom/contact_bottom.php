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
		<h2><?php echo $messages["338"];?></h2>
		<div class="contact_top_menu">
			<div class="tool_top_menu">
				<div class="main_shorttool"><?php echo $messages["339"];?></div>
				<div class="main_righttool">
					<h2><?php echo $messages["340"];?></h2>
					<p><?php echo $messages["341"];?></p>
					<p>&nbsp;</p>
				</div>
			</div>
			<form method="post" action="content.php?include=contact" id="contactform">
				<fieldset>
					<legend><?php echo $messages["342"];?></legend>
					<div class="input_field">
						<label for="a"><?php echo $messages["343"];?></label>
						<input class="mediumfield" name="c2" type="text" value="<?php echo $loginun;?>" disabled="disabled" />
						<span class="field_desc"><?php echo $messages["344"];?></span>
					</div>
					<div class="input_field">
						<label for="b"><?php echo $messages["345"];?></label>
						<input type="text" name="email" class="mediumfield" value="<?php if (isset($formerror)) { echo $_POST['email']; }?>"/>
						<?php
						if (isset($formerror)) {
							if ($formerror == "email") {
								echo "<span class=\"validate_error\">".$messages["346"]."</span>";
							}
							else {
								echo "<span class=\"validate_success\">".$messages["347"]."</span>";
							}
						}
						?>
					</div>
					<div class="input_field">
						<label for="c"><?php echo $messages["348"];?></label>
						<input type="text" name="reason" class="mediumfield" value="<?php if (isset($formerror)) { echo $_POST['reason']; }?>" />
						<?php
						if (isset($formerror)) {
							if ($formerror == "reason") {
								echo "<span class=\"validate_error\">".$messages["349"]."</span>";
							}
							else {
								echo "<span class=\"validate_success\">".$messages["350"]."</span>";
							}
						}
						?>
					</div>
					<div class="input_field">
						<textarea cols="90" name="message" rows="6" class="textbox" value=""><?php if (isset($formerror)) { echo $_POST['message']; }?></textarea>
						<?php
						if (isset($formerror)) {
							if ($formerror == "message") {
								echo "<span class=\"validate_error\">".$messages["351"]."</span>";
							}
							else {
								echo "<span class=\"validate_success\">".$messages["352"]."</span>";
							}
						}
						?>
					</div>
					<input class="submit" type="submit" name="submit" value="<?php echo $messages["353"];?>" />
					<input class="submit" type="reset" value="<?php echo $messages["354"];?>" />
				</fieldset>
			</form>
		</div>