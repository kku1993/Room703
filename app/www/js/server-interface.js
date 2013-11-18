server = {
  address: "192.168.1.2/client_interface/",

  /* getJSON: convert reponse into a json object */
  getJSON: function(s){
    try{
      var jobj = jQuery.parseJSON(s);
      return jobj;
    }catch(err){
      return null;
    }
  },

  login: function(email, pw){
    if(email != "" && pw != ""){
      $url = server.address + "login.php";
      $.post(
        url, 
        {user_email: email, user_pw: pw}, 
        function(res){
          if(res.success){
            return true;
          } 
          else{
            return false;
          }
        },
        "json");
    }

    return false;
  }

};
