<?php
define('IS_INC', true);
require_once __DIR__ . '/back/init.php';
?>
<!--
Thanks to:
- Icons by https://icons8.com
- loading gif by Fadhel Adam https://www.behance.net/gallery/36197347/Loading-Spinner-(GIF)
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CTBans - <?= SITE_NAME ?></title>

    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/ctbans.css" type="text/css">
</head>
<body>
<header></header>
<main id="app">
    <div class="errors" v-if="errors.length">
        <ul>
            <li v-for="(error, i) in errors" :key="i">{{ error }} <span>&times;</span></li>
        </ul>
    </div>

    <div class="header">
        <search @hide-search="hideSearchbox" @player-search="playerSearch" v-show="showSearch"></search>
        <div class="container flex">
            <div class="menu">
                <ul>
                    <li class="dropdown-parent">
                        <img src="assets/img/menu.svg" alt="menu">
                        <ul class="dropdown">
                            <?php foreach ($cfg['site']['links'] as $label => $link): ?>
                                <?php if( empty($label) ): ?>
                                    <li class="sep"></li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?= $link ?>"><?= $label ?></a>
                                </li>
                            <?php endforeach; ?>
                            <li class="sep"></li>
                            <li>
                                <?php if( IS_ADMIN ): ?>
                                    <a href="<?= SITE_URL ?>/auth.php?logout">Logout</a>
                                <?php else: ?>
                                    <a href="<?= SITE_URL ?>/auth.php?login">Login</a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="sitename">
                CTBans - <?= SITE_NAME ?>
            </div>
            <div class="search">
                <span @click="showSearchbox">
                    <img src="assets/img/search.svg" alt="Search" width="42px">
                </span>
            </div>
        </div>
    </div>
    <div class="loading" v-show="loading">
        <img src="assets/img/loading.gif" alt="loading...">
    </div>
    <div class="main" v-if="!loading">
        <div class="container">
            <div class="content">
                <div class="nobans" v-if="bans.length < 1">
                    No bans.
                </div>
                <ban v-for="ban in bans" :key="ban.ban_id" :data="ban"></ban>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <div>
<!--                <span>TOTAL BANS: {{ bans.length }}</span>-->
                <span>SHOWING RECENT {{ bans.length }} BANS</span>
            </div>
<!--            <div>-->
<!--                <span>ONLY 25 BANS ARE SHOWN AT ALL TIMES</span>-->
<!--            </div>-->
        </div>
    </div>
</main>
<script>
    const SITE_URL = "<?= SITE_URL ?>";
    const AJAX_URL = SITE_URL + "/ajax.php";
    const IS_ADMIN = <?php echo IS_ADMIN ? "true" : "false" ?>;
</script>
<script src="assets/js/ctbans.js"></script>
</body>
</html>
<!--
@todo: SOFT DLEETE INSTEAD OF HARD DELETE? or implement a ban deletion history?
-->