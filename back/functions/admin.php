<?php
if( !defined('IS_INC') )
    exit;

function addBan($params){
    $required_data = [
        'perp_steamid',
        'perp_name',
        'bantime',
        'timeleft',
        'reason'
    ];

    dd($params);
    $val = trim($params['val']);
    if(!$val || empty($val))
        return error('Please provide a valid steamid.');

    if( !validData($required_data, $params) )
        return error('Please fill the required fields.');

    if( db_addBan($params['DB'], $params) ) // MUST REFRESH FROM CONSOLE OR BY ADMIN @todo add servers cron?
        return success('Ban added successfully.');
    return error('Error adding the ban (internal error).');
}

function remove($params){
    $required_data = [
        'id',
    ];

    $id = $params['id'];
    if( !validData($required_data, $params) ) {
        return error('[Remove] Please fill the required fields.');
    }

    if( db_removeBan($params['DB'], $id) ) // MUST REFRESH FROM CONSOLE OR BY ADMIN @todo add servers cron?
        return success('Ban removed successfully.');
    return error('Error adding the ban (internal error).');
}

/**
 * Is admin current logged in user
 * Simply read session value :<
 * @return bool
 */
function _isAdmin(){
    return (
        isset($_SESSION['steamid']) && !empty($_SESSION['steamid'])
    );
}