<?php
ini_set('memory_limit', '2G');
ini_set('max_execution_time', 0);

//$payload['aps'] = array('alert' => 'This is the alert text', 'badge' => 1, 'sound' => 'default');

/*$payload['aps'] = array('alert' => array('body' => 'This is the alert text', 'action-loc-key' => 'Answer'));
 $payload['aps']['badge'] = 5;
 $payload['aps']['sound'] = 'default';
 $payload['aps']['match'] = '1';

 $payload = json_encode($payload);
 $deviceToken = '55426365a7c2444066d857f322da36143bf2b8a06464f678353cdee8d6604588';
 $apnsHost = 'gateway.sandbox.push.apple.com';
 $apnsPort = 2195;
 $apnsCert = dirname(__FILE__) . '\\' .'server_certificates_bundle_sandbox.pem';


 $streamContext = stream_context_create();
 stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
 $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
 $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;
 fwrite($apns, $apnsMessage);*/
			

class apnNotifications{

	public $payload;
	public $certFile;
	public $badge;
	public $sound;
	public $match;
	public $apnsCert;

	public function __construct(){
			
               //$this->apnsCert = dirname(__FILE__) . '\\' .'apple_push_notification_production.pem';
               $this->apnsCert = dirname(__FILE__) . '\\'.'pHest_Dev_push_cer.pem';
              // echo $this->apnsCert;exit;
	}
	

	// function for sent notificatoin to multiple devices
	public function sendNotification($deviceTokens,$message,$action_loc_key = 'Answer',$badge,$sound,$match,$from){

               /* if($_SERVER['http_host'] == '192.168.10.2'){
                    $apnsHost = 'gateway.sandbox.push.apple.com';
                }else{
                    $apnsHost = 'gateway.push.apple.com';
                }              */

                // Enable/Disable Specific Device Notification
                $test = false;
                $apnsHost = 'gateway.push.apple.com';
                //$apnsHost = 'gateway.sandbox.push.apple.com';
                // echo "test";exit;
	            $apnsPort = 2195;

		      foreach($deviceTokens as $deviceToken){
		          
                        $payload = array();
                        $payload['aps'] = array('alert' => array('body' => $message, 'action-loc-key' => $action_loc_key));

                        mysql_query("UPDATE devicetoken set badge_number=badge_number+1 WHERE device_token = '".$deviceToken['device_token']."'");

                        // get badge from device token
                        if(@mysql_num_rows($bg_rs = mysql_query("SELECT badge_number FROM devicetoken WHERE device_token = '".$deviceToken['device_token']."'")) > 0){
                            $data = mysql_fetch_assoc($bg_rs);
                            //print_r($data);
                            $bg = $data['badge_number'];
                            $payload['aps']['badge_number'] = intval($bg); // 5
                            
                        }
                        
                        $payload['aps']['sound'] = $sound; // default
                        $payload['aps']['match'] = $match;   // '1'
                        //$payload['aps']['from'] = $from;   // '1' 'business',promotion,category
                        
                         $payload['photo_user_id'] = $from['photo_user_id'];
                         $payload['login_user_id'] = $from['login_user_id'];
                         $payload['photo_id'] = $from['photo_id'];
                        
                        $payload = json_encode($payload);
                                              
                        $options = array('ssl' => array(
                            'local_cert' => $this->apnsCert
                          ));

                        // if in test, send only to one device
                        if($test){
                            $token_list = array('6f44395d 0bbd6afd 64bfba87 10047bca bafb3a8d 1bfa5725 e398d0a2 bbbe3359','a303d318a2da3f0c7cefc35a46ccc99c1dc9793c10a9a72e69366e4702f80907');
                            if(in_array($deviceToken['device_token'],$token_list)){
                                $streamContext = @stream_context_create();
                                stream_context_set_option($streamContext, $options);
                                $apns = @stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
                                $apnsMessage = chr(0) . chr(0) . chr(32) . @pack('H*', str_replace(' ', '', $deviceToken['device_token'])) . chr(0) . chr(strlen($payload)) . $payload;
                                @fwrite($apns, $apnsMessage);
                                
                            }
                            
                        }else{
                            
                            $streamContext = @stream_context_create();
                            stream_context_set_option($streamContext, $options);
                            $apns = @stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
                            $apnsMessage = chr(0) . chr(0) . chr(32) . @pack('H*', str_replace(' ', '', $deviceToken['device_token'])) . chr(0) . chr(strlen($payload)) . $payload;
                            @fwrite($apns, $apnsMessage);
                        }
            		}
            	}
            }
?>