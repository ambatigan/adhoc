<?php
if(isset($_REQUEST['id']) && $_REQUEST['id']!="" )
    $title= index_lang::$editlanguage;
else
    $title = index_lang::$addlanguage;
$heading = index_lang::$languagemgmt;

require_once(COMMON_CORE_MODEL."language.class.php");
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$flaglogodest =  UPLOAD_PATH."languages/";
///******* Assigning all posted data in local object *********//
$obj_language = new language();
$obj_language->id =(isset($_REQUEST['id']) && $_REQUEST['id'] != '')?$_REQUEST['id']:$obj_language->id;
if($obj_language->id!='' && $action=='edit')
{
	$obj_language->selectById();
    if($obj_language->id=="")
    {
        $obj_language->commonFunction->Redirect('./?page=language_list&action=view&msg=4');
    }
	$objlanguage = (array) $obj_language;
}
///******* Edit Case *********//
foreach($obj_language as $k=>$d)
{
	if($k!='db')
	{
		$obj_language->$k =(isset($_REQUEST[$k]) && $_REQUEST[$k] != '')?$_REQUEST[$k]:$obj_language->$k;
	}
}
///******* Insert / Update Case *********//
if($_POST['LanguageFormSubmit'])
{
    $delete=0;
    $validateData['required'] = array('language_name'=>index_lang::$languagenamereq, 'lng_code'=>index_lang::$languagecodereq,'status'=>index_lang::$languagestareq);

	$errorMsg = $commonFunction->validate_form($_POST, $validateData);
	if($obj_language->checkexist() != false){
		$errorMsg[] = index_lang::$languagenameexists;
	}
    if($obj_language->checkexistcode() != false){
		$errorMsg[] = index_lang::$languagecodeexists;
	}
	$objlanguage = (array) $obj_language;

    if(isset($_POST['status']) && $_POST['status'] == 2)
    {
        if($_POST['set_default'] == 1 || $_POST['defaultlanguage'] == 1)
         {
            //$obj_language->set_default = 0;
            $objlanguage['set_default'] = 0;
            $errorMsg[] = index_lang::$inactivecannotset;
         }
    }
    if(count($errorMsg) == 0)
    {
        if(isset($_REQUEST['set_default']) && $_REQUEST['set_default'] == 1)
        {
    		$obj_language->SetDefault();
    	}
      if(isset($_REQUEST['defaultlanguage']) && $_REQUEST['defaultlanguage'] == 1)
      {
          $default = $_REQUEST['defaultlanguage'];
    	}
      else {$default=0;}
        if($obj_language->insertUpdate($obj_language->id,$target_url,$default)==false)
		{
			$errorMsg[] = $obj_language->error_message;
		}
		else
		{
            if($delete==1)
            {
                @unlink($flaglogodest . $FileToDelete);
             }
            if(!isset($obj_language->id))
			{
				$commonFunction->Redirect('./?page=language_list&action=view&msg=1');
				exit;
			}
			else
			{
				$commonFunction->Redirect('./?page=language_list&action=view&msg=2');
				exit;
			}
		}
	}
}
///******* Insert / Update Case *********//
?>