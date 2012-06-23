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
//	djcrackhome & WallCity-Server Coop.
//	GNU License 
//	http://www.stremerspanel.com
///////////////////////////////////////////////
//	./pages/main_bottom.php
//	

if (stripos($_SERVER['PHP_SELF'], 'content.php') === false) {
    die ("You can't access this file directly...");
}

?>
<h2><?php echo $messages["358"];?></h2>
<div class="main_top_menu">
    <div class="main_short">

        <?php if ($user_level == 'dj'){ ?>
        <div class="_shortcut_auto">
            <span><a href="content.php?include=autodj">Source Kick</a></span>
        </div>

        <?php }

        if (isset($_POST['kickIt'])){

        }



         if ($user_level != 'dj'){
         if ($setting['os']=="linux") { ?>
        <div class="_shortcut_auto">
            <span><a href="content.php?include=autodj"><?php echo $messages["359"];?></a></span>
        </div>
        <?php } ?>
        <div class="_shortcut_user">
            <span><a href="content.php?include=account"><?php echo $messages["360"];?></a></span>
        </div>
        <div class="_shortcut_cont">
            <span><a href="content.php?include=contact"><?php echo $messages["361"];?></a></span>
        </div>
        <div class="_shortcut_main">
            <span><a href="content.php?include=server"><?php echo $messages["362"];?></a></span>
        </div>
        <?php if ($setting['os']=="linux") { ?>
        <div class="_shortcut_mp3u">
            <span><a href="content.php?include=music"><?php echo $messages["363"];?></a></span>
        </div>
        <?php } ?>
        <?php
        // THE FOLLOWING CODE IS WRITTEN IN THIS 'CHAOS' ON PURPOSE =)
        ?>
        <div class="_shortcut_sett<?php if ($user_level!="Super Administrator") { echo "_adm"; }?>">
            <span><?php if ($user_level=="Super Administrator") { echo '<a href="content.php?include=admserver">'; }?><?php if ($user_level!="Super Administrator") { echo "<p>("; }?><?php echo $messages["364"];?><?php if ($user_level!="Super Administrator") { echo ")</p>"; }?><?php if ($user_level=="Super Administrator") { echo '</a>'; }?></span>
        </div>
        <div class="_shortcut_radi<?php if ($user_level!="Super Administrator") { echo "_adm"; }?>">
            <span><?php if ($user_level=="Super Administrator") { echo '<a href="content.php?include=admradio">'; }?><?php if ($user_level!="Super Administrator") { echo "<p>("; }?><?php echo $messages["365"];?><?php if ($user_level!="Super Administrator") { echo ")</p>"; }?><?php if ($user_level=="Super Administrator") { echo '</a>'; }?></span>
        </div>
        <div class="_shortcut_cust<?php if ($user_level!="Super Administrator") { echo "_adm"; }?>">
            <span><?php if ($user_level=="Super Administrator") { echo '<a href="content.php?include=admuser">'; }?><?php if ($user_level!="Super Administrator") { echo "<p>("; }?><?php echo $messages["366"];?><?php if ($user_level!="Super Administrator") { echo ")</p>"; }?><?php if ($user_level=="Super Administrator") { echo '</a>'; }?></span>
        </div>




  <?php } ?>
    </div>
    <aside class="main_right">
        <h2><?php echo $messages["367"];?></h2>
        <p><?php echo $messages["368"];?></p>
        <p><?php echo $messages["369"];?></p>
    </aside>
    <div class="clear"> </div>
</div>
<div class="listing_menu">
    <div class="clear"> </div>
</div>
<?php
if ($user_level=="Super Administrator")	{
    echo "<h2>".$messages["370"]."</h2>
				<table cellspacing=\"0\" cellpadding=\"0\">
				<tbody>";
    $noticesq = mysql_query("SELECT * FROM notices");
    if (mysql_num_rows($noticesq)==0) {
        echo "<tr class=\"alt\">
					<td colspan=\"3\">".$messages["371"]."</td>
					</tr>";
    }
    else {
        while($data = mysql_fetch_array($noticesq)) {
            echo "<tr>
						<td>".$data['username']."</td>
						<td>".$data['reason']."</td>
						<td><a class=\"delete\" href=\"?action=remove&delmessid=".$data['id']."\">".$messages["372"]."</a><a href=\"messages.php?id=".$data['id']."\" class=\"nyroModal selector\">".$messages["373"]."</a></td>
						</tr>";
        }
    }
    echo "</tbody>
				</table>";
}
?>