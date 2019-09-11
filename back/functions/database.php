<?php

/**
 * @param PDO $DB
 * @param string $steamid
 * @return int
 */
function db_getPlayerTotalBans($DB, $steamid){
    $bansStmt = $DB->prepare('SELECT COUNT(id) total FROM ctban_log WHERE perp_steamid = :sid');
    $bansStmt->bindParam(':sid', $steamid);
    $bansStmt->execute();
    return (int) $bansStmt->fetchColumn();
}

/**
 * @param PDO $DB
 * @param string $steamId
 * @return array
 */
function db_getPlayerBansBySteam($DB, $steamId){
    $bansStmt = $DB->prepare('SELECT timestamp, ban_id, perp_steamid, perp_name, admin_steamid, admin_name, bantime, timeleft, reason FROM ctban_log WHERE perp_steamid = :sid ORDER BY timestamp');
    $bansStmt->bindParam(':sid', $steamId);
    $bansStmt->execute();
    $bans = $bansStmt->fetchAll();
    return _formatBans($bans);
}

/**
 * @param PDO $DB
 * @param string $name
 * @return array
 */
function db_getPlayerBansByName($DB, $name){
    $name = '%'.$name.'%';
    $bansStmt = $DB->prepare('SELECT timestamp, ban_id, perp_steamid, perp_name, admin_steamid, admin_name, bantime, timeleft, reason FROM ctban_log WHERE perp_name LIKE :name ORDER BY timestamp');
    $bansStmt->bindParam(':name', $name);
    $bansStmt->execute();
    $bans = $bansStmt->fetchAll();
    return _formatBans($bans);
}

/**
 * @param PDO $DB
 * @return array
 */
function db_getRecentBans($DB){
    $query = $DB->query('SELECT timestamp, ban_id, perp_steamid, perp_name, admin_steamid, admin_name, bantime, timeleft, reason FROM ctban_log ORDER BY timestamp DESC LIMIT 25');
    $bans = $query->fetchAll();
    return _formatBans($bans);
}

/**
 * @param PDO $DB
 * @return
 */
function db_addBan($DB, $data){
    $stmt = $DB->prepare('INSERT INTO ctban_log(timestamp, perp_steamid, perp_name, admin_steamid, admin_name, bantime, timeleft, reason) VALUES (:timestamp, :perp_steamid, :perp_name, :admin_steamid, :admin_name, :bantime, :timeleft, :reason)');
    foreach($data as $key => $value){
        $stmt->bindParam(':'.$key, $value);
    }
    return $stmt->execute();
}

/**
 * @param PDO $DB
 * @return
 */
function db_removeBan($DB, $banId){
    $stmt = $DB->prepare('DELETE FROM ctban_log WHERE ban_id = :banId');
    $stmt->bindParam(':banId', $banId);
    return $stmt->execute();
}