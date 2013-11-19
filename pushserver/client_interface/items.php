<?php

require_once("../common/util.php");
require_once("../db/db_connection.php");

$jsonpCallback = "listItems";

if(!isset($_GET['user_id']))
  replyJSONP($jsonpCallback, 0, "user id not set", NULL);

$uid = $_GET['user_id'];

$db = new DBConnection();
if(!$db->connect())
  replyJSONP($jsonpCallback, 0, "failed to connect to database", NULL);

$room_id = $db->getRoomID($uid);
if($room_id == NULL)
  replyJSONP($jsonpCallback, 0, "failed to get room id", NULL);

$items = $db->getRoomItems($room_id);
if($items == NULL)
  replyJSONP($jsonpCallback, 0, "failed to get room items", NULL);

$db->close();

$data = array("num_itmes" => sizeof($items),"items" => $items);
replyJSONP($jsonpCallback, 1, "", $data);

?>
