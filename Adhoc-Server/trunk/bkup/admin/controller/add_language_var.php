<?php

$heading = "Language Variable Management";
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
    $title = "Edit Language Variable";
else
    $title = "Add Language Variable";

include_once CK_EDITOR_PATH . 'ckeditor.php';
include_once CK_FINDER_PATH . 'ckfinder.php';
require_once(COMMON_CORE_MODEL . "language_var.class.php");
$language_var = new language_var();

foreach ($language_var as $k => $d) {
    if ($k != 'db') {
        $language_var->$k = (isset($_REQUEST[$k]) && $_REQUEST[$k] != '') ? $_REQUEST[$k] : $language_var->$k;
    }
}

$language_var->id = (isset($_REQUEST['id']) && $_REQUEST['id'] != '') ? $_REQUEST['id'] : $language_var->id;
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
///******* Assigning all posted data in local object *********//
///******* Insert / Update Case *********//
if ($_POST['LanguageVarFormSubmit']) {
    $validateData['required'] = array('name' => 'Language Variable Name is required');

    $errorMsg = $commonFunction->validate_form($_POST, $validateData);
    if ($language_var->checkexist() != false) {
        $errorMsg[] = "Language Variable Name already exists";
    }

    $cntlng = count($sitelanguages);
    $cnt = 0;
    foreach ($sitelanguages as $lng) {



        if (trim($_REQUEST['txt'][$lng['id']]) == "") {
            $errorMsg[] = "Language Text required " . $lng['language_name'];
            $cnt++;
        }
    }
    if ($cntlng == $cnt) {
        // $errorMsg[] = "Language Text required for any one language";
    }
    $obj_language_var = (array) $language_var;
    if (count($errorMsg) == 0) {
        if ($language_var->insertUpdate($language_var->id) == false) {
            $errorMsg[] = $language_var->error_message;
        } else {
            if (!isset($language_var->id)) {
                $commonFunction->Redirect('./?page=language_var_list&action=view&msg=1');
                exit;
            } else {
                $commonFunction->Redirect('./?page=language_var_list&action=view&msg=2');
                exit;
            }
        }
    }
}
///******* Insert / Update Case *********//
///******* $objcontact_method Case *********//
elseif ($language_var->id != '' && $action == 'edit') {
    $language_var->selectById();
    $obj_language_var = (array) $language_var;
}
///******* Edit Case *********//
//print "<pre>";  print_r($obj_language_var);die;
?>