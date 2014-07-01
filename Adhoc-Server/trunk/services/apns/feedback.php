<?php
function send_feedback_request() {
	
    //connect to the APNS feedback servers
    //make sure you're using the right dev/production server & cert combo!
    $apnsHost = 'ssl://feedback.push.apple.com';
    $apnsPort = 2196;
    $apnsCert = 'D:\AppServ\www\fanticker\apns\apns-dev.pem';
    $stream_context = stream_context_create();
    stream_context_set_option($stream_context, 'ssl', 'local_cert', $apnsCert);
    $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $errcode, $errstr, 60, STREAM_CLIENT_CONNECT, $stream_context);
    if(!$apns) {
        echo "ERROR $errcode: $errstr\n";
        return;
    }
 

    $feedback_tokens = array();
    //and read the data on the connection:
    while(!feof($apns)) {
        $data = fread($apns, 38);
        if(strlen($data)) {
            $feedback_tokens[] = unpack("N1timestamp/n1length/H*devtoken", $data);
        }
    }
    fclose($apns);
    return $feedback_tokens;
}
$tokens=send_feedback_request();
print "<pre>";
print_r($tokens);
?>