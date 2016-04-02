<?php
header('Access-Control-Allow-Origin: *');  
echo "OK";
require_once "config.php";

if(empty(@$_GET['id'])){ die(); }

// If the user is banned, don't let them submit data
if(in_array(@$_SERVER['REMOTE_ADDR'],$banlist)){ die(); }

// FIXME: Don't hardcode this
// Next merge should be around 5000 and will probably take a while.
if (intval(@$_GET['count']>6000) || intval(@$_GET['count'])<0) { die(); }

// Basic data validation
if (intval(@$_GET['count']) != (intval(@$_GET['gr'])+intval(@$_GET['st'])+intval(@$_GET['ab'])+intval(@$_GET['nv']))) { die(); }

// If this IP has posted a result in the last 45 seconds: bail
$data = $database->query("SELECT * FROM track WHERE `ip`=".$database->quote(@$_SERVER['REMOTE_ADDR'])." AND `time`>(UNIX_TIMESTAMP()-45)")->fetchAll();
if(count($data)!=0) { die(); }


$last_user_id = $database->insert("track", [
	"room" => $_GET['id'],
	"abandon" => @$_GET['ab'],
	"stay" => @$_GET['st'],
	"grow" => @$_GET['gr'],
	"novote" => @$_GET['nv'],
	"count" => @$_GET['count'],
	"reap" => @$_GET['rt'],
	"formation" => @$_GET['ft'],
	"time" => time(),
	"ip" => @$_SERVER['REMOTE_ADDR']
]);
