<?php
  /* 
   *  jsonpCallback - string of the name of the callback function
   *  success - 0 or 1
   *  error - error message (optional)
   *  data - associative array to send back to client as JSON (optional)
   *  ENSURES: returns a JSONP to the client
   */
  function replyJSONP($jsonpCallback, $success, $error, $data){
    $ret = array("success" => $success, "error" => $error);
    if($data != NULL)
      $ret = array_merge($data, $ret);

    echo $jsonpCallback."(".json_encode($ret).");";
    
    exit(0);
  }
?>
