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

defined('entries_per_page') || define('entries_per_page',250);
if (!isset($_GET['filecount']) or !is_numeric($_GET['filecount'])) $offset = 1;
else $offset = $_GET['filecount'];
if ($offset == 1) {
	$listing_start = $offset * entries_per_page - entries_per_page;
}
else {
	$listing_start = $offset * entries_per_page - entries_per_page + 3;
}					
$listing_end = $offset * entries_per_page + 2;
$dirlisting = @scandir("./temp/".$port."/playlist") or $errors[] = "";
if (!isset($dirlisting[$listing_start]))
	$errors[] = "";
?>
		<h2><?php echo $messages["391"];?> <?php echo $port;?></h2>
		<div class="tool_top_menu">
			<div class="main_shorttool"><?php echo $messages["392"];?></div>
			<div class="main_righttool">
				<h2><?php echo $messages["393"];?></h2>
				<p><?php echo $messages["394"];?></p>
				<p>&nbsp;</p>
			</div>
		</div>
		<table cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th><?php echo $messages["395"];?></th>
					<th><?php echo $messages["396"];?></th>
					<th><a class="selector" href="content.php?include=playlist&portbase=<?php echo $port;?>&indiv=1&listname=<?php echo base64_encode("new playlist.lst")?>"><?php echo $messages["397"];?></a></th>
				</tr>
			<tbody>
				<?php
				for($i=$listing_start;$i<=$listing_end;$i++) {
					if (!isset($dirlisting[$i])) {
						continue;
					}

					if (($dirlisting[$i]!=".") and ($dirlisting[$i]!="..") and ($dirlisting[$i]!="")) {
						echo "<tr>
							<td>$dirlisting[$i]</td>
							<td>".round((filesize("./temp/".$port."/playlist/".$dirlisting[$i])/1024), 2)." KB (".round((filesize("./temp/".$port."/playlist/".$dirlisting[$i])/1024/1024), 2)." MB)</td>
							<td><a class=\"delete\" href=\"content.php?include=playlist&portbase=".$port."&indiv=0&listname=".base64_encode($dirlisting[$i])."&delete=1\">".$messages["398"]."</a><a class=\"edit\" href=\"content.php?include=playlist&portbase=".$port."&indiv=0&delete=0&listname=".base64_encode($dirlisting[$i])."\">".$messages["399"]."</a><a class=\"selector\" href=\"content.php?include=playlist&portbase=".$port."&indiv=1&listname=".base64_encode($dirlisting[$i])."\">".$messages["400"]."</a></td>
							</tr>";
					}
				}
				?>
			</tbody>
		</table>
		<?php if (!isset($_GET['playlist']) && (isset($_GET['indiv']) && $_GET['indiv'] == "1")) { ?>
		<br />
		<h2><?php echo $messages["401"];?></h2>
		<form action="content.php?include=playlist&portbase=<?php echo $port;?>&indiv=1&listname=<?php echo $_GET['listname']; ?><?php if ($_GET['listname'] == "bmV3IHBsYXlsaXN0LmxzdA==") { echo "&new=1"; }?>" method=post name=treeform onSubmit=setValue()>
			<input name=arv  type=hidden>
			<div class="input_field">
				<label for="a"><?php echo $messages["402"];?></label>
				<input class="mediumfield" name="playlistformname" type="text" value="<?php if ($_GET['listname'] == "bmV3IHBsYXlsaXN0LmxzdA==") { echo "demoplaylistname"; } else { echo base64_decode($_GET['listname']); }?>" <?php if ($_GET['listname'] !== "bmV3IHBsYXlsaXN0LmxzdA==") { echo "disabled=\"disabled\""; }?> />
			</div>
			<script src="./js/dhtmlxcommon.js"></script>
			<script src="./js/dhtmlxtree.js"></script>
            <div id="playlist_table_id">
                <div id="playlist_left">
                    <h4>Ihr Dateibrowser</b></h4>
                    <div id="playlist_files"></div>
                </div>
                <div id="playlist_right">
                    <h4>Playliste: <b><?php echo base64_decode($_GET['listname']); ?></b></h4>
                    <div id="playlist_available"></div>
                </div>
            </div>
            <script>
                tree=new dhtmlXTreeObject("playlist_files","100%","100%",0);
                tree.enableDragAndDrop(true);
                tree.label = "tree1";
            				tree.loadXML("content.php?portbase=<?php echo $port;?>&playlist=left");
                            tree.enableTreeLines(false);
                            tree.enableTreeImages(false);
                            tree.enableImageDrag(false);
                            tree.enableActiveImages(false);
                            tree2=new dhtmlXTreeObject("playlist_available","100%","100%",0);
                            tree2.enableDragAndDrop(true);
            				tree2.loadXML("content.php?portbase=<?php echo $port;?>&playlist=right&listname=<?php echo $_GET['listname']; ?>");
                            tree2.enabledpcpy(true);
                            tree2.label = "tree2";
                            tree2.enableTreeLines(false);
                            tree2.enableTreeImages(false);
                            tree2.enableImageDrag(false);
                            tree2.enableActiveImages(false);
            			</script>
			<input class="submit" type="submit" value="<?php echo $messages["405"];?>" />
			<input type="button" name="clear" onClick="clearPlaylist()" class="submit" value="<?php echo $messages["406"];?>" />
		</form>
        <?php }?>