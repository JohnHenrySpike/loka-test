<?php
require_once 'mail_checker.php';
require_once 'notification.php';

mail_check_run();
notification_run();

$GLOBALS['PDO'] = null;