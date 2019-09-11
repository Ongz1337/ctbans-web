<?php
if( !defined('IS_INC') )
    exit;

session_start();

$cfg = require_once __DIR__ .'/config.php';

require_once __DIR__ .'/functions.php';
require_once  __DIR__ . "/SteamAuth/SteamAuth.class.php";


define('SITE_URL', $cfg['site']['url']);
define('SITE_NAME', $cfg['site']['name']);
define('ROOT_DIR', __DIR__ . '/..');
define('STEAM_API_URL_SUMMARIES', 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s');
define('STEAM_APIKEY', $cfg['steam']['apikey']);
define('IS_ADMIN', _isAdmin());

if($cfg["debug"]){
    error_reporting(E_ALL);
    ini_set("display_errors", true);
}

$DB = _initDb($cfg);
$auth = _initAuth($cfg);

