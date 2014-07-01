<?php
      #============================================================================================================
      	#	Created By  			: Dev-
      	#	Created Date			: 24-02-2011
      	#	Purpose					: For Handling users
      	#	includes / Obj(Req)		: Files:
      	#	Last update date		: 24-02-2011
      	#	Update Purpose			: For generation
      #============================================================================================================
      class config_vars
      {
      	public $config_var_id;
		public $config_var_name;
		public $config_var_title;
		public $config_var_description;
		public $config_var_type;
      	public $config_var_value;
      	public $drop_down_id;

      	public $created_by;
      	public $created_on;
      	public $modified_by;
      	public $modified_on;
      	public $deleted_by;
      	public $deleted_on;
      	public $deleted;
		public $error_message;
		public $sucess_message;

      	public function __construct(&$db)
      	{
      		$this->db = &$db;
      		$argv = func_get_args();
      		switch( func_num_args() )
      		{
      			default:
      			case 0:
      				$this->id = 0;
      			break;
      			case 1:
      				$this->id = $argv[0];
      				//$this->selectById();
      			break;
      		}
      	}

		/**
		 * This Function will returns all the config variable with its values based on condition given.
		 * @param condition - where condition ie. config_var_name like '%var_city%'
		 * @param sort - order by condition i.e. ORDER BY config_var_title
		 * @param pagging - object of pagging class
		 * @return Array of config variables*/
		public function selectAll(&$pagging,$limit)
		{
			if(is_object($pagging))
			{
				$this->paggingObj = &$pagging;
				$this->paggingObj->TotalResults = $this->db->GetRecordCount(TBL_CONFIG_VARS,"config_var_id",$condition,false);
				$paggingArray = $this->paggingObj->InfoArray($this->paggingObj->TotalResults);
				$limit = $paggingArray["MYSQL_LIMIT1"].",".$paggingArray["MYSQL_LIMIT2"];
			}
			$query="SELECT * FROM ".TBL_CONFIG_VARS." LIMIT ".$limit;
			$rows = $this->db->SimpleQuery($query,"");
			return $rows;
		}

		/**
		 * This Function will select all drop down options of particular config variable.
		 * @param condition - where condition ie. config_var_id = 1
		*/
/*		public function selectDropDownOptions($id="")
		{
			if ($id == '')
				$id = $this->config_var_id;

			$query = "SELECT ddo.drop_down_option_id, ddo.drop_down_option_value, ddo.drop_down_id, IF( IFNULL( cvddo.drop_down_option_id, 'No' ) = 'No', 'No', 'Yes' ) AS is_selected
						FROM ".TBL_DROP_DOWN_OPTIONS." ddo
						INNER JOIN ".TBL_DROP_DOWNS." dd ON dd.drop_down_id = ddo.drop_down_id
						INNER JOIN ".TBL_CONFIG_VARS." cv ON cv.drop_down_id = ddo.drop_down_id
						LEFT JOIN ".TBL_CONFIG_VAR_DROP_DOWN_OPTIONS." cvddo ON cvddo.drop_down_option_id = ddo.drop_down_option_id
						WHERE cv.config_var_id = '".mysql_real_escape_string($id)."'";
			$rows = $this->db->SimpleQuery($query,"");
			return $rows;
		}
*/
		public function selectDropDownSelectedOptions($var_name)
		{			
			$query = "SELECT cvd.drop_down_option_id 
			FROM config_var_drop_down_options cvd
			INNER JOIN config_vars cv ON cv.config_var_id = cvd.config_var_id
			WHERE cv.config_var_name = '".$var_name."'";
			$rows = $this->db->SimpleQuery($query,"");
			return $rows;
		}
		
		public function getGlobalVariableValue($var_name)
		{
			$var_value = false;
			$query = "SELECT config_var_value FROM ".TBL_CONFIG_VARS." WHERE config_var_name='".$var_name."'";
			$rows = $this->db->SimpleQuery($query,"");
			if (is_array($rows))
			{
				if (count($rows) > 0)
					$var_value = $rows[0]['config_var_value'];
			}
			return $var_value;
		}
		
		public function selectGlobalVariablesArray()
		{
		 	$query="SELECT * FROM ".TBL_CONFIG_VARS;
			$rows = $this->db->SimpleQuery($query,"");
			$total_vars = count($rows);
			if ($total_vars > 0)
			{
				$i = 0;				
				while ($i < $total_vars)
				{
					$var_name = $rows[$i]['config_var_name'];
				 	if ($rows[$i]['config_var_type'] >= 1 || $rows[$i]['config_var_type'] <= 3)	
						$config_vars[$var_name] = $rows[$i]['config_var_value'];
				  /*	if ($rows[$i]['config_var_type'] == 4)
					{
						$rowDD = $this->selectDropDownOptions($rows[$i]['config_var_id']);
						
						if (is_array($rowDD))
						{
							if (count($rowDD) > 0)
								$config_vars[$var_name] = $rowDD[0]['drop_down_option_id'];
						}
					} 
					if ($rows[$i]['config_var_type'] == 5)
					{
						$rowDD = $this->selectDropDownOptions($rows[$i]['config_var_id']);
						if (is_array($rowDD))
						{
							$total_options = count($rowDD);
							if ($total_options > 0)
							{
								
								$rowDD = $this->selectDropDownSelectedOptions($var_name);
								if (is_array($rowDD))								
								{
									$total_options = count($rowDD);
									if ($total_options > 0)
									{
										$j = 0;
										while ($j < $total_options)
										{
											$rowDDTemp[$j] = $rowDD[$j]['drop_down_option_id'];
											$j++;
										}
									}
								}
								$config_vars[$var_name] = $rowDDTemp;
							}
						}
					}	*/
					$i++;
				}
			}		
			return $config_vars;	
		}
		

	   	public function insertUpdate($id="")
		{
			$fields = array();
						
			if($id!='')
			{
				$fields['modified_by'] = $_SESSION['admin_id'];
				$fields['modified_on'] = date('Y-m-d h:s:i');
			}
			else
			{
				//$fields['created_by'] = $_SESSION['admin_id'];
				//$fields['created_on'] = "";
			}

			$where = "config_var_id = ".$id;

			if($id!='')
			{
				if ($this->config_var_type >= 1 && $this->config_var_type <= 3)
				{					
					$fields['config_var_value'] = mysql_real_escape_string($this->config_var_value);					
				
					$this->db->InsertUpdateQuery(TBL_CONFIG_VARS,$fields,$where,false);
					$this->sucess_message = "##NG_CONFIG_VARS_EDIT_SUCCESS##";
					return true;
				}
			}
			
		}

		public function checkExists()
      	{
	   		$rows = $this->db->SelectQuery(TBL_CONFIG_VARS,"config_var_id","config_var_name = '".mysql_real_escape_string($this->config_var_name)."'","");
			return $rows;
      	}
      }
?>