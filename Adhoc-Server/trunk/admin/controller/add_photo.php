<?php

$username_id = logged_in_user::id();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if($_GET['action']=='edit' && $_GET['id']!=''){
    $heading = "Photo Management";
    $title = "Edit Photo";
}
else{
    $heading = "Photo Management";
    $title = "Add Photo";
}
 
require_once(COMMON_CORE_MODEL . "users.class.php");
require_once(COMMON_CORE_MODEL . "photo.class.php");
$objPhoto = new photo();
$objPhoto->id = (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : $objPhoto->id;
$photo = array();

$objUser = new users();
$user = $objUser->select("",'where deleted="0" and status="Active"',"id","ASC");

///******* Edit Case *********//
if ($objPhoto->id != '' && $action == 'edit')
{


    $objPhoto->selectById();  
    if ($objPhoto->id == "")
    {
    	$_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "Record does not exist";
        $objPhoto->commonFunction->Redirect('./?page=photo_listing');
    }
    else
    {

        $photo = (array) $objPhoto;

    }
} 

///******* Insert / Update Case *********//
if (isset($_POST['addEditPhotoSubmit']))
{
    foreach ($objPhoto as $k => $d)
    {
        if($k!='password'){
            $objPhoto->$k = (isset($_POST[$k])) ? htmlspecialchars($_POST[$k], ENT_QUOTES, 'UTF-8') : $objPhoto->$k;
        }
    }
    $objPhoto->user_id = $username_id;

    if($_GET['action']=='edit' && $_GET['id']!='')
    {
         $validateData['required'] = array('name' => 'Please provide Name',
                                           'tag' => 'Please provide Tag'
                                           );
    }
    else
    {
         $validateData['required'] = array('name' => 'Please provide Name',
                                           'tag' => 'Please provide Tag'
                                           );
    }

    $errorMsg = $commonFunction->validate_form($_POST, $validateData);



    $file = ($_FILES['image']['name']);
    if($file=='' && $action =='add')
    {
        $errorMsg[] = 'Please provide Image';
    }


    $photo = (array) $objPhoto;
    //echo "<pre>"; print_R($_FILES);
    //echo "<pre>"; print_r($photo); exit;

    if (count($errorMsg) == 0) {
        if($objPhoto->insertUpdate($objPhoto->id) == false)
        {

            $errorMsg[] = $objPhoto->error_message;
        }
        else
        {
            if (!isset($objPhoto->id))
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Photo successfully added";
               $commonFunction->Redirect('./?page=photo_listing');
               exit;
            } 
            else 
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Photo successfully updated";
               $commonFunction->Redirect('./?page=photo_listing');
               exit;
            }
        }
    }
}

if ($objPhoto->id != '' && $action == 'statusupdate')
{
                $currentStatus = $_REQUEST['status'];
                if($currentStatus == 'Active'){
                $currentStatus = 'Inactive';
                } else {
                $currentStatus = 'Active';

                }
                $commentId = $_REQUEST['id'];
                $photo = $objPhoto->updateStatus($currentStatus,$commentId);
                $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Photo successfully updated";
               $commonFunction->Redirect('./?page=photo_listing');
               exit;

}
///******* Insert / Update Case *********//
?>