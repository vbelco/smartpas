/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
var host = 'broker.hivemq.com';	// hostname or IP address

var port = 8000;
var path = "/mqtt";
var topic = 'out_smartpas';		// topic to subscribe to
var topic_lwt = 'lwt_smartpas'; //topic na lWT spravy
var topic_online = 'online_smartpas'; //topic na kontrolu online dostupnosti citaciek
var useTLS = false;
var username = null;
var password = null;
var clientId = "web_" + parseInt(Math.random() * 100, 10);

// path as in "scheme:[//[user:password@]host[:port]][/]path[?query][#fragment]"
//    defaults to "/mqtt"
//    may include query and fragment
//
// path = "/mqtt";
// path = "/data/cloud?device=12345";

cleansession = true;       
        
var mqtt;
var reconnectTimeout = 2000;
var pripojene = 0;

function MQTTconnect() {
    mqtt = new Paho.MQTT.Client(
	host,
	port,
	clientId );
        
    var options = {
        timeout: 3,
        useSSL: useTLS,
        cleanSession: cleansession,
        onSuccess: onConnect,
        onFailure: onFailure
    };
            
    mqtt.onConnectionLost = onConnectionLost;
    mqtt.onMessageArrived = onMessageArrived;
    if (username !== null) {
        options.userName = username;
        options.password = password;
    }
    console.log("Host="+ host + ", port=" + port + " TLS = " + useTLS + " username=" + username + " password=" + password);
    mqtt.connect(options);
}//end function MQTTconnect
    
function onFailure(message) {
    console.log("Connection failed: " + message.errorMessage + "Retrying");
    setTimeout(MQTTconnect, reconnectTimeout);
}
    
function onConnect() {
    console.log('Connected to ' + host + ':' + port + path);
    // Connection succeeded; subscribe to our topic
    mqtt.subscribe(topic, { qos: 2 }); //prihlasenie sa do tohto topicu
    mqtt.subscribe(topic_lwt, { qos: 2 });
    mqtt.subscribe(topic_online, { qos: 2 });
    console.log(topic + "," + topic_lwt + "," + topic_online);
    message = new Paho.MQTT.Message("Hello, here is web");
    message.destinationName = "in_smartpas";
    mqtt.send(message);
    pripojene = 1;
}
    
function onConnectionLost(response) {
    pripojene = 0;
    setTimeout(MQTTconnect, reconnectTimeout);
    console.log("connection lost: " + responseObject.errorMessage + ". Reconnecting");
}
    
function onMessageArrived(message) {
    var topic = message.destinationName;
    var payload = message.payloadString;
        
    switch  (topic){
        case "out_smartpas":
            var identifikator = '#kontrola_'+payload;
            var cell_identifikator = '#cell_kontrola_'+payload;
            $(cell_identifikator).removeClass("warning");
            $(cell_identifikator).addClass("success");
            $(identifikator).text("pripojene");
            console.log(identifikator +">"+ topic+": "+payload);
            break;

        case "lwt_smartpas":
            var identifikator = '#kontrola_'+payload;
            var cell_identifikator = '#cell_kontrola_'+payload;
            $(cell_identifikator).removeClass("success");
            $(cell_identifikator).addClass("warning");
            $(identifikator).text("offline");
            console.log(identifikator +">"+ topic+": "+payload);
            break;
    } //end switch    
}//end function onMessageArrived
    
  
//kontrola dostupnosti mojich citaciek
function Kontrola_dostupnosti_citacky( id_citacky ) {
    if (pripojene){ //aby nekontrolovalo pri vypadku pripojenia
        var message =  new Paho.MQTT.Message( "kontrola_"+id_citacky );
        message.destinationName = "in_smartpas";
        message.qos = 2;
        mqtt.send(message);
        console.log("Sending message to topic in_smartpas: kontrola_"+id_citacky);
    }    
}//end  function kontrola dostupnosti citaciek

//kontrola dostupnosti mojich citaciek
//urobime 3 pokusy napripojenie
function Prva_kontrola_dostupnosti_citacky( id_citacky ) {
    for (i=0; i<3; i++){
        setTimeout(
                function(){
                    if (pripojene){ //aby nekontrolovalo pri vypadku pripojenia
                        var message =  new Paho.MQTT.Message( "kontrola_"+id_citacky );
                        message.destinationName = "in_smartpas";
                        message.qos = 2;
                        mqtt.send(message);
                        console.log("PRVA> Sending message to topic in_smartpas: kontrola_"+id_citacky);
                    }//end if
                }, 1000 );//end function setTimeout
    }//end for
}//end  function kontrola dostupnosti citaciek
