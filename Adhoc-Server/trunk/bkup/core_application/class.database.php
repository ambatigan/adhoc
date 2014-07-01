<?php
	class DBAccess
	{
		private $dbHost;
		private $dbUserName;
		private $dbPassword;
		private $dbName;
		private $dbLinkId;
		public $dbErrStr;
		public $lastInsertedId;


		/*
		*   DBAccess
		*   Desc: constuctor to assign host,user name, pasword to data member of class.
		*   Parms:
		*   $host - hostname e.g. localhost.
		*   $user_name - Database User name.
		*   $password - Password of Database.
		*   $datbase_name - Database name.
		*   Returns: none
		*/
		function DBAccess($host,$userName,$password,$databaseName,$pcon=0)
		{
			$this->dbHost = $host;
			$this->dbUserName = $userName;
			$this->dbPassword = $password;
			$this->dbName = $databaseName;

			// open persistent or normal connection
			if ($pcon)
			{
				$this->dbLinkId = @mysql_pconnect($this->dbHost,$this->dbUserName,$this->dbPassword);
			}
			else
			{
				$this->dbLinkId = @mysql_connect ($this->dbHost,$this->dbUserName,$this->dbPassword);
			}

			// connect to mysql server failed?
			if (!$this->dbLinkId)
			{
				$this->dbErrStr  = $pcon ? "persistent " : "";
				$this->dbErrStr .= "connect failed ('$user_name:$pass@$host')";

				echo $this->dbErrStr." ".mysql_error();
				die();
			}
			// select database
			$result = mysql_select_db($this->dbName);
			if (!$result)
			{
				// db select failed
				@mysql_close($this->dbLinkId);
				$this->dbErrStr = "database not found ('".$this->dbName."')";
				echo $this->dbErrStr." ".mysql_error();
				die();
			}
	        // return with success
    	    return 1;
		}


		/*
		*   SelectQuery
		*   Desc: Retrieve data from the database.
		*   Parms:
		*   $table - comma separated list of table names.
		*   $fields - comma separated list of field names or "*".
		*   $where - SQL Where clause (e.g. "where id=2").
		*   $groupBy - SQL Group clause (e.g. "group by name").
		*   $orderBy - SQL Order clause (e.g. "order by name").
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   2d array of rows and columns on success.
		*   Error String on failure.
		*/
		function SelectQuery($table, $fields, $where="", $limit="", $groupBy="", $orderBy="", $sort_order, $pagging=null, $showDebug=false )
		{
			// Return the data requested by the fields, table and where.
			// Return the data in a 2 dimensional array.
			
			$values = array();
			$fieldArray = split (", ?", $fields);

			$max_fields = ($fields == "*")? 20 : count($fieldArray);

			if (!empty($where))
			{
				$where = "WHERE $where";
			}
			
		   	$query = "SELECT $fields FROM $table $where $groupBy $orderBy $sort_order";
			
			// Paging & Limit
			if(is_object($pagging))
			{
				$this->paggingObj = &$pagging;
				$this->paggingObj->TotalResults = $this->GetRecordCountSimpleQuery($query,false);
				$pagingArray = $this->paggingObj->InfoArray($this->paggingObj->TotalResults);
			  	$limit = $pagingArray["MYSQL_LIMIT1"].",".$pagingArray["MYSQL_LIMIT2"];
			}
			
			if($limit!="")
			{
				$limit = " LIMIT ".$limit;
				$query .= $limit;
			}
			// Paging & Limit
			
	   	   	
			if ($showDebug == true)
				echo "query=$query<br>\n";

			$stmt = mysql_query ($query,$this->dbLinkId);

			if ($stmt == false)
			{
				echo mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			while ($fields = mysql_fetch_assoc ($stmt))
			{
				$values[] = $fields;
			}

			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $values;
		}
		
		
		function SelectFields($table,$showDebug=false)
		{
			$values = array();
			$query = "DESCRIBE $table";
			if ($showDebug == true)
				echo "query=$query<br>\n";

                        
			$stmt = mysql_query ($query,$this->dbLinkId);

			if ($stmt == false)
			{
				echo mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			while ($fields = mysql_fetch_assoc($stmt))
			{
				$values[$fields['Field']] = $fields['Field'];
			}

			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $values;
		}

		/*
		*   SelectQuery
		*   Desc: Retrieve data from the database.
		*   Parms:
		*   $table - comma separated list of table names.
		*   $fields - comma separated list of field names or "*".
		*   $where - SQL Where clause (e.g. "where id=2").
		*   $groupBy - SQL Group clause (e.g. "group by name").
		*   $orderBy - SQL Order clause (e.g. "order by name").
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   2d array of rows and columns on success.
		*   Error String on failure.
		*/
		function SimpleQuery($query, $limit='', $pagging=null, $showDebug=false)
		{
                        //mysql_set_charset('utf8'); 
                    
			// Return the data requested by the fields, table and where.
			// Return the data in a 2 dimensional array.
			$values = array();
			$fieldArray = split (", ?", $fields);

			$max_fields = ($fields == "*")? 20 : count($fieldArray);

			// Paging & Limit
			                        
			if(is_object($pagging))
			{
				$this->paggingObj = &$pagging;
				$this->paggingObj->TotalResults = $this->GetRecordCountSimpleQuery($query,false);
				$pagingArray = $this->paggingObj->InfoArray($this->paggingObj->TotalResults);
			  	$limit = $pagingArray["MYSQL_LIMIT1"].",".$pagingArray["MYSQL_LIMIT2"];
			}
			
			if($limit!="")
			{
				$limit = " LIMIT ".$limit;
				$query .= $limit;
			}
			// Paging & Limit
              // echo $query; exit;
			if ($showDebug == true)
				echo "query=$query<br>\n";

			$stmt = mysql_query ($query,$this->dbLinkId);

			if ($stmt == false)
			{
				echo mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			while ($fields = mysql_fetch_assoc($stmt))
			{
				$values[] = $fields;
			}
			
			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $values;
		}



		/*
		*   SelectQuery
		*   Desc: Retrieve data from the database With direct pass by query and return one result.
		*   Parms:
		*   $table - comma separated list of table names.
		*   $fields - comma separated list of field names or "*".
		*   $where - SQL Where clause (e.g. "where id=2").
		*   $groupBy - SQL Group clause (e.g. "group by name").
		*   $orderBy - SQL Order clause (e.g. "order by name").
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   2d array of rows and columns on success.
		*   Error String on failure.
		*/
		function SimpleOneQuery($query,$showDebug=false)
		{

			// Return the data requested by the fields, table and where.
			// Return the data in a 2 dimensional array.
			$values = array();
			$fieldArray = split (", ?", $fields);

			$max_fields = ($fields == "*")? 20 : count($fieldArray);

			if ($showDebug == true)
				echo "query=$query<br>\n";

			$stmt = mysql_query ($query,$this->dbLinkId);

			if ($stmt == false)
			{

				echo mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			$fields = mysql_fetch_object ($stmt);
			
			$values = $fields;
			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $values;
		}



		/*
		*   SelectQueryObject
		*   Desc: Retrieve data from the database as an object.
		*   Parms:
		*   $table - comma separated list of table names.
		*   $fields - comma separated list of field names or "*".
		*   $where - SQL Where clause (e.g. "where id=2").
		*   $groupBy - SQL Group clause (e.g. "group by name").
		*   $orderBy - SQL Order clause (e.g. "order by name").
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   2d array of rows and columns on success.
		*   Error String on failure.
		*/
		function SimpleQueryObject($query,$showDebug=false)
		{
			// Return the data requested by the fields, table and where.
			// Return the data in a 2 dimensional array.
			$values = array();
			$fieldArray = split (", ?", $fields);

			$max_fields = ($fields == "*")? 20 : count($fieldArray);

			if ($showDebug == true)
				echo "query=$query<br>\n";

		 	$stmt = mysql_query ($query,$this->dbLinkId);
			if(mysql_num_rows($stmt) == 0)
			{
				return false;
			}

			if ($stmt == false)
			{

				echo mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			$obj_rows = array();
			$i=0;
			while ($fields = mysql_fetch_object ($stmt))
			{
				$obj_rows[$i] = $fields;
				$i++;
			}

			@mysql_free_result ($stmt);
			@mysql_close($db);
			return $obj_rows;
		}


		/*
		*   SelectQueryObject
		*   Desc: Retrieve data from the database as an object.
		*   Parms:
		*   $table - comma separated list of table names.
		*   $fields - comma separated list of field names or "*".
		*   $where - SQL Where clause (e.g. "where id=2").
		*   $groupBy - SQL Group clause (e.g. "group by name").
		*   $orderBy - SQL Order clause (e.g. "order by name").
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   2d array of rows and columns on success.
		*   Error String on failure.
		*/
		function SimpleOneObject($query,$showDebug=false)
		{
			// Return the data requested by the fields, table and where.
			// Return the data in a 2 dimensional array.
			$values = array();
			$fieldArray = split (", ?", $fields);

			$max_fields = ($fields == "*")? 20 : count($fieldArray);

			if ($showDebug == true)
				echo "query=$query<br>\n";

		  	$stmt = mysql_query ($query,$this->dbLinkId);
			if(mysql_num_rows($stmt) == 0)
			{
				return false;
			}

			if ($stmt == false)
			{

				echo mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			$obj_rows = array();
			$fields = mysql_fetch_object ($stmt);
			$obj_rows = $fields;
			@mysql_free_result ($stmt);
			@mysql_close($db);
			return $obj_rows;
		}



		/*
		*   select one
		*   Desc: Retrieve data from the database.
		*   Parms:
		*   $table - table names.
		*   $fields - comma separated list of field names or "*".
		*   $where - SQL Where clause (e.g. "where id=2").
		*   $groupBy - SQL Group clause (e.g. "group by name").
		*   $orderBy - SQL Order clause (e.g. "order by name").
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   1d array of rows and columns on success.
		*   Error String on failure.
		*/

		function SelectOne($table, $fields, $where="",$showDebug=false, $groupBy="", $orderBy="" )
		{

			// Return the data requested by the fields, table and where.
			// Return the data in a 2 dimensional array.
			$values = array();
			$fieldArray = split (", ?", $fields);

			$max_fields = ($fields == "*")? 20 : count($fieldArray);

			if (!empty($where))
			{
				$where = "where $where";
			}

			$query = "SELECT $fields FROM $table $where $groupBy $orderBy";
			if ($showDebug == true)
				echo "query=$query<br>\n";

			$stmt = mysql_query ($query,$this->dbLinkId);

			if ($stmt == false)
			{

				echo mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			$values = mysql_fetch_assoc ($stmt);


			@mysql_free_result ($stmt);
			@mysql_close($db);
		}

		/********************************************************/
		/*
		  * InsertQuery
		  * Desc: Insert data into the database.
		  * Parms:
		  *   $tableName - database table name.
		  *   $values - associative array of field names and corresponding values.
		  *   $debug - If true then return SQL query without executing.
		  * Returns:
		  *   Nothing on success.
		  *   Error String on failure.
		*/
		function InsertQuery($tableName, $values, $debug=false)
		{
			/* Insert the $values into the database.
			   * e.g.
			   * $values = array ("name"=>"kris","email"=>"karn@nucleus.com");
			   * InsertQuery ("employee", $values);
			*/
			return $this->InsertUpdateQuery( $tableName, $values,"", $debug);
		}

		/********************************************************/

		/*
		  * UpdateQuery
		  * Desc: Update data in the database.
		  * Parms:
		  *   $tableName - database table name.
		  *   $values - associative array of field names and corresponding values.
		  *   $where - SQL Where clause to specify which row(s) to update.
		  *   $debug - If true then return SQL query without executing.
		  * Returns:
		  *   Nothing on success.
		  *   Error String on failure.
		*/
		function UpdateQuery ($tableName, $values, $where="", $debug=false)
		{

			/* Update the $values in the database.
			   	* e.g.
			   * $values = array ("name"=>"kris","email"=>"karn@nucleus.com");
			   * $where = "WHERE id='1'";
			   * UpdateQuery ("employee", $values, $where);
			*/
			if (empty($where))
				$where = " ";

			return $this->InsertUpdateQuery( $tableName, $values,$where, $debug);
		}

		/********************************************************/

		function InsertUpdateQuery($tableName, $fieldValues , $where="" , $debug=false)
		{


			$i = 0;
			$fields = "";
			$values = "";
			$updateList = "";

			while (list ($key, $val) = each ($fieldValues))
			{
				//$val = mysql_real_escape_string($val);
				if ($i > 0)
				{
					$fields .= ", ";
					$values .= ", ";
					$updateList .= ", ";

				}

				$fields .= $key;

				// If you do not want to add quotes
				// around the field then specify
				// /*NO_QUOTES*/ when passing in the value.
				// For update statements like
				// "update poll set total_votes=total_votes+1",
				// you do not want
				// the value field to have quotes around it.
				if (strstr($val,"/*NO_QUOTES*/"))
				{
					$val = str_replace ("/*NO_QUOTES*/", "", $val);
					$updateList .= "$key=$val";
					//$values .= mysql_real_escape_string($val);
                    $values .= $val;
				}
				else
				{
					/*$updateList .= "$key='".mysql_real_escape_string($val)."'";
					$values .= "'".mysql_real_escape_string($val)."'";*/
                    $updateList .= "$key='".$val."'";
					$values .= "'".$val."'";
				}
				$i++;
			}

			if (empty($where))
			{
			   	$query = "INSERT INTO $tableName ($fields) VALUES ($values)";
			}
			else
			{
			   $where = " WHERE ".$where; 
		   	   $query = "UPDATE $tableName SET $updateList $where";
			}
		   	if ($debug)
			{
				//@mysql_close($db);
				echo $query;
			}

			//$query =  mysql_real_escape_string($query);
            //echo $query;die;
			$stmt = mysql_query($query,$this->dbLinkId);
			if (empty($where))
				$this->lastInsertedId = mysql_insert_id($this->dbLinkId);

			if ($stmt == false)
			{
				echo  mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();

			}
			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $error;
		}
		/*
		*  DeleteQuery
		*  Desc: Delete data from the database.
		*  Parms:
		*  $tableName - database table name.
		*  $where - SQL Where clause to specify which row(s) to delete.
		*  $debug - If true then return SQL query without executing.
		*  Returns:
		*  Nothing on success.
		*  Error String on failure.
		*/
		function DeleteQuery($tableName, $where="", $debug=false)
		{
			// Delete a row from the specified table.
			if (!empty($where))
			{
				$where = "WHERE $where";
			}

		 $query = "DELETE FROM $tableName $where";
			if ($debug)
			{
				@mysql_close($db);
				echo $query;
			}
			$stmt = mysql_query ($query,$this->dbLinkId);
			if (!$stmt)
			{
				echo  mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $error;
		}

		function SimpleDeleteQuery ($query, $debug=false)
		{
			$stmt = mysql_query ($query,$this->dbLinkId);
			if (!$stmt)
			{
				echo  mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $error;
		}

		function SimpleUpdateQuery ($query, $debug=false)
		{
			$stmt = mysql_query ($query,$this->dbLinkId);
			if (!$stmt)
			{
				echo  mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $error;
		}

		/*
		*   GetRecordCount
		*   Desc: Retrieve data from the database.
		*   Parms:
		*   $table - table name.
		*   $fields - comma separated list of field names or "*".
		*   $condition - SQL Where clause (e.g. "where id=2").
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   return number of records
		*   Error String on failure.
		*/


		function GetRecordCount($table_name,$field,$condition,$debug =false)
		{

			$query = "SELECT ".$field." FROM ".$table_name;
			if(!empty($condition))
				$query .= " WHERE ".$condition;

			$stmt = mysql_query($query);

			if($debug == true)
			{
				echo $query;
			}
			if ($stmt == false)
			{
				echo  mysql_errno().": ".mysql_error()."<br>";
				die();
			}
			return mysql_num_rows($stmt);
		}

		function GetRecordCountSimpleQuery($query,$debug =false)
		{

			$stmt = mysql_query($query);

			if($debug == true)
			{
				echo $query;
			}
			if ($stmt == false)
			{
				echo  mysql_errno().": ".mysql_error()."<br>";
				die();
			}
			return mysql_num_rows($stmt);

		}

		/*
		*   CallSP (to call stored procedure who have parameter)
		*   Desc: Retrieve data from the database.
		*   Parms:
		*   $sp_name - stord procedure name.
		*   $fields - comma separated list of field names or "*".
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   return number of records
		*   Error String on failure.
		*/

		function CallSP($sp_name,$fieldValues,$showDebug=false)
		{


			$i = 0;
			$fields = "";
			$values = "";
			$updateList = "";

			while (list ($key, $val) = each ($fieldValues))
			{
				if ($i > 0)
				{
					$fields .= ", ";
					$values .= ", ";
					$updateList .= ", ";

				}

				$fields .= $key;

				if (strstr($val,"/*NO_QUOTES*/"))
				{
					$val = str_replace ("/*NO_QUOTES*/", "", $val);
					$updateList .= "$key=$val";
					$values .= $val;
				}
				else
				{
					$updateList .= "$key='$val'";
					$values .= "'$val'";
				}
				$i++;
			}
			$query = "call $sp_name ($values)";

			if ($showDebug)
			{
				echo $query;
			}

			$stmt = mysql_query ($query,$this->dbLinkId);
			if ($stmt == false)
			{
				echo  mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your sp is: ".$query;
				die();

			}


			@mysql_free_result ($stmt);
			@mysql_close($db);

			return $error;

		}

		/*
		*   GenerateDynamicDropDown (to genrate dynamic dropdown)
		*   Desc: Retrieve data from the database.
		*   Parms:
		*   $tableName  table name
		*   $fieldId	value of option <option value=$fieldName>
		*   $fieldName	display value of select <option>$fieldName</option>
		*   $multiselect
		*   $where_clause where caluse of query
		*   $order_by 	order caluse of query
		*   $selectedVal to select perticular value
		*   $select_name
		*   $width	width of combobox
		*   $size  size of combobox
		*   $title the first value of combobox
		*   $fun  if you want to call any funciton then use this
		*   $extraval
		*   $sym
		*   $optionfun
		*   Returns: combobox control

		*/

		function GenerateDynamicDropdown($tableName,$fieldId,$fieldName,$multiselect=false,$where_clause='',$order_by='',$selectedVal='',$select_name='',$width=150,$size='',$title='',$fun='',$extraval='',$sym='',$optionfun='')
		{
			Global $obj;
			$groupdropdown = "";
			if($select_name == '')
				$select_name == $fieldId;

			if($where_clause !="")
				$where_clause = " WHERE $where_clause ";



			if($extVal!='')
				$ssql = "$fieldId,$fieldName,$extVal";
			else
				$ssql = "$fieldId,$fieldName";



			if($order_by !="")
				$order_by = " ORDER BY $order_by ";

			if($title=='')
				$title="Please Select Value";

			$sql_query="SELECT $ssql FROM $tableName $where_clause $order_by";
			//function SelectData ($table, $fields, $where="",$limit="",$showDebug=false, $groupBy="", $orderBy="" )

			$dbSelectRs = $this->SelectData($tableName,$ssql,$where,"","","",$order_by);

			if($size!='')
				$size="size=$size";

			if($fun!='')
				$function = "onChange='".$fun."(this.value)'";

			if($optionfun!='')
				$function = "onChange='".$optionfun."'";

			if($multiselect ==true)
				$multi_select = "multiple='multiple'";


			$groupdropdown .= "<select name='".$select_name."' id='".$select_name."' $multi_select  $size $extraval style=\"width:$width\" $function>";
			$groupdropdown .= "<option value='' selected>".$title."</option>";



			for($i=0;$i<count($dbSelectRs);$i++)
			{
				$cid = $dbSelectRs[$i][$fieldId];
				$cname = $dbSelectRs[$i][$fieldName];
				$extname = $dbSelectRs[$i][$extVal];

				if ($extVal != "")
				{
					$vData = "$cname ( $sym$extname )";
				}
				else
				{
					$vData = "$cname";
				}

				if($cid==$selectedVal)
				{
					$groupdropdown .= "<option value=\"$cid\" selected>".$vData."</option>";
				}
				else
				{
					$groupdropdown .= "<option value=\"$cid\">".$vData."</option>";
				}
			}
			$groupdropdown .= "</select>";
			//echo $groupdropdown;
			//exit;
			return $groupdropdown;
		}
		/**/
		function FormateDate($date,$dateFormat)
		{
			$return_date = $this->SimpleQuery("SELECT DATE_FORMAT('".$date."','".$dateFormat."') as date");
			return $return_date[0]["date"];
		}

		/*
		*   InQuery
		*   Desc: Retrieve parameters of the in query.
		*   Parms:
		*   $table - comma separated list of table names.
		*   $fields - field name.
		*   $where - SQL Where clause (e.g. "where id=2").
		*   $groupBy - SQL Group clause (e.g. "group by name").
		*   $orderBy - SQL Order clause (e.g. "order by name").
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   2d array of rows and columns on success.
		*   Error String on failure.
		*/
		function InQuery($table, $field, $where="",$limit="",$showDebug=false, $groupBy="", $orderBy="" )
		{
			$fields = $this->SelectQuery($table,$field,$where,$limit,$showDebug,$groupBy,$orderBy);

			$filedArray = array();

			for($i=0;$i<count($fields);$i++)
			{
				$filedArray[] = $fields[$i][$field];
			}

			$returnStr = implode("','",$filedArray);

			return "IN('".$returnStr."')";
		}
		
		function LastInsertedID()
		{
  			return $this->lastInsertedId;			
		}
		
		function GetAutoIncrementValue($table_name)
		{
			$sql = "SHOW TABLE STATUS LIKE '".$table_name."'";
			$row = $this->SimpleQuery($sql);			
			return $row[0]['Auto_increment'];
		}
		
		
		/*
		*   ExecuateQuery
		*   Desc: It will simply excute the query .
		*   Parms:
		*   $query - query which we want to execuate.
		*   $showDebug - If true then print SQL query.
		*   Returns:
		*   nothing.
		*   Error String on failure.
		*/
		function ExecuateQuery($query,$showDebug=false)
		{

		

			$stmt = mysql_query ($query,$this->dbLinkId);
			if($showDebug)
				echo "<Br>your query is: ".$query;

			if ($stmt == false)
			{

				echo mysql_errno().": ".mysql_error()."<br>";
				echo "<Br>your query is: ".$query;
				die();
			}

			
		}
		/*
		  *   MySqlInsertID
		  *   Desc: It will return the last inserted id .
		  *   Parms:
		  *   $query - .
		  *   $showDebug - .
		  *   Returns:
		  *   nothing : Last Inserted id
		  *   Error String on failure.
		*/
		function MySqlInsertID()
		{
			return mysql_insert_id();
		}
	}
?>