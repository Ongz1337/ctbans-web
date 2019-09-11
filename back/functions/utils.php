<?php
if( !defined('IS_INC') )
    exit;

/**
 * @param string $value
 * @return false|int
 */
function isValidSteamid($value){
    $pattern = 'STEAM_[0-1]:[0-1]:[0-9]{7,10}';
    return preg_match("#{$pattern}#", $value);
}

function _formatBans($bans){
    for($i = 0; $i < count($bans); $i++){
        $bans[$i]->avatar = _getPlayerAvatar($bans[$i]->perp_steamid);
    }
    return $bans;
}

/**
 * cache
 * thanks to deceze
 * https://stackoverflow.com/a/11407678/4752480
 *
 * @return stdClass
 */
function getJson($url) {
    $cacheFile = ROOT_DIR . '/back/cache' . DIRECTORY_SEPARATOR . md5($url);
    $json = "";

    if (file_exists($cacheFile)) {
        $fh = fopen($cacheFile, 'r');
        $cacheTime = filemtime($cacheFile);

        if ($cacheTime > strtotime('-24 hours')) {
            $json = json_decode(fread($fh, filesize($cacheFile)));
            fclose($fh);
            return $json;
        }

        fclose($fh);
        unlink($cacheFile);
    }

    $json = file_get_contents($url);
    $json = json_decode($json)->response->players;
    $json = array_pop($json);

    $fh = fopen($cacheFile, 'w');
    fwrite($fh, json_encode($json));
    fclose($fh);

    return $json;
}

/**
 * Get player avatar using steam id.
 * @param $steamid
 * @return mixed
 */
function _getPlayerAvatar($steamid){
    $info = _getPlayerSummaries(_getSteamID64($steamid));
    return (
      $info == null ?
          'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/fe/fef49e7fa7e1997310d705b2a6158ff8dc1cdfeb_full.jpg':
          $info->avatarfull
    );
}

function _getPlayerSummaries($steamid64){
    $url = sprintf(STEAM_API_URL_SUMMARIES, STEAM_APIKEY, $steamid64);
    return getJson($url);
}

/**
 * convert steamid32 to steamid64
 * thanks to Anonimous PT
 * https://stackoverflow.com/a/44499987/4752480
 * @param string $id
 * @return string
 */
function _getSteamID64($id) {
    if (preg_match('/^STEAM_/', $id)) {
        $parts = explode(':', $id);
        return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
    } elseif (is_numeric($id) && strlen($id) < 16) {
        return bcadd($id, '76561197960265728');
    } else {
        return $id; // We have no idea what this is, so just return it.
    }
}

function _parseInt($string) {
    //    return intval($string);
    if(preg_match('/(\d+)/', $string, $array)) {
        return $array[1];
    } else {
        return 0;
    }
}

/**
 * Convert SteamID64 into SteamID
 * @param $id
 * @return string
 */
function _getSteamId32($id){
    $subid = substr($id, 4);
    $steamY = _parseInt($subid);
    $steamY = $steamY - 1197960265728; //76561197960265728
    if ($steamY%2 == 1){
        $steamX = 1;
    } else {
        $steamX = 0;
    }
    $steamY = (($steamY - $steamX) / 2);
    $steamID = "STEAM_0:" . (string)$steamX . ":" . (string)$steamY;
    return $steamID;
}

/**
 * Login failed login attemps to a file.
 */
function _logFailedLogin($steamid) {
    $ip = isset($_SERVER['REMOTE_HOST']) ?
        $_SERVER['REMOTE_HOST'] :
        (isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : '');

    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
        $ip = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
    }
    $root = defined('ROOT_DIR') ? ROOT_DIR : (__DIR__ . '/../');
    $filename = $root . '/back/logs/' . date('d-m-y') . '.log';
    $file = new SplFileObject($filename, 'a+');
    $file->fwrite(date('h:i:s') . " - steamid '{$steamid}' with ip '{$ip}' failed to login." . PHP_EOL);
    $file = null;
}

function _initDb(array $cfg){
    $dbcfg = $cfg['db'];
    $options = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES   => false,
    );

    try {
        $DB = new PDO(
            "mysql:{$dbcfg['host']};port={$dbcfg['port']};dbname={$dbcfg['db']};charset={$dbcfg['charset']}",
            $dbcfg['user'],
            $dbcfg['pass'],
            $options
        );
    }catch (Exception $e){
        echo "Database connection. Error code: {$e->getCode()}";
        exit;
    }
    return $DB;
}

function _initAuth(array $cfg){
    $auth = new SteamAuth();
    $auth->SetOnLoginCallback(function($steamid) use ($cfg) {
        if( in_array($steamid, $cfg['allowedSteamids']) )
            return true;
        _logFailedLogin($steamid);
        return false;
    });
    $auth->SetOnLoginFailedCallback(function(){
        _logFailedLogin('');
        return true;
    });
    $auth->SetOnLogoutCallback(function($steamid){
        return true;
    });
    $auth->init();
    return $auth;
}

function jsonExit($isError, $msg){
    $f = $isError ? "error" : "success";
    echo json_encode($f($msg));
    exit;
}


/**
 * return an array representing an error.
 * @param $message
 * @return array
 */
function error($message){
    return [
        'error' => true,
        'msg'   => $message,
    ];
}
// opposite of ^
/**
 * @param $message
 * @return array
 */
function success($message){
    return [
        'error' => false,
        'msg'   => $message,
    ];
}

function validData(array $required, array $data){
    return count(array_intersect_key(array_flip($required), $data)) === count($required);
}

function redirect($url){
    header('Location: ' . $url);
	exit;
}

/**
 * Thanks to https://stackoverflow.com/users/4163162/maggsweb
 * @param $data
 * @param string $label
 * @param bool $return
 * @return string
 */
function dd($data, $label='', $return = false) {

    $debug           = debug_backtrace();
    $callingFile     = $debug[0]['file'];
    $callingFileLine = $debug[0]['line'];

    ob_start();
    var_dump($data);
    $c = ob_get_contents();
    ob_end_clean();

    $c = preg_replace("/\r\n|\r/", "\n", $c);
    $c = str_replace("]=>\n", '] = ', $c);
    $c = preg_replace('/= {2,}/', '= ', $c);
    $c = preg_replace("/\[\"(.*?)\"\] = /i", "[$1] = ", $c);
    $c = preg_replace('/  /', "    ", $c);
    $c = preg_replace("/\"\"(.*?)\"/i", "\"$1\"", $c);
    $c = preg_replace("/(int|float)\(([0-9\.]+)\)/i", "$1() <span class=\"number\">$2</span>", $c);

// Syntax Highlighting of Strings. This seems cryptic, but it will also allow non-terminated strings to get parsed.
    $c = preg_replace("/(\[[\w ]+\] = string\([0-9]+\) )\"(.*?)/sim", "$1<span class=\"string\">\"", $c);
    $c = preg_replace("/(\"\n{1,})( {0,}\})/sim", "$1</span>$2", $c);
    $c = preg_replace("/(\"\n{1,})( {0,}\[)/sim", "$1</span>$2", $c);
    $c = preg_replace("/(string\([0-9]+\) )\"(.*?)\"\n/sim", "$1<span class=\"string\">\"$2\"</span>\n", $c);

    $regex = array(
        // Numberrs
        'numbers' => array('/(^|] = )(array|float|int|string|resource|object\(.*\)|\&amp;object\(.*\))\(([0-9\.]+)\)/i', '$1$2(<span class="number">$3</span>)'),
        // Keywords
        'null' => array('/(^|] = )(null)/i', '$1<span class="keyword">$2</span>'),
        'bool' => array('/(bool)\((true|false)\)/i', '$1(<span class="keyword">$2</span>)'),
        // Types
        'types' => array('/(of type )\((.*)\)/i', '$1(<span class="type">$2</span>)'),
        // Objects
        'object' => array('/(object|\&amp;object)\(([\w]+)\)/i', '$1(<span class="object">$2</span>)'),
        // Function
        'function' => array('/(^|] = )(array|string|int|float|bool|resource|object|\&amp;object)\(/i', '$1<span class="function">$2</span>('),
    );

    foreach ($regex as $x) {
        $c = preg_replace($x[0], $x[1], $c);
    }

    $style = '
/* outside div - it will float and match the screen */
.dumpr {
    margin: 2px;
    padding: 2px;
    background-color: #fbfbfb;
    float: left;
    clear: both;
}
/* font size and family */
.dumpr pre {
    color: #000000;
    font-size: 9pt;
    font-family: "Courier New",Courier,Monaco,monospace;
    margin: 0px;
    padding-top: 5px;
    padding-bottom: 7px;
    padding-left: 9px;
    padding-right: 9px;
}
/* inside div */
.dumpr div {
    background-color: #fcfcfc;
    border: 1px solid #d9d9d9;
    float: left;
    clear: both;
}
/* syntax highlighting */
.dumpr span.string {color: #c40000;}
.dumpr span.number {color: #ff0000;}
.dumpr span.keyword {color: #007200;}
.dumpr span.function {color: #0000c4;}
.dumpr span.object {color: #ac00ac;}
.dumpr span.type {color: #0072c4;}
';

    $style = preg_replace("/ {2,}/", "", $style);
    $style = preg_replace("/\t|\r\n|\r|\n/", "", $style);
    $style = preg_replace("/\/\*.*?\*\//i", '', $style);
    $style = str_replace('}', '} ', $style);
    $style = str_replace(' {', '{', $style);
    $style = trim($style);

    $c = trim($c);
    $c = preg_replace("/\n<\/span>/", "</span>\n", $c);

    if ($label == ''){
        $line1 = '';
    } else {
        $line1 = "<strong>$label</strong> \n";
    }

    $out = "\n<!-- Dumpr Begin -->\n".
        "<style type=\"text/css\">".$style."</style>\n".
        "<div class=\"dumpr\">
    <div><pre>$line1 $callingFile : $callingFileLine \n$c\n</pre></div></div><div style=\"clear:both;\">&nbsp;</div>".
        "\n<!-- Dumpr End -->\n";
    if($return) {
        return $out;
    } else {
        echo $out;
        exit;
    }
}