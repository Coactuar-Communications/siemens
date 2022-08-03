<?php
$event_title = 'OnConf Virtual Conference Demo';
$admin_title = 'OnConf Demo';
$dates = array('', '2021-04-01');
$agenda_url = "#";

define('DBHOST', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASENAME', 'onconfdemo');
define('DBPORT', '3306');
/* define('DBHOST', 'db-mysql-blr1-97926-do-user-7739084-0.b.db.ondigitalocean.com');
define('USERNAME', 'genseer');
define('PASSWORD', 'ub3dk6u3gmqtglw0');
define('DATABASENAME', 'onconfdemo');
define('DBPORT', '25060');
 */
define('SALT', 'onconfSALT');
//define('PASSWORD_DEFAULT', "2y");
define('PUBNUB_PUB', 'pub-c-266dbab2-aa12-4e74-b1b9-c0813bea3a4b');
define('PUBNUB_SUB', 'sub-c-d9b14368-dd03-11eb-8c90-a639cde32e15');

define('CHANNEL_REG', 'registration');
define('CHANNEL_ANN', 'announcements');
define('CHANNEL_LIVESESS', 'livesession');
define('CHANNEL_QUES', 'sessques');
define('CHANNEL_POLL', 'sesspoll');
define('CHANNEL_POLLRESP', 'sesspollresp');
define('CHANNEL_AGENDA', 'audiagenda');
define('CHANNEL_AUDISTATUS', 'audistatus');

define('TBL_USERS', 'tbl_attendees');
define('TBL_USERLOGINS', 'tbl_attendeelogins');

define('TBL_AUDI', 'tbl_auditoriums');

define('TBL_SESSIONS', 'tbl_sessions');
define('TBL_SESSIONATT', 'tbl_sessionattendees');
define('TBL_SESSIONQUES', 'tbl_sessionquestions');

define('TBL_POLLS', 'tbl_polls');
define('TBL_POLLRESP', 'tbl_pollanswers');

define('TBL_EXHIBS', 'tbl_exhibitors');
define('TBL_EXHIBVISITORS', 'tbl_exhibitorvisitors');

define('TBL_ANN', 'tbl_announcements');
