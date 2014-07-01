<?php
$title = "Notify Users";
$heading = "Push Notification";

require_once(COMMON_CORE_MODEL . "gcm_users.class.php");
$objGcmUsers = new gcm_users();

// show success message
if (isset($_REQUEST['msg']) && intval($_REQUEST['msg']))
{
    if ($_REQUEST['msg'] == '1') {
        $successMsg[] = "Notification sent successfully";
    }
}
// echo 
if (isset($_POST['blogFormSubmit']) && $_POST['blogFormSubmit'] == 'Notify Users') {

    $notify_message = $_POST['notfication-message'];
    $validateData['required'] = array('notfication-message' => "Notification Message required");
    $errorMsg = $commonFunction->validate_form($_POST, $validateData);

    if (empty($errorMsg))
    {
        $limit = GSM_USERS_MAIL_LIMIT;
        // GCM Users Listing
        $message = array("data" => $notify_message);

        $GcmUsersCnt =  $objGcmUsers->cntRecords();
        $senttimes = ceil($GcmUsersCnt/$limit);

        $start_limit = 0;
        $final_limit =  GSM_USERS_MAIL_LIMIT;
        $cnt = 0;
        $errcnt=0;
        for($i=0;$i<$senttimes;$i++)
        {
            $regId = array();
            $GcmUsersList = $objGcmUsers->selectGcmUsers($start_limit, GSM_USERS_MAIL_LIMIT);
            $start_limit = $final_limit;
            $final_limit =  $final_limit+GSM_USERS_MAIL_LIMIT;
            // Send Notification messages
            foreach ($GcmUsersList as $key => $gcmusers)
            {
                $regId[] = $gcmusers['gcm_regid'];
            }
            if (!empty($regId))
            {
                $sendnotify = $commonFunction->send_notification($regId, $message);
                if($sendnotify[0]->success)
                {
                    $cnt++;
                }
                else
                {
                    $errcnt++;
                }
            }
        }
         if($cnt > 0)
         {
            //Display error message
            $commonFunction->Redirect('./?page=push-notification&action=view&msg=1');
            exit;
         }
    }
}
?>