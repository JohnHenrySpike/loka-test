<?php
require_once 'core.php';

echo "Clear users table... \n";
db_query("TRUNCATE users;");
echo "Users table cleared.\n";