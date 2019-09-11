<?php
require_once __DIR__ . '/back/init.php';

$sql = <<<SQL_CREATE_TABLE
DROP TABLE IF EXISTS ctban_admin_log;
CREATE TABLE ctban_admin_log(
  id INT AUTO_INCREMENT PRIMARY KEY,
  steamid VARCHAR(50) NOT NULL,
  content VARCHAR(1028) NOT NULL,
  date_add DATETIME DEFAULT now()
);

SQL_CREATE_TABLE;
$sqlHistory = <<<SQL_CREATE_TABLE_HISTORY
DROP TABLE IF EXISTS ctban_ban_history;
CREATE TABLE ctban_ban_history(
  steamid VARCHAR(50) NOT NULL,
  content VARCHAR(1028) NOT NULL,
  date_add DATETIME DEFAULT  now()
  
);
SQL_CREATE_TABLE_HISTORY;


echo 'Running installation... ';
if( ($DB->exec($sql) !== false ) && ($DB->exec($sqlHistory) !== false)){
    echo "Tables created successfully.";
    echo "<br> This installation file will now be deleted... ";
    if( unlink(__FILE__) )
        echo ' Installation file deleted.';
    else
        echo ' Please delete this installation file manually.';
}else{
    echo "Error creating tables. Error message: <pre>";
    print_r( $DB->errorInfo() );
    // reverse
    $DB->exec('DROP TABLE IF EXISTS ctban_admin_log; DROP TABLE IF EXISTS ct_ban_history;');
}