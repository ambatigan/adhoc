<?php
//$payload['aps'] = array('alert' => 'This is the alert text', 'badge' => 1, 'sound' => 'default');

$payload['aps'] = array('alert' => array('body' => 'This is the alert text', 'action-loc-key' => 'Answer'));
$payload['aps']['badge'] = 5;
$payload['aps']['sound'] = 'default';
$payload['aps']['match'] = '1';


/*{
	"aps" :
		{
			"alert" :
			{
				"body" : "Bob wants to play poker",
				"action-loc-key" : "PLAY"
			},
			"badge" : 5,
		},
	"acme1" : "bar",
	"acme2" : [ "bang",  "whiz" ]
}*/

//echo "<pre>";
//print_r($payload);
//echo "</pre>";

$payload = json_encode($payload);

$deviceToken = '993f607d3970a79dbe348c1f9bb2fca10d3125a8b425d3e6628c1d356308ab27';
//$deviceToken = 'bb0c2094272ce2d87806ee0cb2729e8af82d38f831ce3b9b5ba96d1b5916a203';
//$deviceToken = '993f607d3970a79dbe348c1f9bb2fca10d3125a8b425d3e6628c1d356308ab27';
//$deviceToken = 'bb0c2094272ce2d87806ee0cb2729e8af82d38f831ce3b9b5ba96d1b5916a203';

$apnsHost = 'gateway.sandbox.push.apple.com';
$apnsPort = 2195;
$apnsCert = 'D:\AppServ\www\fanticker\apns\apns-dev.pem';

$streamContext = stream_context_create();
stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);

$apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);

$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $deviceToken)) . chr(0) . chr(strlen($payload)) . $payload;

fwrite($apns, $apnsMessage);

?>