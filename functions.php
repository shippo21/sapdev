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

require_once 'meekrodb.2.0.class.php';
require_once 'Requests.php';
Requests::register_autoloader();
define('SAP_VERSION', 'dev3.3.4');

function e($v) { echo $v; }
function el($v) { e($v . PHP_EOL); }

function sendUsageStatisticsIfAllowed()
{
	if (!isSendingOfUsageStatisticsAllowed()) {
		return;
	}

	$data = collectUsageData();
	sendUsageDataToServer($data);
}

function isSendingOfUsageStatisticsAllowed()
{
	if (!file_exists('.isAllowedToSendUsageStatistics')) {
		return false;
	};

	if (!file_exists('.lastUsageSentAt')) {
		file_put_contents('.lastUsageSentAt', date('Y-m-d H'));
		return true;
	} else {
		$lastUsageSentAt = file_get_contents('.lastUsageSentAt');
		if ($lastUsageSentAt !== date('Y-m-d H')) {
			file_put_contents('.lastUsageSentAt', date('Y-m-d H'));
			return true;
		} else {
			return false;
		}
	}
}

function collectUsageData()
{
	list($amountOfServers, $currentlyRunningServers) = getCurrentlyRunningServers();
	return array(
		'phpversion' => phpversion(),
		'phpinfo' => getPhpInfoAsArray(),
		'amount_of_servers' => $amountOfServers,
		'currently_running_servers' => $currentlyRunningServers,
		'panel_version' => SAP_VERSION,
	);
}

function getPhpInfoAsArray()
{
	ob_start();
	phpinfo();
	$info_arr = array();
	$info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
	$cat = "General";
	foreach($info_lines as $line)
	{
		// new cat?
		preg_match("~<h2>(.*)</h2>~", $line, $title) ? $cat = $title[1] : null;
		if(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
		{
			if (stripos($val[1], 'request') !== false
				|| stripos($val[1], 'post') !== false
				|| stripos($val[1], 'get') !== false
				|| stripos($val[1], 'cookie'))
				continue;

			$info_arr[$cat][$val[1]] = $val[2];
		}
		elseif(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
		{
			$info_arr[$cat][$val[1]] = array("local" => $val[2], "master" => $val[3]);
		}
	}
	return $info_arr;
}

function getCurrentlyRunningServers()
{
	$toReturn = array();

	$serverPorts = DB::queryOneColumn('portbase', 'SELECT * FROM `servers`');
	foreach ($serverPorts as $serverPort) {
		try {
			$shoutcastStatusUrl = sprintf('http://localhost:%d/7.html', $serverPort);
			$response = Requests::get($shoutcastStatusUrl, array('user-agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13'));
			if (!$response->success) {
				continue;
			}

			if (preg_match('~<body>(.+?)</body>~', $response->body, $matches) !== 1) {
				continue;
			}

			list($listeners, $hasInput, $listenerPeak, $maxListener, $uniqueListeners, $bitrate, $currentTrack) = explode(',', $matches[1]);
			$toReturn[] = array(
				'listeners_current' => $listeners,
				'stream_status' => $hasInput,
				'listeners_peak' => $listenerPeak,
				'listeners_max' => $maxListener,
				'listeners_unique' => $uniqueListeners,
				'bitrate' => $bitrate,
				'track_current' => $currentTrack,
			);
		} catch (Exception $e) {
			continue;
		}

	}

	return array(count($serverPorts), $toReturn);
}

function sendUsageDataToServer(array $data)
{
	static $serverUrl = 'http://update.streamerspanel.com/usage.php';
	Requests::post($serverUrl, array(), array('data' => json_encode($data)));
}