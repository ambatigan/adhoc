<?php
    #============================================================================================================
    	#	Created By  			: -
    	#	Created Date			: 20-05-2011
    	#	Purpose					: For Handling  users
    	#	includes / Obj(Req)		: Files:
    	#	Last update date		: 20-05-2011
    	#	Update Purpose			: For generation
    #============================================================================================================
    class language
    {
      	public function __construct()
      	{
      		global $commonFunction, $dbAccess;
			$this->db = $dbAccess;
            $this->commonFunction = $commonFunction;
			$fields = $this->db->SelectFields(TBL_LANGUAGES);
			foreach($fields as $k=>$d)
			{
				$this->$d = null;
			}
			//$values['flag_image'] = null;
			$this->fields = $fields;

      	}

        public function select($pagging="", $condition='', $sort_by='id', $sort_order = 'ASC')
      	{
      		$query = "SELECT l.*
						FROM ".TBL_LANGUAGES." l "
						.$condition." 
						ORDER BY ".$sort_by." ".$sort_order;

      		$userList = $this->db->SimpleQuery($query, "", $pagging, false);
			
			//$cmsList = $this->db->SelectQuery(TBL_CMS, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
			return $userList;
        }
		// Check email Exists or not
		public function checkexist()
      	{
      		$query = "SELECT id
						FROM ".TBL_LANGUAGES."
						WHERE id != '".$this->id."' AND language_name = '".$this->language_name."'";
			return $this->db->SimpleQuery($query);
        }

        public function checkexistcode()
      	{
      		$query = "SELECT id
						FROM ".TBL_LANGUAGES."
						WHERE id != '".$this->id."' AND lng_code = '".$this->lng_code."'";
			return $this->db->SimpleQuery($query);
        }
		
		// insert,update query
		public function insertUpdate($id="",$img="",$default="0")
		{
			//$fields = array('page_name', 'description', 'meta_description', 'meta_keywords', 'status', 'slug_title');
			$fields = $this->fields;
			foreach($fields as $k=>$d)
			{
				$values[$d] = $this->$d;// $_POST[$d];
			}
			$values['set_default'] = $default;
			if($id!='')
			{
				$where = "id = ".$id;
				$this->db->InsertUpdateQuery(TBL_LANGUAGES,$values,$where,false);
				return true;
			}
			else
			{
				$this->db->InsertUpdateQuery(TBL_LANGUAGES,$values,false);
				return true;
			}
		}
				
		// delete functionality
		public function delete()
		{
			$this->db->DeleteQuery(TBL_LANGUAGE,"id = ".mysql_real_escape_string($this->id) ,false);
			return true;
		}

		// select by id
		public function selectById()
      	{
			$getUserDetail = $this->db->SelectQuery(TBL_LANGUAGES,"*","id = ".mysql_real_escape_string($this->id),"",false,"","");
			foreach($this as $k=>$d)
			{
				if($k!='db')
				{
					$this->$k =(isset($getUserDetail[0][$k]))?$getUserDetail[0][$k]:$this->$k;
				}
			}
      	}
        // select by code
		public function selectByCode()
      	{
			$getUserDetail = $this->db->SelectQuery(TBL_LANGUAGES,"*","lng_code = '".mysql_real_escape_string($this->lng_code)."'","",false,"","");
			foreach($this as $k=>$d)
			{
				if($k!='db')
				{
					$this->$k =(isset($getUserDetail[0][$k]))?$getUserDetail[0][$k]:$this->$k;
				}
			}
      	}
		public function SetDefault()
      	{
      		$query = "UPDATE ".TBL_LANGUAGE." 
						SET set_default = '0'";
			return $this->db->SimpleUpdateQuery($query);
        }
        public function GetDefault()
      	{
            $getUserDetail = $this->db->SelectQuery(TBL_LANGUAGES,"*","set_default = '1'","",false,"","");
			foreach($this as $k=>$d)
			{
				if($k!='db')
				{
					$this->$k =(isset($getUserDetail[0][$k]))?$getUserDetail[0][$k]:$this->$k;
				}
			}
        }
		function select_max_language_id()
		{
		  	$query = "SELECT IFNULL(MAX(id)+1 ,1) maxid FROM ".TBL_LANGUAGES."";
			$result = mysql_query($query) or throw_ex();
			if(mysql_num_rows($result)==0)
			{
				$this->maxid = 1;
			}
			else
			{
				$obj_row = mysql_fetch_object($result);
				$this->maxid = $obj_row->id;
			}
			
		}
	}
?>