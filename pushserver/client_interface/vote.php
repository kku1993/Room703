<?php

require_once("../common/util.php");
require_once("../db/db_connection.php");

$jsonpCallback = "voteItem";

if(!isset($_GET['item_id'])
  || !isset($_GET['user_id'])
  || !isset($_GET['accept_item']))
  replyJSONP($jsonpCallback, 0, "data field not set", NULL);

$item_id = $_GET['item_id'];
$user_id = $_GET['user_id'];
$accept_item = $_GET['accept_item'];

$db = new DBConnection();
if(!$db->connect())
  replyJSONP($jsonpCallback, 0, "failed to connect to database", NULL);

$data = $db->voteItem($item_id, $user_id, $accept_item);

if($data == NULL)
  replyJSONP($jsonpCallback, 0, "failed update item vote", NULL);

$db->close();

replyJSONP($jsonpCallback, 1, "", $data);

?>
