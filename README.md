loka-test
=================

Requriments: 
 - Linux
 - docker
 - docker-compose

Usage
--------------

```Shell
# clone the code repository
$ git clone git@github.com:JohnHenrySpike/loka-test.git
# get the root of project
$ cd loka-test
#buil docker images and starts conatiners
$ make up        
```

Use
-----------
add to cron row like

`0 1 * * * /usr/bin/php -f /path/to/app/job.php &> /var/log/app_notify.log`

for this project row must be like

`0 1 * * * cd /path/to/project && docker compose exec -u $$(id -u):$$(id -g) php-fpm php job.php &> /var/log/app_notify.log`


Test
------------
in project root

`make dbload` - add 100k users to users table

`make clear` - clear users table

`make dbload-1m` - add 1 million users to users table

`make test` - run mail checker and notification sender job 


Sample output `make test` command
```shell
[Mail checker started]

Users has 12505 unchecked emails
0/11 [1000] mail-check initiated
1/11 [1000] mail-check initiated
2/11 [1000] mail-check initiated
3/11 [1000] mail-check initiated
4/11 [1000] mail-check initiated
5/11 [1000] mail-check initiated
6/11 [1000] mail-check initiated
7/11 [1000] mail-check initiated
8/11 [1000] mail-check initiated
9/11 [1000] mail-check initiated
10/11 [1000] mail-check initiated
11/11 [1000] mail-check initiated
12/11 [505] mail-check initiated
Database query run...
SQL query to update 12505 rows
Database query done. 

[Mail check complete]


[Notifications started]

Creating notifications...
Created 853 notifications 
Notifications has 1543 items
0/1 [1000] emails send initiated
1/1 [543] emails send initiated

notification done (1543 mail initiated, 690 failed) 
Database query run...
Database query done. 

[Notifications done]

```