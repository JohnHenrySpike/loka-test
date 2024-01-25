<?php
require_once 'core.php';

echo "1m users loading... \n";
$date = date('Y-m-d H:i:s', time()+(3600*72) );
for($iterate = 1; $iterate <=10; $iterate++ ){
    $prefix = uniqid();
    $ins = "insert into test.users (username, email, validts, confirmed, checked) values ";
    for($i = 1; $i <=99999; $i++ ){
        $ins.= "('user_".$prefix."_".$i."', 'email".$prefix.$i."@mail.com', '". $date ."', 1, 1),";
    }
    $ins.= "('user_".$prefix."_".$i."', 'email".$prefix.$i."@mail.com', '". $date ."', 1, 1);";
    db_query($ins);
    echo "inserted ".$i." rows [$iterate/10]\n";
}
echo "1m users loaded\n";