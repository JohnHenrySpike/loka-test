<?php
require_once 'core.php';
require_once 'mailer.php';

function mail_check_run()
{
    echo "\n[Mail checker started]\n\n";
    $batch_size = 1000;
    $sql = "SELECT count(*) cnt FROM users WHERE checked = 0;";
    $count = db_fetch($sql)[0]['cnt'];
    echo "Users has ".$count." unchecked emails\n";
    if ($count){
        $iterations = intdiv($count, $batch_size);
        for($i = 0; $i <= $iterations; $i++){
            $sql = "SELECT id, email FROM users WHERE checked = 0 LIMIT $batch_size OFFSET ".$i*$batch_size;
            $users = db_fetch($sql);
            echo $i ."/". $iterations-1 ." [".count($users). "] mail-check initiated\n";
            foreach ($users as $user) {
                check_email_extedned($user['id'], $user['email']);
            }
        }
        echo "Database query run...\n";
        update_users();
        echo "Database query done. \n";
    }
    echo "\n[Mail check complete]\n\n";

}

function prepare_update_user(int $user_id)
{
    $GLOBALS["mail_checker"]["done"][] = $user_id;
}

function update_users()
{
    if (isset($GLOBALS["mail_checker"]["done"])){
        $sql = "UPDATE users SET checked = 1 WHERE id in (".implode(",", $GLOBALS["mail_checker"]["done"]).")";
        echo "SQL query to update ".count($GLOBALS["mail_checker"]["done"])." rows\n";
        db_query($sql);
    }
    if (isset($GLOBALS["mail_checker"]["fail"])){
        echo "Failed mail checks ". count($GLOBALS["mail_checker"]["fail"])."\n";
    }
    $GLOBALS["mail_checker"] = null;
}

function check_email_extedned(int $user_id, string $email)
{
    try{
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new Exception("Invalid email");
        }
        check_email($email);
        prepare_update_user($user_id);
    } catch (Exception $e){
        $GLOBALS["mail_checker"]["fail"][] = $user_id;
    }
}