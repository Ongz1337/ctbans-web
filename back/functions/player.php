<?php
if( !defined('IS_INC') )
    exit;

/**
 * @param $params
 * @return null|array
 */
function getBans($params){
    $DB = $params['DB']; /** @var PDO $DB */
    $val = trim($params['val']);

    if(!$val || empty($val))
        return [
            'error' => true,
            'msg'   => 'Invalid Steam ID.',
        ];

    if($val == 'all')
        $bans = db_getRecentBans($DB);
    else {
        $val = urldecode($val);
        if( isValidSteamid($val) )
            $bans = db_getPlayerBansBySteam($DB, $val);
        else
            $bans = db_getPlayerBansByName($DB, $val);
    }

    return [
        'error' => false,
        'data'  => $bans,
    ];
}



