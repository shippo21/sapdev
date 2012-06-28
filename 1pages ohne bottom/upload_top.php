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

$settingsq = mysql_query("SELECT * FROM settings WHERE id='0'") or die($messages["g5"]);
foreach(mysql_fetch_array($settingsq) as $key => $pref) {
    if (!is_numeric($key)) {
        $setting[$key] = stripslashes($pref);
    }
}

if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {

    if (!isset($_GET['portbase'])) {
        header('Location: content.php?include=music&error=port');
        die ();
    }
    else {
        $port = $_GET['portbase'];
    }
    $port = strip_tags(str_replace('/', '', $port));
    $selectowner = mysql_query("SELECT * FROM servers WHERE portbase='".$port."' AND owner='".$_SESSION['username']."'");
    if (mysql_num_rows($selectowner)==1) {
        $port=$port;
    }
    else {
        header('Location: content.php?include=music&error=access');
        die ();
    }
    $database_space = mysql_query("SELECT * FROM servers WHERE portbase='".$port."'") or die ();
    $data = mysql_fetch_array($database_space);
    if (file_exists("./uploads/".$port."/")) {
        $port_use = $port;
    }
    else {
        $old = umask(0);
        mkdir("./uploads/".$port."", 0777);
        umask($old);
        $port_use = $port;
        if ($old != umask()) {
            header('Location: content.php?include=music&error=dir');
            die ();
        }
    }

    $filename = basename($_FILES['uploaded_file']['name']);
    $ext = substr($filename, strrpos($filename, '.') + 1);
    $file_size_m = str_replace("M","",ini_get("upload_max_filesize"));

    // Eines von Sebastian seinen LieblingsCode-Salat teilen :-D
    /*
    if ($_FILES["uploaded_file"]["size"] >= (($data['webspace']*1024)-$actual_dir_size*1024)) {
		$errors[] = "<h2>".$messages["549"]."</h2>";
	}
	else {
	if (
    	($ext == "mp3") && 
    	($_FILES["uploaded_file"]["size"] < ($file_size_m*1024)*1024) &&
    	(
        	($_FILES["uploaded_file"]["type"] == "audio/mpeg") ||
        	($_FILES["uploaded_file"]["type"] == "audio/mpeg3") || 
			($_FILES["uploaded_file"]["type"] == "audio/ext") || 
        	($_FILES["uploaded_file"]["type"] == "audio/x-mpeg-3") ||
			($_FILES["uploaded_file"]["type"] == "application/octet-stream") ||
			($_FILES["uploaded_file"]["type"] == "application/force-download") ||
			($_FILES["uploaded_file"]["type"] == "application/octetstream") ||
			($_FILES["uploaded_file"]["type"] == "application/x-download")
    	)
	) 
	{
		$newname = "./uploads/".$port_use."/$filename";
		if (!file_exists($newname)) {
			if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$newname))) {
				$correc[] = "<h2>".$messages["550"]."</h2>";
				$playlistupdate = "1";
			}
			else {
				$errors[] = "<h2>".$messages["551"]."</h2>";
			}
		} 
		else {
			$errors[] = "<h2>".$messages["552"]."</h2>";
		}
	}
	else {
		$errors[] = "<h2>".$messages["553"]."</h2>";
	}
	}
            */




}
else {
    if (!isset($_GET['portbase'])) {
        header('Location: content.php?include=music&error=port');
        die ();
    }
    else {
        $port=$_GET['portbase'];
    }
    $selectowner = mysql_query("SELECT * FROM servers WHERE portbase='".$port."' AND owner='".$_SESSION['username']."'");
    if (mysql_num_rows($selectowner)==1) {
        $port=$port;
    }
    else {
        header('Location: content.php?include=music&error=access');
        die ();
    }
    if (isset($_GET['delete'])) {
        $deletefiledecoded = base64_decode($_GET['delete']);
        if (file_exists("./uploads/".$port."/".$deletefiledecoded."")) {
            unlink("./uploads/".$port."/".$deletefiledecoded."");
            $correc[] = "<h2>".$messages["556"]."</h2>";
            $playlistupdate = "2";
        }
        else {
            $errors[] = "<h2>".$messages["557"]."</h2>";
        }
    }
    if (isset($_GET['download'])) {
        $downloadiddecode=base64_decode($_GET['download']);
        if (file_exists("./uploads/".$port."/".$downloadiddecode."")) {
            $filename = "./uploads/".$port."/".$downloadiddecode."";
            if(ini_get("zlib.output_compression")) ini_set("zlib.output_compression", "Off");
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);
            header("Content-Type: audio/mpeg");
            header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".filesize($filename));
            readfile("$filename");
            exit();
        }
        else {
            $errors[] = "<h2>".$messages["558"]."</h2>";
        }
    }
    $database_space = mysql_query("SELECT * FROM servers WHERE portbase='".$port."'") or die ();
    $data = mysql_fetch_array($database_space);
    $port = strip_tags(str_replace('/', '', $port));

    if (file_exists("./uploads/".$port."/")) {
        $dir = "./uploads/".$port."/";
        $filesize = 0;
        if(is_dir($dir)) {
            if($dp = opendir($dir)) {
                while( $filename = readdir($dp) ) {
                    if(($filename == '.') || ($filename == '..'))
                        continue;
                    $filedata = stat($dir."/".$filename);
                    $filesize += $filedata[7];
                }
                $actual_dir_size = $filesize/1024;
            }
            else {
                $errors[] = "<h2>".$messages["559"]."</h2>";
            }
        }
        else {
            $errors[] = "<h2>".$messages["560"]."</h2>";
        }
    }
}
$dirlistdir = @opendir("./uploads/".$port."/") or $errors[] = "<h2>".$messages["561"]."</h2>";
define('entries_per_page',7);
if (!isset($_GET['filecount']) or !is_numeric($_GET['filecount'])) $offset = 1;
else $offset = $_GET['filecount'];
if ($offset == 1) {
    $listing_start = $offset * entries_per_page - entries_per_page;
}
else {
    $listing_start = $offset * entries_per_page - entries_per_page + 3;
}
$listing_end = $offset * entries_per_page + 2;
$dirlisting = @scandir("./uploads/".$port) or $errors[] = "<h2>".$messages["562"]."</h2>";
if (!isset($dirlisting[$listing_start])) $errors[] = "<h2>".$messages["563"]."</h2>";
if (isset($_GET['playlist']) or is_numeric($_GET['playlist'])) {
    if (!file_exists("./temp/".$port.".lst")) {
        $handle = fopen("./temp/".$port.".lst",'w+');
        fclose($handle);
        chmod("./temp/".$port.".lst",0777);
    }
    shell_exec('find '.dirname(__FILE__).'/uploads/'.$port.'/ -type f -name "*.mp3" > ./temp/'.$port.'.lst');
    header('Location: content.php?include=music&error=playlist');
    die ();
}
