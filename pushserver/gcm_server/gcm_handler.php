<?php
/*
 *  @author kku
 *  @comment wrapper class to handle GCM messages
 */

require_once("gcm_response.php");

class GCMHandler{
	public function __construct(){
	}
	
	public function __destruct(){
	}
	
	/*	send push notification
	 * 	REQUIRES: $reg_ids are registration ids of Android devices 
	 *		registered with GCM. 
	 *		$data is an array containing any data that needs to be passed 
	 *		along, but cannot exceed 4kb
	 *	ENSURES: returns gcm_response object
	 */
	public function sendNotification($reg_ids, $data){
    require_once "gcm_config.php";

    if(!sizeof($reg_ids))
      return new GCMResponse(0, "no registration id given");

    //set POST variables
    $url = 'https://android.googleapis.com/gcm/send';
    
    $fields = array(
      'registration_ids' => $reg_ids,
      'data' => $data,
    );

    $headers = array(
      'Authorization: key='.GOOGLE_API_KEY, 
      'Content-Type: application/json'
    );

    $con = curl_init();

    //set url
    curl_setopt($con, CURLOPT_URL, $url);
 
		//set POST
    curl_setopt($con, CURLOPT_POST, true);
    curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($con, CURLOPT_RETURNTRANSFER, true);

    //disable ssl
    curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($con, CURLOPT_POSTFIELDS, json_encode($fields));

    //POST to GCM
    $result = curl_exec($con);
		
    if($result === false)
      return new GCMResponse(0, curl_error($con));
 
    //close connection
    curl_close($con);

    return new GCMResponse(1, "");
	}
	
	/*
	 *	send a purchase request to roommates
	 *	REQUIRES: $uid is the user_id of the person requesting the purchase,
	 *		$data contains data about the purchase as a string-indexed array,
   *    $roommates is an array of person objects representing the roommates 
   *    that this request is targeted to
	 *	ENSURES: returns GCMResponse object
	 */
	public function sendPurchaseRequest($uid, $roommates, $data){
    //get roommate android devices
    $getDevice = function($r){ 
      $r->updateDevices(true); 
      return $r->getAndroidDevices(); 
    };
    $android = array_map($getDevice, $roommates);

    //get all gcm_reg_id
    $gcm_reg_id = array();

    foreach($android as $a){
      $getGCMID = function($d){ 
        return $d->device_info['gcm_reg_id'];
      };

      if($a != NULL){
        $id = array_map($getGCMID, $a);
        $gcm_reg_id = array_merge($gcm_reg_id, $id);
      }
    }

    $info = array(
      "title" => "New Item!",
      "message" => $data['item_name']." has been added to your shopping list."
    );

    //send notification
    return $this->sendNotification($gcm_reg_id, array_merge($info, $data));
	}
}
?>
