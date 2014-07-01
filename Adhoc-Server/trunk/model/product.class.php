<?php

#============================================================================================================
#	Created By  			: -Vijay Gosai
#	Created Date			: 22-04-2013
#	Purpose					: For Handling  user
#	includes / Obj(Req)		: Files:
#	Last update date		: 22-03-2011
#	Update Purpose			: For generation
#============================================================================================================

class product {

    public function __construct() {
        global $commonFunction, $dbAccess;
        $this->db = $dbAccess;
        $this->commonFunction = $commonFunction;
        $fields = $this->db->SelectFields(TBL_PRODUCTS);
        foreach ($fields as $k => $d) {
            $this->$d = null;
        }
    }
    public function select($pagging, $condition='', $sort_by = 'title', $sort_order = 'ASC')
      	{ 
      		if($sort_by != "")
			{
			 	$seperate = explode(',',$sort_by);
				if($seperate != "" && is_array($seperate) && $seperate[1] != "")
				{
					$sort_by = $seperate[0]." ".$sort_order.",".$seperate[1]." ASC";
					$query = "SELECT p.*,pc.*,c.name FROM ".TBL_PRODUCTS." as p LEFT JOIN ". TBL_PRODUCT_CATEGORY ." as pc ON p.id = pc.product_id LEFT JOIN ".TBL_CATEGORY." as c ON pc.category_id = c.id   ". $condition." ORDER BY  ".$sort_by;
				}
				else
				{
					$query = "SELECT p.*,pc.*,c.name FROM ".TBL_PRODUCTS." as p LEFT JOIN ". TBL_PRODUCT_CATEGORY ." as pc ON p.id = pc.product_id LEFT JOIN ".TBL_CATEGORY." as c ON pc.category_id = c.id   ". $condition." ORDER BY  ".$sort_by." ". $sort_order;		
				}	
			}
      		$StickerList = $this->db->SimpleQuery($query, "", $pagging, false);
			return $StickerList;
        }

    public function select_categories($id)
    	{
    		$query = "SELECT category_id FROM ".TBL_PRODUCT_CATEGORY." WHERE product_id= ". $id;
    		$StickerList = $this->db->SimpleQuery($query, "",false);
            $category = array();
            foreach($StickerList as $sl=>$v)
            {
                array_push($category,$v['category_id']);
            }
	        return $category;
      }

        // select by id
        public function selectById() {
            $getBlogCategoryDetail = $this->db->SelectQuery(TBL_PRODUCTS,"*","id = ".mysql_real_escape_string($this->id),"",false,"","");
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
            $where = " AND id!='".$this->id."' ";
        }
  		$query = "SELECT id FROM ".TBL_PRODUCTS." WHERE id='".mysql_real_escape_string($this->id)."' ".$where;
        $getUserId = $this->db->SimpleQuery($query, false);
        return $getUserId;
  	}
    // insert,update query
        public function insertUpdate($id)
        {

            $this->fields = $this->db->SelectFields(TBL_PRODUCTS);
            $fields = $this->fields;
            foreach($fields as $k=>$d)
            {
                $values[$d] = mysql_real_escape_string($this->$d);
            }

            if($id!='' && $values['status']=='A')
            {
                    $values['modified_by'] = logged_in_user::id();
                    //$values['modified_on'] = date('Y-m-d');
                    $values['modified_on'] = date('Y-m-d H:i:s');
					if($values['active_count'] == 0)
					{
						$values['active_count'] = 1;
					}
            }
            else
            {
                    $values['created_by'] = logged_in_user::id();
                    $values['created_on'] = date('Y-m-d H:i:s');
                    //$values['modified_by'] = logged_in_user::id();
                    //$values['modified_on'] = date('Y-m-d H:i:s');
            }

            if($id!='')
            {
                  $where = "id = ".$id;
                  $this->db->InsertUpdateQuery(TBL_PRODUCTS,$values,$where,false);
                  $this->db->DeleteQuery(TBL_PRODUCT_CATEGORY,"product_id = ".mysql_real_escape_string($id),false);
                  foreach($_POST['category_id'] as $c)
                    {
                        $values1 = array('product_id'=>$id,'category_id'=>$c);
                        $this->db->InsertUpdateQuery(TBL_PRODUCT_CATEGORY,$values1,false);
                    }
                  return true;

            }
            else
            {
                    $this->db->InsertUpdateQuery(TBL_PRODUCTS,$values,false);
                    $ins = $this->db->lastInsertedId;
                    foreach($_POST['category_id'] as $c)
                    {
                        $values1 = array('product_id'=>$ins,'category_id'=>$c);
                        $this->db->InsertUpdateQuery(TBL_PRODUCT_CATEGORY,$values1,false);
                    }
                    return true;

            }
        }



        // delete functionality
        public function delete()
        {
                $id = mysql_real_escape_string($this->id);
                $fields['deleted'] = 1;
                $where1 = "product_id = ".$id;
                $where2 = "id = ".$id;
                //$this->db->DeleteQuery(TBL_PRODUCTS,$where2,false);
                $this->db->InsertUpdateQuery(TBL_PRODUCTS, $fields,$where2,false);
				$this->db->InsertUpdateQuery(TBL_PRODUCT_CATEGORY,$fields,$where1,false);
                 return true;
                //$this->db->DeleteQuery(TBL_PRODUCT_CATEGORY,$where1,false);
                /*$fields['deleted'] = 1;
                $this->db->InsertUpdateQuery(TBL_PRODUCTS, $fields,$where2,false);
                */

        }



     }
?>