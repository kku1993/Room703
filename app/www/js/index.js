var app = {
  // Application Constructor
  initialize: function() {
    this.bindEvents();
  },

  // Bind Event Listeners
  //
  // Bind any events that are required on startup. Common events are:
  // 'load', 'deviceready', 'offline', and 'online'.
  bindEvents: function() {
    document.addEventListener('deviceready', this.onDeviceReady, false);
  },

  // deviceready Event Handler
  //
  // The scope of 'this' is the event. In order to call the 'receivedEvent'
  // function, we must explicity call 'app.receivedEvent(...);'
  onDeviceReady: function() {
    app.receivedEvent('deviceready');

    /* set up GCM */
    var pushNotification = window.plugins.pushNotification;
    pushNotification.register(
      app.gcmRegisterSuccessful, 
      app.gcmRegisterError,
      {"senderID":"769579813594", "ecb":"app.onGCMNotification"});
  },

  // Update DOM on a Received Event
  receivedEvent: function(id) {
    console.log('Received Event: ' + id);
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
        /* actual message delivery */
        console.log("received gcm message: " + e.message);
        alert('message = ' + e.message);
        break;
      case "error":
        console.log("GCM error: " + e.msg);
        alert("GCM error " + e.msg);
        break;
      default:
        alert("Unknown GCM event has occurred");
        break;
    }
  }
};
