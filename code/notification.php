<?php
require_once 'core.php';
require_once 'mailer.php';

function create_notify_jobs()
{
    $sql = "insert into notifications (username, email)
    SELECT u.username, u.email
    FROM users u
    LEFT JOIN test.notifications nj on u.email = nj.email
    WHERE nj.id is null 
      AND confirmed = 1
      AND checked = 1
      and DATEDIFF(validts, NOW()) = 3";
    return db_query($sql);
}

function notify()
{
    $batch_size = 1000;
    $count = db_fetch("SELECT COUNT(*) cnt FROM notifications;")[0]["cnt"];
    echo "Notifications has ".$count." items\n";
    $iterations = intdiv($count, $batch_size);
    for($i = 0; $i <= intdiv($count, $batch_size); $i++){
        $sql = "SELECT id, username, email FROM notifications LIMIT $batch_size OFFSET ".$i*$batch_size;
        $notifications = db_fetch($sql);
        echo $i ."/". $iterations ." [".count($notifications). "] emails send initiated\n";
        foreach ($notifications as $job) {
            send_email_extended($job['email'], $job['username'], $job['id']);
        }
    }
    return null;
}

function prepare_to_update(int $id, bool $failed = false)
{
    if ($failed) {
        $GLOBALS["notify"]["fail"][] = $id;
    } else {
        $GLOBALS["notify"]["done"][] = $id;
    }
}

function update()
{
    if (isset($GLOBALS["notify"]["fail"])){
        $sql = "UPDATE notifications 
            SET failed = 1 
            WHERE id in (".implode(",", $GLOBALS["notify"]["fail"]).");";
        db_query($sql);
    }
    if (isset($GLOBALS["notify"]["done"])){
        $sql = "DELETE FROM notifications WHERE id in (".implode(",", $GLOBALS["notify"]["done"]).");";
        db_query($sql);
    }
    $GLOBALS["notify"] = null;
}

function send_email_extended(string $email, string $username, int $job_id)
{
    try{
        send_email(
            'notify@site.com',
            $email,
            'Subscription',
            $username. ', your subscription is expired soon.'
        );
        prepare_to_update($job_id);
    } catch (Exception $e){
        prepare_to_update($job_id, true);
    }
}

function notification_run()
{
    echo "\n[Notifications started]\n\n";
    echo "Creating notifications...\n";
    $cnt = create_notify_jobs();
    echo "Created $cnt notifications \n";

    notify();
    echo "\nnotification done ("
        .db_fetch("SELECT COUNT(*) cnt FROM notifications;")[0]['cnt']." mail initiated, "
        .db_fetch("SELECT COUNT(*) cnt FROM notifications WHERE failed = 1;")[0]['cnt']." failed) \n";

    echo "Database query run...\n";
    update();
    echo "Database query done. \n";
    echo "\n[Notifications done]\n\n";
}