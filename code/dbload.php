<?php
require_once 'core.php';

echo "100k users loading... \n";
$prefix = uniqid();
$ins = "insert into test.users (username, email, validts, confirmed, checked) values ";
for($i = 1; $i <=99999; $i++ ){
    $ins.= "('user".$prefix.$i."', 'email".$prefix.$i."@mail.com', '2024-01-".rand(1,31)." 02:27:37',".rand(0,1).",".rand(0,1)."),";
}
$ins.= "('user".$prefix.$i."', 'email".$prefix.$i."@mail.com', '2024-0".rand(1,9)."-".rand(10,31)." 02:27:37',".rand(0,1).",".rand(0,1).");";
db_query($ins);

echo "inserted ".$i." rows \n";