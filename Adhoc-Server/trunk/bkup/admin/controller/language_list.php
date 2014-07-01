<?php


//$title = index_lang::$listlanguage;
//$heading = index_lang::$languagemgmt;



require_once(COMMON_CORE_MODEL."language.class.php");
$flaglogodest =  UPLOAD_PATH."languages/";
$obj_language = new language();

$objPagging= new pagging(RECORDS_PER_PAGE,"post");
$pageno = (isset($_REQUEST['pageno']))?$_REQUEST['pageno']:1;
$sort_by =(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != '')?$_REQUEST['sort_by']:"l.id ";
$sort_order =(isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '')?$_REQUEST['sort_order']:"DESC";

if($_REQUEST['msg'])
{
    if($_REQUEST['msg']==1)
    {
        $successMsg[] = index_lang::$lng_add_success;
    }
	if($_REQUEST['msg']==2)
    {
        $successMsg[] = index_lang::$lng_upd_success;
    }

    if($_REQUEST['msg']==3)
    {
    	$successMsg[] = index_lang::$lng_del_success;
    }
}

//DELETE
if($_REQUEST['id'] !='' && $_REQUEST['action']=='delete')
{
	$obj_language->id = $_REQUEST['id'];
	$obj_language->selectById();
	if($obj_language->flag_image !='' && file_exists($flaglogodest.$obj_language->flag_image)){
		unlink($flaglogodest.$obj_language->flag_image);
	}
	if($obj_language->delete())
	{
		$commonFunction->Redirect('./?page=language_list&action=view&&msg=3');
	}
}
$where="";
if(isset($_REQUEST['search_cms']) && $_REQUEST['search_cms']!="")
{
    $successMsg = array();
    $search_faq = $_REQUEST['search_cms'];

	$objlanguage = $obj_language->select($objPagging,"where l.language_name like '%".mysql_real_escape_string($search_faq)."%'",$sort_by, $sort_order);
	$paggingRecord=$objPagging->InfoArray();
}
else{
	$objlanguage = $obj_language->select($objPagging,"where 1=1",$sort_by, $sort_order);
	$paggingRecord=$objPagging->InfoArray();
}

$extraVars = (isset($_GET)?$_GET:array());
$pagging = $objPagging->print_pagging($extraVars,ADMIN_PAGGING_STYLE);
$starting_record = $paggingRecord['START_OFFSET'];
$ending_record = $paggingRecord['END_OFFSET'];
$total_records = $paggingRecord['TOTAL_RESULTS'];
//print "<pre>";print_r($objlanguage);die;
?>