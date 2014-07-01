<?php
    #============================================================================================================
    	#	Created By  			: -
    	#	Created Date			: 20-05-2011
    	#	Purpose					: For Handling  users
    	#	includes / Obj(Req)		: Files:
    	#	Last update date		: 20-05-2011
    	#	Update Purpose			: For generation
    #============================================================================================================
    class language_var
    {
      	public function __construct()
      	{
      		global $commonFunction, $dbAccess;
			$this->db = $dbAccess;
            $this->commonFunction = $commonFunction;
			$fields = $this->db->SelectFields(TBL_LANGUAGES_VAR);
			//print_r($fields);die;
			foreach($fields as $k=>$d)
			{
				$this->$d = null;
			}

			$this->fields = $fields;

            // Multi language fields
            $this->txt=null;
      	}

        public function select($pagging="", $condition='', $sort_by='id', $sort_order = 'ASC')
      	{
      		$query = "SELECT l.* 
						FROM ".TBL_LANGUAGES_VAR." l "
						.$condition."
						ORDER BY ".$sort_by." ".$sort_order;

      		$userList = $this->db->SimpleQuery($query, "", $pagging, false);

			//$cmsList = $this->db->SelectQuery(TBL_CMS, "*", "", "", "", "ORDER BY $sort_by", $sort_order, $pagging, false);
			return $userList;
        }

        public function select_lng($condition='')
      	{
      		$query = "SELECT lt.lng_id,lt.txt,lv.id FROM ".TBL_LANGUAGES_TEXTS." lt
                        JOIN ".TBL_LANGUAGES_VAR ." lv ON lv.id = lt.lng_var_id "
						.$condition;

      		$userList = $this->db->SimpleQuery($query, "", $pagging, false);
							
            for($i=0;$i<count($userList);$i++)
            {
                $txt[$userList[$i]['lng_id']] = $userList[$i]['txt'];
																$id[$userList[$i]['lng_id']] = $userList[$i]['id'];

            }
			return array('language_variable'=>$txt,'language_variable_id'=>$id);
        }
		// Check email Exists or not
		public function checkexist()
      	{
      		$query = "SELECT id 
						FROM ".TBL_LANGUAGES_VAR." 
						WHERE id != '".$this->id."' AND name = '".$this->name."' ";
			return $this->db->SimpleQuery($query);
        }
		
		// insert,update query
		public function insertUpdate($id="")
		{
			//$fields = array('page_name', 'description', 'meta_description', 'meta_keywords', 'status', 'slug_title');
			$fields = $this->fields;

			foreach($fields as $k=>$d)
			{
				$values[$d] = $this->$d;// $_POST[$d];
			}
			
			if($id!='')
			{
				$where = "id = ".$id;
				$this->db->InsertUpdateQuery(TBL_LANGUAGES_VAR,$values,$where,true);
				$this->addMultiLangFields($id);
				return true;
			}
			else
			{
				$this->db->InsertUpdateQuery(TBL_LANGUAGES_VAR,$values,false);
				$this->addMultiLangFields($this->db->lastInsertedId);
				return true;
			}
		}
		
		public function addMultiLangFields($languagevarid){
            global $sitelanguages;
            foreach ($sitelanguages as $lng){
                $where = "lng_var_id=".$languagevarid." AND lng_id=".$lng['id'];
                $this->db->DeleteQuery(TBL_LANGUAGES_TEXTS,$where);

               $values['lng_var_id'] = $languagevarid;
               $values['lng_id'] = $lng['id'];
               $values['txt'] = $this->txt[$lng['id']];

               $this->db->InsertUpdateQuery(TBL_LANGUAGES_TEXTS,$values,false);

            }

        }
				
		// delete functionality
		public function delete()
		{
			$this->db->DeleteQuery(TBL_LANGUAGES_VAR,"id = ".mysql_real_escape_string($this->id) ,false);
			$this->db->DeleteQuery(TBL_LANGUAGES_TEXTS,"lng_var_id = ".mysql_real_escape_string($this->id) ,false);
			return true;
		}

		// select by id
		public function selectById()
      	{
			$getLanguageVarDetail = $this->db->SelectQuery(TBL_LANGUAGES_VAR,"*","id = ".mysql_real_escape_string($this->id),"",false,"","");
			foreach($this as $k=>$d)
			{
				if($k!='db')
				{
					$this->$k =(isset($getLanguageVarDetail[0][$k]))?$getLanguageVarDetail[0][$k]:$this->$k;
				}
			}
			//Get Language Text
            $getLanguageTxt = $this->db->SelectQuery(TBL_LANGUAGES_TEXTS,"*","lng_var_id = ".mysql_real_escape_string($this->id),"",false,"","");
			foreach($getLanguageTxt as $k=>$d)
			{
			  $languageTxt[$d['lng_id']] =$d['txt'];
            }

            $this->txt =$languageTxt;
      	}
	}
?>