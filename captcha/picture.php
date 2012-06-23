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

session_start();	
$img=imagecreatefromjpeg('captcha_bg.jpg');	
$text=imagettftext($img,15,rand(-10,10),rand(5,80),rand(20,21),imagecolorallocate($img,255-rand(100,255),255-rand(100,255),255-rand(100,255)),"../css/font_source/delicious-roman-webfont.ttf",empty($_SESSION['captcha_streamerspanel']) ? 'error' : $_SESSION['captcha_streamerspanel']);
header("Content-type:image/jpeg");
header("Content-Disposition:inline ; filename=secure.jpg");	
imagejpeg($img);