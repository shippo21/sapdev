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
<h2><?php echo $messages["adm1"];?></h2>
<div class="contact_top_menu">
    <div class="tool_top_menu">
        <div class="main_shorttool"><?php echo $messages["adm2"];?>
        </div>
        <div class="main_righttool">
            <h2><?php echo $messages["adm3"];?></h2>
            <p><?php echo $messages["adm4"];?></p>
        </div>
    </div>

    <form method="post" action="content.php?include=admnews" id="contactform">
        <fieldset>
            <legend><?php echo $messages["adm5"];?></legend>

            <div class="input_field">
                <label for="b"><?php echo $messages["adm6"];?>
                </label>
                <input type="text" name="titel" class="mediumfield" value=""/>
                <?php
                if (isset($formerror)) {
                    if ($formerror == "reason") {
                       echo "<span class=\"validate_error\">".$messages["adm7"]."</span>";
                    }
                    else {

                    }
                }
                ?>
            </div>
            <div class="input_field">
                <textarea cols="90" name="message" rows="6" class="textbox" value=""></textarea>
                <?php
                if (isset($formerror)) {
                    if ($formerror == "message") {
                        echo "<span class=\"validate_error\">".$messages["adm7"]."</span>";
                    }
                    else {

                    }
                }
                ?>
            </div>
            <input class="submit" type="submit" name="submit" value="<?php echo $messages["353"];?>" />
            <input class="submit" type="reset" value="<?php echo $messages["354"];?>" />
        </fieldset>
    </form>

    <fieldset>
        <form method="post" action="content.php?include=admnews">
            <table>
                <?php
                $editNews = mysql_query("SELECT id,titel FROM news ");
                while($row = mysql_fetch_object($editNews))
                {
                    echo "<tr>";
                    echo '<td><input type="checkbox" name="chboxid" value="'. $row->id . '" </td>';
                    echo "<td>",$row->titel,"</td>";
                    echo "<td>",$row->text,"</td>";
                    echo '<td></td>';
                    echo "</tr>";
                }
                echo "</table>";

                ?>
                <input class="submit" type="submit" name="delmes" value="Löschen">


        </form>
    </fieldset>

</div>