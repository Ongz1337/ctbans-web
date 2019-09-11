<?php
define('IS_INC', true);
require_once __DIR__ . '/back/init.php';

if(!IS_ADMIN){
    jsonExit(true, "Auth error.");
}

$action = isset($_GET['action']) ? $_GET['action'] : null;
$value  = isset($_GET['val']) ? $_GET['val'] : null;
$func = null;

$params = array_merge(
    (isset($_GET) ? $_GET : []),
    (isset($_POST) ? $_POST : []),
    [
        'cfg'   => $cfg,
        'DB'    => $DB  /** @var PDO $DB */
    ]
);

switch ($action){
    case 'remove':
        $func = 'remove';
        break;
}
if($func != null) {
//    $result = admin_call_user_func($func, $params);
    $result = call_user_func($func, $params);
    echo json_encode(
        $result
    );
}else{
    echo json_encode(error("Nice try."));
}

$DB = null;