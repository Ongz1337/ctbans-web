<?php
define('IS_INC', true);
require_once __DIR__ . '/back/init.php';

if ( isset($_GET['login']) ){
    if( ! $auth->IsUserLoggedIn() ) {
        $auth->RedirectLogin();
        exit;
    }
    redirect(SITE_URL . '/');
    exit;
}

if( isset($_GET['logout']) ){
    $auth->Logout();
    redirect(SITE_URL);
}

redirect(SITE_URL);
exit;
