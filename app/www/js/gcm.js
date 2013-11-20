gcm ={
  sender_id: "769579813594",

  register: function(){
    /* set up GCM */
    console.log("registering GCM");

    var pushNotification = window.plugins.pushNotification;
    pushNotification.register(
      gcm.gcmRegisterSuccessful, 
      gcm.gcmRegisterError,
      {"senderID": gcm.sender_id, "ecb":"gcm.onGCMNotification"});
  },

  gcmRegisterSuccessful: function(result){
    console.log("GCM registration successful: " + result);
  },

  gcmRegisterError: function(error){
    console.log("GCM registration error " + error);
  },

  onGCMNotification: function(e){
    switch(e.event){
      case "registered":
        if(e.regid.length > 0){
          console.log("Regid " + e.regid);
          window.localStorage["gcm_reg_id"] = e.regid;
        }
        break;
      case "message":
        if(e.foreground){
          /* notification occurred when the app is in the foreground */
          alert("received " + e.message);
        }
        else{
          console.log("received gcm message: " + e.message);
        }
        break;
      case "error":
        console.log("GCM error: " + e.msg);
        break;
      default:
        alert("Unknown GCM event has occurred");
        break;
    }
  },

  /* send gcm reg id to the server */
  sendGCMRegID: function(){
    var gcm_reg_id = window.localStorage["gcm_reg_id"];
    var user_id = window.localStorage["user_id"];
    var user_pw = window.localStorage["user_pw"];

    if(gcm_reg_id != undefined && user_id != undefined 
      && user_pw != undefined){
      var data = {gcm_reg_id: gcm_reg_id, user_id: user_id, 
        user_pw: user_pw};

      var success = function(res){
        if(res == null){
          console.log("Failed to send GCM Registration ID to server");
        }
        else{
          if(res.success){
            console.log("registration successful");
          }
          else{
            alert("server error");
          }
        }
      }

      var fail = function(e){
        console.log(e.message);
        alert("server error");
      }

      server.request("/register_android.php", data, "registerAndroid", 
        success, fail);
    }
    else{
      alert("No GCM registration id");
    }
  }
};
