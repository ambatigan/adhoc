<?php

require_once(COMMON_CORE_MODEL."/check_login.php");
require_once(COMMON_CORE_MODEL."/class.config_vars.php");
require_once(COMMON_CORE_MODEL."/class.config_var_drop_down_options.php");

$objConfigVARs = new config_vars($dbAccess);
$objPagging = new pagging(RECORDS_PER_PAGE);

$var_no = (isset($_REQUEST['config_var_no']) && $_REQUEST['config_var_no'] != '')?$_REQUEST['config_var_no']:"0";
$objConfigVARs->config_var_id =(isset($_REQUEST['config_var_id']) && $_REQUEST['config_var_id'] != '')?$_REQUEST['config_var_id']:$objConfigVARs->config_var_id;
$objConfigVARs->config_var_type =(isset($_REQUEST['config_var_type']) && $_REQUEST['config_var_type'] != '')?$_REQUEST['config_var_type']:$objConfigVARs->config_var_type;
if ($objConfigVARs->config_var_type >=1 && $objConfigVARs->config_var_type <= 3) //textbox, textarea or checkbox
{
	$objConfigVARs->config_var_value =(isset($_REQUEST['config_var_value']) && $_REQUEST['config_var_value'] != '')?$_REQUEST['config_var_value']:$objConfigVARs->config_var_value;
}

//ADD AND UPDATE
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'submit')
{    		
	$smarty->assign("config_var_value",$objConfigVARs->config_var_value);
	
	//INSERT OR UPDATE
	if ($objConfigVARs->config_var_type >=1 && $objConfigVARs->config_var_type <= 3) //textbox, textarea or checkbox
	{
		if(!trim($objConfigVARs->config_var_value) && $objConfigVARs->config_var_type != 3) // if checkbox we allow unticked
		{
			$error_Msg = "##NG_CONFIG_VARS_ENTER_VALUE##" . " ##NG_NO##"." ". $var_no . " ";
			$smarty->assign("error_Msg",$error_Msg);				
		}						
		else if($objConfigVARs->insertUpdate($objConfigVARs->config_var_id)==false)
		{
			$smarty->assign("error_Msg",$objConfigVARs->error_message);
		}
		else
		{			
			$smarty->assign("sucess_Msg","##NG_NO##"." ". $var_no . " " . $objConfigVARs->sucess_message);
		}	
	}
	else if ($objConfigVARs->config_var_type >= 4 && $objConfigVARs->config_var_type <= 5) //dropdowns/multi-select dropdowns
	{		
		$objConfigVAR_DD = new config_var_drop_down_options($dbAccess);
	
		$objConfigVARs->drop_down_id =(isset($_REQUEST['drop_down_id']) && $_REQUEST['drop_down_id'] != '')?$_REQUEST['drop_down_id']:$objConfigVARs->drop_down_id;
			
		$objConfigVAR_DD->config_var_id = $objConfigVARs->config_var_id;
		if (isset($_REQUEST['drop_down_option_id']))
			$objConfigVAR_DD->deleteDropDownOptions($objConfigVAR_DD->config_var_id);
		
		if ($objConfigVARs->config_var_type == 4) //dropdown
		{				
			$objConfigVAR_DD->drop_down_option_id =(isset($_REQUEST['drop_down_option_id']) && $_REQUEST['drop_down_option_id'] != '')?$_REQUEST['drop_down_option_id']:$objConfigVAR_DD->drop_down_option_id;	
			
			
			if(!trim($objConfigVAR_DD->drop_down_option_id))
			{				
				$error_Msg = "##NG_CONFIG_VARS_ENTER_VALUE##" . " ##NG_NO##"." ". $var_no . " ";
				$smarty->assign("error_Msg",$error_Msg);					
			}						
			else if($objConfigVAR_DD->insertUpdate()==false)
			{				
				$smarty->assign("error_Msg",$objConfigVAR_DD->error_message);
			}
			else
			{				
				$smarty->assign("sucess_Msg","##NG_NO##"." ". $var_no . " " . $objConfigVAR_DD->sucess_message);
			}
		}	
		else if ($objConfigVARs->config_var_type == 5) //multi-select dropdown
		{								
			if(!is_array($_REQUEST['drop_down_option_id']))
			{			   
				$error_Msg = "##NG_CONFIG_VARS_ENTER_VALUE##" . " ##NG_NO##"." ". $var_no . " ";
				$smarty->assign("error_Msg",$error_Msg);					
			}						
			else if(is_array($_REQUEST['drop_down_option_id']))
			{
				if (count($_REQUEST['drop_down_option_id']) <= 0)
				{
					$error_Msg = "##NG_CONFIG_VARS_ENTER_VALUE##" . " ##NG_NO##"." ". $var_no . " ";
					$smarty->assign("errorMsg",$error_Msg);				
				}
				else 
				{
					$total_selected_options = count($_REQUEST['drop_down_option_id']);
					$i = 0;
					while ($i < $total_selected_options)
					{
						$objConfigVAR_DD->drop_down_option_id = $_REQUEST['drop_down_option_id'][$i];
						$objConfigVAR_DD->insertUpdate();
						$i++;
					}
					$smarty->assign("sucess_Msg","##NG_NO##"." ". $var_no . " " . $objConfigVAR_DD->sucess_message);
				}			
			}
		}	
	}	
}


$config_vars = $objConfigVARs->selectAll($objPagging,RECORDS_PER_PAGE);
$paggingRecord=$objPagging->InfoArray();
$total_config_vars = count($config_vars);
if ($paggingRecord['TOTAL_RESULTS'] > 0)
{	
	$i = 0;
	while ($i <= $total_config_vars)
	{
		if ($config_vars[$i]['config_var_type'] == 4 || $config_vars[$i]['config_var_type'] == 5)
		{
			$rowOptions = $objConfigVARs->selectDropDownOptions($config_vars[$i]['config_var_id']);
			$config_vars[$i]['drop_down_options'] = $rowOptions;
		}
		$i++;
	}
}

//print("<pre>");
//print_r($config_vars);

$smarty->assign("config_vars",$config_vars);
$extraVars = (isset($_GET)?$_GET:array());	
$smarty->assign("pagging",$objPagging->print_pagging($extraVars,ADMIN_PAGGING_STYLE));
$smarty->assign("starting_record",$paggingRecord['START_OFFSET']);
$smarty->assign("ending_record",$paggingRecord['END_OFFSET']);
$smarty->assign("total_records",$paggingRecord['TOTAL_RESULTS']);

$smarty->assign("pageTitle","##NG_CONFIG_VARS_MANAGE##");
$smarty->assign("contentFile",ADMIN_VIEW_PATH . "/config_vars.tpl");
include(SITE_PATH."/include/include_master_page.php");


?>