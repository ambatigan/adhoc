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
    public function select($pagging, $condition='', $sort_by = 'id', $sort_order = 'DESC')
      	{
      		$query = "SELECT * FROM ".TBL_PRODUCTS." ". $condition." ORDER BY  ".$sort_by." ". $sort_order;
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
            //Get Title
            $getDescDetail = $this->db->SelectQuery(TBL_PRODUCTS_LNG,"*","product_id = ".mysql_real_escape_string($this->id),"",false,"","");
            foreach($getDescDetail as $k=>$d)
            {
                $price[$d['lng_id']] =$d['price'];
                $price_with_subtitle[$d['lng_id']] =$d['price_with_subtitle'];
                $small_free_text[$d['lng_id']] =$d['small_free_text'];
                $product_name[$d['lng_id']] =$d['product_name'];
                $production_house[$d['lng_id']] =$d['production_house'];
                $app_detail_1[$d['lng_id']] =$d['app_detail_1'];
                $app_detail_2[$d['lng_id']] =$d['app_detail_2'];
                $app_detail_3[$d['lng_id']] =$d['app_detail_3'];
                $download_link[$d['lng_id']] =$d['download_link'];
																$share_model_title[$d['lng_id']] =$d['share_model_title'];
																$share_model_description[$d['lng_id']] =$d['share_model_description'];
                $image1[$d['lng_id']] =$d['image1'];
                $image2[$d['lng_id']] =$d['image2'];
                $image3[$d['lng_id']] =$d['image3'];
                $image4[$d['lng_id']] =$d['image4'];
                $image5[$d['lng_id']] =$d['image5'];
            }

            $this->price =$price;
            $this->price_with_subtitle =$price_with_subtitle;
            $this->small_free_text =$small_free_text;
            $this->product_name =$product_name;
            $this->production_house =$production_house;
            $this->app_detail_1 =$app_detail_1;
            $this->app_detail_2 =$app_detail_2;
            $this->app_detail_3 =$app_detail_3;
            $this->download_link =$download_link;
												$this->share_model_description =$share_model_description;
												$this->share_model_title =$share_model_title;
            $this->image1 =$image1;
            $this->image2 =$image2;
            $this->image3 =$image3;
            $this->image4 =$image4;
            $this->image5 =$image5;
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
            //$Check=$this->checkExists();

            /*if(count($Check)>0  )
            {
                $this->error_message= "Product already exists";
                return false;
            }*/

            $this->fields = $this->db->SelectFields(TBL_PRODUCTS);
            $fields = $this->fields;
            foreach($fields as $k=>$d)
            {
                $values[$d] = $this->$d;
            }

            if($id!='' && $values['status']=='A')
            {
                    $values['modified_by'] = logged_in_user::id();
                    $values['modified_on'] = date('Y-m-d');
            }
            else
            {
                    $values['created_by'] = logged_in_user::id();
                    $values['created_on'] = date('Y-m-d H:i:s');
                    //$values['modified_by'] = logged_in_user::id();
                    //$values['modified_on'] = date('Y-m-d H:i:s');
            }
           $this->fieldsLng = $this->db->SelectFields(TBL_PRODUCTS_LNG);
            $fieldsLng = $this->fieldsLng;
            /*foreach($fieldsLng as $k=>$d)
            {
                  $valuesLng[$d] = $this->$d;}
            }*/
            foreach($fieldsLng as $k=>$d)
            {
                if(!strstr($d,'image'))
                {
                  $valuesLng[$d] = $_POST[$d];
                }
                else
                {
                     $valuesLng[$d] = $this->$d;
                }
            }
            $addMultiLangFields_values = $valuesLng;
            //echo "<pre>";
           // print_r($_POST);
            //print_r($addMultiLangFields_values);
            //exit;

            //exit;
            /*$fields_lan['image1'] = 'image1';
            $fields_lan['image2'] = 'image2';
            $fields_lan['image3'] = 'image3';
            $fields_lan['image4'] = 'image4';
            $fields_lan['image5'] = 'image5';
            foreach($fields_lan as $k=>$d)
            {
                $addMultiLangFields_values[$d] = $this->$d;
            }*/
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
                  $val = $this->addMultiLangFields($id,$addMultiLangFields_values);
                  if($val)
                  {
                      $this->error_message = $val;
                      return false;
                  }
                  else
                  {
                      return true;
                  }
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
                    $val = $this->addMultiLangFields($ins,$addMultiLangFields_values,'A');
                    if($val)
                    {
                        $this->error_message = $val;
                        $this->db->DeleteQuery(TBL_PRODUCTS_LNG,"product_id = ".mysql_real_escape_string($ins),false);
                        $this->db->DeleteQuery(TBL_PRODUCTS,"id = ".mysql_real_escape_string($ins),false);
                        return false;
                    }
                    else
                    {
                        return true;
                    }
            }
        }

        public function addMultiLangFields($product_id,$valuesLng,$add = '')
        {
            global $sitelanguages;
            foreach ($sitelanguages as $lng)
            {
              $this->fieldsLng = $this->db->SelectFields(TBL_PRODUCTS_LNG);
              $fieldsLng = $this->fieldsLng;

              foreach($fieldsLng as $k=>$d)
              {
                  $values[$k] = $valuesLng[$d][$lng['id']];
              }
              $values['product_id'] = $product_id;
              $values['lng_id'] = $lng['id'];
              if($add == '')
              {
                $where = "product_id = ".$values['product_id'] . " AND lng_id = ". $values['lng_id'];
                $this->db->InsertUpdateQuery(TBL_PRODUCTS_LNG,$values,$where,false);
              }
              else
              {
                $this->db->InsertUpdateQuery(TBL_PRODUCTS_LNG,$values,false);
              }
            }
        }

        // delete functionality
        public function delete()
        {
                $id = mysql_real_escape_string($this->id);
                $where1 = "product_id = ".$id;
                $where2 = "id = ".$id;
                $this->db->DeleteQuery(TBL_PRODUCTS_LNG,$where1,false);
                $this->db->DeleteQuery(TBL_PRODUCTS,$where2,false);
                return true;
        }
        public function delete_produdct_image($product_id,$image_no){
          //echo $product_id."  ".$image_no."  ".$image_name;die;  echo
          $values = array();
          $values['image'.$image_no] = '';
          $where =  "product_id = ".$product_id . " AND lng_id = 1";
          $this->db->UpdateQuery (TBL_PRODUCTS_LNG, $values, $where, false);
        }

     }
?>