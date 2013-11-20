<?php
class Person {
	public $user_id;
	public $first_name;
	public $last_name;
	public $devices = NULL; //list of devices this person is using
	
	public function __construct($id, $fname, $lname){
		$this->uid = $id;
		$this->first_name = $fname;
		$this->last_name = $lname;
	}

  public getAndroidDevices(){
    require_once("constants.php");
    $f = function($d){ return $d.device_type == ANDROID_DEVICE; };
    return array_filter($this->devices, $f);
  }

  /*
   *  get this person's devices from database
   *  REQUIRES: set $forceUpdate to true to refresh the user's device list
   *  ENSURES: returns true/false depending on success or not
   */
  public updateDevices($forceUpdate){
    if(!$forceUpdate && $this->devices != NULL)
      return true; //have devices already, no need to update

    if(!$this->user_id)
      return false; //no user_id

    require_once "../db/db_connection.php";
    $db = new DBConnection();
    if(!$db->connect())
      return false;

    $this->devices = $db->getUserDevices($this->user_id);
    $db->close();

    return !$this->devices;
  }
}
?>
