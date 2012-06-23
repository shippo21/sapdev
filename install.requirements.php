<?php
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

$requirements = array(
	array(
		'function' => function() {
			return extension_loaded('ssh2');
		},
		'success_message' => 'i15',
		'failure_message' => 'i14',
	),
	array(
		'function' => function() {
			return extension_loaded('mysql');
		},
		'success_message' => 'i17',
		'failure_message' => 'i16',
	),
	array(
		'function' => function() {
			return !(bool)ini_get('safe_mode');
		},
		'success_message' => 'i18',
		'failure_message' => 'i19',
	),
	array(
		'function' => function() {
			return is_writable('database.php') && is_readable('database.php');
		},
		'success_message' => 'i22',
		'failure_message' => 'i23',
	),
	array(
		'function' => function() {
			$rights = substr(sprintf('%o', fileperms('pages')), -3);
			return $rights === '777';
		},
		'success_message' => 'i25',
		'failure_message' => 'i27',
	),
	array(
		'function' => function() {
			$rights = substr(sprintf('%o', fileperms('temp')), -3);
			return $rights === '777';
		},
		'success_message' => 'i26',
		'failure_message' => 'i28',
	),
	array(
		'function' => function() {
			$rights = substr(sprintf('%o', fileperms('files/linux/sc_serv.bin')), -3);
			return $rights === '777';
		},
		'success_message' => 'install_sc_serv_rights_checked',
		'failure_message' => 'install_sc_serv_rights_failed',
	),
	array(
		'function' => function() {
			$rights = substr(sprintf('%o', fileperms('files/linux/sc_trans.bin')), -3);
			return $rights === '777';
		},
		'success_message' => 'install_sc_trans_rights_checked',
		'failure_message' => 'install_sc_trans_rights_failed',
	),
);