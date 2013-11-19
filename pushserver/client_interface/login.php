<?php

require_once("../common/util.php");

$jsonpCallback = "userLogin";

if(!isset($_GET['user_email']) || !isset($_GET['user_pw']))
  replyJSONP($jsonpCallback, 0, "data fields not set", NULL);

$email = $_GET['user_email'];
$pw = $_GET['user_pw'];

require_once("../db/db_connection.php");

$db = new DBConnection();
if(!$db->connect())
  replyJSONP($jsonpCallback, 0, "failed to connect to database", NULL);

$uid = $db->getUID($email);
if($uid == NULL)
  replyJSONP($jsonpCallback, 0, "failed to get user id", NULL);

if(!$db->verifyPassword($uid, $pw))
  replyJSONP($jsonpCallback, 0, "invalid password", NULL);

$db->close();

replyJSONP($jsonpCallback, 1, "", array("user_id" => $uid));

?>
