<?php

#============================================================================================================
#	Created By  			: -Vijay Gosai
#	Created Date			: 22-04-2013
#	Purpose					: For Handling  user
#	includes / Obj(Req)		: Files:
#	Last update date		: 22-03-2011
#	Update Purpose			: For generation
#============================================================================================================

class category {

    public function __construct() {
        global $commonFunction, $dbAccess;
        $this->db = $dbAccess;
        $this->commonFunction = $commonFunction;
        $fields = $this->db->SelectFields(TBL_CATEGORY);
        foreach ($fields as $k => $d) {
            $this->$d = null;
        }
    }
    public function select($pagging, $condition='', $sort_by = 'id', $sort_order = 'DESC')
      	{
      		$query = "SELECT * FROM ".TBL_CATEGORY." ". $condition." ORDER BY  ".$sort_by." ". $sort_order;
      		$StickerList = $this->db->SimpleQuery($query, "", $pagging, false);
			return $StickerList;
        }

        // select by id
        public function selectById() {
            $getBlogCategoryDetail = $this->db->SelectQuery(TBL_CATEGORY,"*","id = ".mysql_real_escape_string($this->id),"",false,"","");
            foreach($this as $k=>$d) {
                if($k!='db' && $k!='commonFunction') {
                    $this->$k =$getBlogCategoryDetail[0][$k];
                }
            }

        }

    public function checkExists()
  	{
  		$where = "";
  		if($this->id !='')
        {
            $where = " AND id!='".$this->id."'";
        }
  		$query = "SELECT id FROM ".TBL_CATEGORY." WHERE name='".mysql_real_escape_string($this->name)."' ".$where;
        $getUserId = $this->db->SimpleQuery($query, false);
        return $getUserId;
  	}
    // insert,update query
        public function insertUpdate($id)
        {
            $Check=$this->checkExists();

            if(count($Check)>0  )
            {
                $this->error_message= "Category already exists";
                return false;
            }
          //  if($id!='')
          //  {
                $this->fields = $this->db->SelectFields(TBL_CATEGORY);
          //  }

            $fields = $this->fields;
            //echo "sadfaf";print_r($fields);die;

            foreach($fields as $k=>$d)
            {
                $values[$d] = $this->$d;
            }

            if($id!='')
            {
                    $values['modified_by'] = logged_in_user::id();
                    $values['modified_on'] = date('Y-m-d H:i:s');
            }
            else
            {
                    $values['created_by'] = logged_in_user::id();
                    $values['created_on'] = date('Y-m-d H:i:s');
                    $values['modified_by'] = logged_in_user::id();
                    $values['modified_on'] = date('Y-m-d H:i:s');
            }

            if($id!='')
            {
                    $where = "id = ".$id;
                    $this->db->InsertUpdateQuery(TBL_CATEGORY,$values,$where,false);
                    return true;

            }
            else
            {
                    $this->db->InsertUpdateQuery(TBL_CATEGORY,$values,'',false);
                    $ins = $this->db->lastInsertedId;
                    return true;

            }
        }


        // delete functionality
        public function delete()
        {
            if($this->childExists())
            {
              $this->error_message = "Product already exists for this category. Please remove product of this category first.";
              return false;
            }
            else
            {
                $id = mysql_real_escape_string($this->id);
                $where2 = "id = ".$id;
                $this->db->DeleteQuery(TBL_CATEGORY,$where2,false);
                return true;
            }
        }

     public function childExists()
        {
           $query = "SELECT p.category_id
           FROM ".TBL_PRODUCT_CATEGORY." as p,".TBL_CATEGORY." as c  WHERE p.category_id=".$this->id." AND p.category_id = c.id";
           $menu =  $this->db->SimpleQuery($query, "", $pagging, false);

           if(empty($menu))
           {
             return false;
           }
           else{
             return true;
           }
        }
     }
?>