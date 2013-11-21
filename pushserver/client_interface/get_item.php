<?php

require_once("../common/util.php");
require_once("../db/db_connection.php");

$jsonpCallback = "getItem";

if(!isset($_GET['item_id']))
  replyJSONP($jsonpCallback, 0, "item id not set", NULL);

$item_id = $_GET['item_id'];

$db = new DBConnection();
if(!$db->connect())
  replyJSONP($jsonpCallback, 0, "failed to connect to database", NULL);

$item = $db->getItem($item_id);
if($item == NULL)
  replyJSONP($jsonpCallback, 0, "failed to item", NULL);

$db->close();

$data = array("item" => $item);
replyJSONP($jsonpCallback, 1, "", $data);

?>
