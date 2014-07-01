<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';

if($_GET['action']=='edit' && $_GET['id']!=''){
    $heading = "Prompt Management";
    $title = "Edit Prompt";
}
else{
    $heading = "Prompt Management";
    $title = "Add Prompt";
}

require_once(COMMON_CORE_MODEL . "prompt.class.php");

$objPrompt = new prompts();


$objPrompt->id = (isset($_GET['id']) && $_GET['id'] != '' && is_numeric($_GET['id'])) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : $objPrompt->id;
$prompt = array();

///******* Edit Case *********//
if ($objPrompt->id != '' && $action == 'edit')
{
    $objPrompt->selectById();
    if ($objPrompt->id == "")
    {
    	$_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "Record does not exist";
        $objPrompt->commonFunction->Redirect('./?page=prompt_listing');
    }
    else
    {
        $prompt = (array) $objPrompt;
    }
}

///******* Insert / Update Case *********//
if (isset($_POST['addEditPromptSubmit']))
{
       foreach ($objPrompt as $k => $d)
    {
      
            $objPrompt->$k = (isset($_POST[$k])) ? htmlspecialchars($_POST[$k], ENT_QUOTES, 'UTF-8') : $objPrompt->$k;
       
    }
    if($_GET['action']=='edit' && $_GET['id']!='')
    {
         $validateData['required'] = array('prompt' => 'Please provide prompt');
    }
    else
    {
         $validateData['required'] = array( 'prompt' => 'Please provide prompt');
    }
    $errorMsg = $commonFunction->validate_form($_POST, $validateData);
    $prompt = (array) $objPrompt;

    if (count($errorMsg) == 0) {
        if($objPrompt->insertUpdate($objPrompt->id) == false)
        {
            $errorMsg[] = $objPrompt->error_message;
        }
        else
        {
            if (!isset($objPrompt->id))
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Prompt successfully added";
               $commonFunction->Redirect('./?page=prompt_listing');
               exit;
            }
            else
            {
               $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Prompt successfully updated";
               $commonFunction->Redirect('./?page=prompt_listing');
               exit;
            }
        }
    }
}

if ($objPrompt->id != '' && $action == 'statusupdate')
{
                $currentStatus = $_REQUEST['status'];
                if($currentStatus == 'Active'){
                $currentStatus = 'Inactive';
                } else {
                $currentStatus = 'Active';

                }
                $promptId = $_REQUEST['id'];
                $photo = $objPrompt->updateStatus($currentStatus,$promptId);
                $_SESSION['message_type'] = 'success';
               $_SESSION['message'] = "Prompt successfully updated";
               $commonFunction->Redirect('./?page=prompt_listing');
               exit;

}
///******* Insert / Update Case *********//
?>