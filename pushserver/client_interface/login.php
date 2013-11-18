<?php

function reply_fail($msg){
  echo "userLogin(";
  echo json_encode(array("success" => 0, "error" => $msg));
  echo ");";
  exit(0);
}

if(!isset($_GET['user_email']) || !isset($_GET['user_pw']))
  reply_fail("data fields not set");

$email = $_GET['user_email'];
$pw = $_GET['user_pw'];

require_once("../db/db_connection.php");

$db = new DBConnection();
if(!$db->connect())
  reply_fail("failed to connect to database");

$uid = $db->getUID($email);
if($uid == NULL)
  reply_fail("failed to get user id");

if(!$db->verifyPassword($uid, $pw))
  reply_fail("invalid password");

$db->close();

echo "userLogin(";
echo json_encode(array("success" => 1, "user_id" => $uid));
echo ");";

?>
