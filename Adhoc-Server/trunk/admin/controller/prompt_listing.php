<?php
$title = "Prompt Listing";
$heading = "Prompt Listing";

require_once(COMMON_CORE_MODEL . "prompt.class.php");
$objPrompt = new prompts();
$objPagging = new pagging(RECORDS_PER_PAGE, "post");
$pageno = (isset($_REQUEST['pageno']))?$_REQUEST['pageno']:1;
$sort_by = (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '') ? $_REQUEST['sort_by'] : "id";
$sort_order = (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '') ? $_REQUEST['sort_order'] : "DESC";

//delete
if ($_REQUEST['id'] != '' && $_REQUEST['action'] == 'delete') {
    if(!is_numeric($_REQUEST['id'])){
        $_SESSION['message_type'] = 'error';
        $_SESSION['message'] = "No record found";
        $commonFunction->Redirect('./?page=prompt_listing');
        exit;
    }
    else{
        $objPrompt->id = $_REQUEST['id'];
        $objPrompt->selectById();
        if ($objPrompt->delete()) {
            if ($objPrompt->deleteGreetings()) {
                $_SESSION['message_type'] = 'success';
                $_SESSION['message'] = "Prompt successfully deleted";
                if(isset($_REQUEST['pageno'])){
                    $etvars = '&pageno='.$_REQUEST['pageno'];
                }
                if(isset($_REQUEST['searchtext'])){
                        $etvars .= '&searchtext='.$_REQUEST['searchtext'];
                }
                if(isset($_REQUEST['search'])){
                        $etvars .= '&search='.$_REQUEST['search'];
                }
                $commonFunction->Redirect('./?page=prompt_listing'.$etvars);
            }
        }
    }
}

if ($_REQUEST['searchtext'] != "")
{
        $search_prompt = trim($_REQUEST['searchtext']);
        $prompt = $objPrompt->select($objPagging, " WHERE prompt like '%" . mysql_real_escape_string($search_prompt) . "%' and deleted=0 ", $sort_by, $sort_order);
    $paggingRecord = $objPagging->InfoArray();
}
else
{
        $prompt = $objPrompt->select($objPagging, " WHERE deleted=0 ", $sort_by, $sort_order);
        $paggingRecord = $objPagging->InfoArray();
}

$extraVars = (isset($_GET) ? $_GET : array());
$pagging = $objPagging->print_pagging($extraVars, ADMIN_PAGGING_STYLE);
$starting_record = $paggingRecord['START_OFFSET'];
$ending_record = $paggingRecord['END_OFFSET'];
$total_records = $paggingRecord['TOTAL_RESULTS'];
?>