<?php
/*
 *  @author kku
 *  @comment common database library class for push message server
 */
class DBConnection {
	private $db = NULL;

	function __construct() {
	}
	
	function __destruct() {
		$this->close();
	}
	
	public function connect(){
		require_once 'db_config.php';
		
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if ($this->db->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " 
        . $mysqli->connect_error;
			$this->db = NULL;
			return false;
		}
		
		return true;
	}
	
	private function prepareStatement($query){
		$stmt = $this->db->prepare($query);		
		if(!$stmt)
			return NULL;	
		return $stmt;
	}
	
	private function hashPassword($password) {
		return hash("sha256", $password);
	}
	
	/*
	 * ENSURES: returns NULL if addUser failed, user_id if successful 
	 */
	public function addUser($firstName, $lastName, $pw, $email) {
		$pw = $this->hashPassword($pw);
	
		$query = "INSERT INTO user(
			user_first_name, 
			user_last_name, 
			user_password, 
			user_email, 
			user_join_time) 
			VALUES(?, ?, ?, ?, NOW());
		";
			
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("ssss", $firstName, $lastName, $pw, $email);
		if(!$bind)
			return NULL;
		
		$exec = $stmt->execute();
		if(!$exec)
			return NULL;
			
		return $stmt->insert_id;
	}
	
	/*
	 *	ENSURES: returns user_id of the user with the corresponding email,
	 *		NULL if not match was found
	 */
	public function getUID($email){
		$query = "SELECT user_id FROM user WHERE user_email = ?";
		
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("s", $email);
		if(!$bind)
			return NULL;
		
		$exec = $stmt->execute();
		if(!$exec)
			return NULL;
			
		$stmt->store_result();
			
		if($stmt->num_rows == 0)
			return NULL;
			
		$stmt->bind_result($uid);
		$stmt->fetch();
		
		return $uid;
	}

  /* returns Person object */
  public function getUser($uid){
    require_once("../common/person.php");
		$query = "SELECT user_first_name, user_last_name FROM user WHERE user_id = ?";
		
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("i", $uid);
		if(!$bind)
			return NULL;
		
		$exec = $stmt->execute();
		if(!$exec)
			return NULL;
			
		$stmt->store_result();
			
		if($stmt->num_rows == 0)
			return NULL;
			
		$stmt->bind_result($fname, $lname);
		$stmt->fetch();
		
		return new Person($uid, $fname, $lname);
  }
	
	/*
	 *	ENSURES: returns true if the password is correct, false otherwise
	 */
	public function verifyPassword($uid, $pw){
		$query = "SELECT user_password FROM user WHERE user_id = ?";
		
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("i", $uid);
		if(!$bind)
			return false;
		
		$exec = $stmt->execute();
		if(!$exec)
			return false;
			
		$stmt->store_result();
			
		if($stmt->num_rows == 0)
			return false;
			
		$stmt->bind_result($stored_pw);
		$stmt->fetch();
		
		$pw = $this->hashPassword($pw);
		
		return (strcmp($pw, $stored_pw) == 0);
	}

  /* getRoomID: get the room id of a user id 
   * ENSURES: returns NULL on fail 
   */
  public function getRoomID($uid){
		$query = "SELECT room_id FROM room_member WHERE user_id = ?";
		
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("i", $uid);
		if(!$bind)
			return NULL;
		
		$exec = $stmt->execute();
		if(!$exec)
			return NULL;
			
		$stmt->store_result();
			
		if($stmt->num_rows == 0)
			return NULL;
			
		$stmt->bind_result($rid);
		$stmt->fetch();
		
		return $rid;
  }

  /* getRoomItems: get all items of a room 
   * ENSURES: returns NULL on fail, an array of items otherwise
   */
  public function getRoomItems($room_id){
		$query = "SELECT 
      item_id, item_name, item_price, item_status, item_votes, room_id 
      FROM items WHERE room_id = ?";
		
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("i", $room_id);
		if(!$bind)
			return NULL;
		
		$exec = $stmt->execute();
		if(!$exec)
			return NULL;
			
		$stmt->store_result();
			
		if($stmt->num_rows == 0)
			return NULL;
			
    $items = array();
		$stmt->bind_result($item_id, $item_name, $item_price, $item_status, 
      $item_votes, $item_room_id);

		while($stmt->fetch()){
      $item_price = round($item_price, 2);

      $items[] = array(
        "item_id" => $item_id,
        "item_name" => $item_name,
        "item_price" => $item_price,
        "item_status" => $item_status,
        "item_votes" => $item_votes,
        "room_id" => $room_id
      ); 
    }
		
		return $items;
  }

  public function addItem($item_name, $item_price, $user_id, $room_id){
		$query = "INSERT INTO items(
      item_name,
      item_price,
      item_status,
      item_votes,
      user_id,
      room_id)
			VALUES(?, ?, '1', '1', ?, ?);
		";
			
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("sdii", $item_name, $item_price, $user_id, 
      $room_id);
		if(!$bind)
			return NULL;
		
		if(!$stmt->execute())
			return NULL;
			
		return $stmt->insert_id;
  }

  /* returns info on 1 item */
  public function getItem($item_id){
    $query = "SELECT item_name, item_price, item_status, item_votes, user_id 
      FROM items WHERE item_id = ?";

		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("i", $item_id);
		if(!$bind)
			return NULL;
		
		if(!$stmt->execute())
			return NULL;

    $stmt->store_result();
    if($stmt->num_rows() == 0)
      return NULL;

    $stmt->bind_result($item_name, $item_price, $item_status, $item_votes, $user_id);
    $stmt->fetch();

    /* person who added this item */
    $user = $this->getUser($user_id);

    $ret = array(
      "item_name" => $item_name,
      "item_price" => round($item_price, 2),
      "item_status" => $item_status,
      "item_votes" => $item_votes,
      "purchaser_first_name" => $user->first_name,
      "purchaser_last_name" => $user->last_name
    );

    return $ret;
  }
	
	/*
	 * 	hash GCM Registration ID with SHA256 to use as key in checking 
	 *		duplicate devices
	 */
	private function hashGCMRegID($id){
		return hash("sha256", $id);
	}
	
  /* returns the device_id with the gcm_reg_id 
   * ENSURES: return NULL on failure 
   */
  public function getAndroidDeviceID($gcm_reg_id){
		$query = "SELECT device_id FROM android_device WHERE gcm_reg_id = ?;";
		
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("s", $gcm_reg_id);
		if(!$bind)
			return NULL;
		
		if(!$stmt->execute())
			return NULL;

    $stmt->store_result();
    if($stmt->num_rows() == 0)
      return NULL;

    $stmt->bind_result($device_id);
    $stmt->fetch();

    return $device_id;
  }

	/*
	 * ENSURES: returns NULL if addAndroidDevice failed, device_id if successful 
	 */
	public function addAndroidDevice($uid, $gcm_reg_id){
		$gcm_reg_id_hash = $this->hashGCMRegID($gcm_reg_id);
		
		$query = "INSERT INTO android_device(
			user_id, 
			gcm_reg_id,
			gcm_reg_id_hash)
			VALUES(?, ?, ?)
			ON DUPLICATE KEY UPDATE gcm_reg_id = ?, gcm_reg_id_hash = ?;
			";
		
		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("issss", $uid, $gcm_reg_id, $gcm_reg_id_hash, 
      $gcm_reg_id, $gcm_reg_id_hash);
		if(!$bind)
			return NULL;
		
		if(!$stmt->execute())
			return NULL;
			
    /* workaround to ensure the caller doesn't mistaken duplicate insert for 
     * error */
    if($stmt->affected_rows)
		  return $stmt->insert_id;
    else
      return -1;
	}
		
	/*
	 * ENSURES: returns a list of Person object who are roommates 
	 *		of $uid living the room $room_id
	 */
	public function findRoommates($uid, $room_id){
		$query = "SELECT user_id 
      FROM room_member WHERE room_id = ? AND user_id <> ?";

		$stmt = $this->prepareStatement($query);
		$bind = $stmt->bind_param("ii", $room_id, $uid);
		if(!$bind)
			return NULL;
		
		$exec = $stmt->execute();
		if(!$exec)
			return NULL;
			
		$stmt->store_result();
			
		if($stmt->num_rows == 0)
			return NULL;
			
    require_once "../common/person.php";
		
		$roommates = array();
    $stmt->bind_result($user_id);

		while($stmt->fetch()){
      $mate = new Person($user_id, "", "");
      $roommates[] = $mate;
    }
		
		return $roommates;		
	}
	
	/*
	 *	get a list of registered devices of a user
	 *	ENSURES: returns a list of ClientDevice that belong to the user,
   *    return NULL on failure
	 */
	public function getUserDevices($uid){
    require_once "../common/constants.php";
    require_once "../common/client_device.php";

    //get android devices
		$query = "SELECT 
      device_id, gcm_reg_id 
      FROM android_device 
      WHERE user_id = ?";

		$stmt = $this->prepareStatement($query);
		if(!$stmt->bind_param("i", $uid))
			return NULL;
		
		if(!$stmt->execute())
      return NULL;
			
		$stmt->store_result();
	
    $stmt->bind_result($device_id, $gcm_reg_id);
		$android = array();
		
    while($stmt->fetch()){
      $info = array(
        'gcm_reg_id' => $gcm_reg_id,
        'device_id' => $device_id
      );

      $android[] = new ClientDevice(ANDROID_DEVICE, $uid, $info);
    }

		return $android;
	}
	
	public function close() {
    /*
		if ($this->db != NULL)
			$this->db->close();
      */
	}
}
?>
