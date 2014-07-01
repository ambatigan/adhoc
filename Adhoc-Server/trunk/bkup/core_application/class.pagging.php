<?php
	class pagging
	{
	   /* These are defaults */
	   var $TotalResults;
	   var $CurrentPage = 1;
	   var $PageVarName = "pageno";
	   var $ResultsPerPage = 5;
	   var $LinksPerPage = 10;
	   var $PaggingMethod = "get";
	   var $StartOffset="";
	   var $EndOffset="";


		function Redirect($url)
		{
			unset($_GET['pageno']);
			foreach($_GET as $key=>$value)
			{
				$pagging_vars.= "&".$key."=".$value;
		    }
			$pagging_vars = ltrim($pagging_vars,'&');
			@header("Location: ?".$pagging_vars);
			exit;
		}
	   function pagging($ResultsPerPage,$PaggingMethod="get")
	   {
	   		$this->ResultsPerPage = $ResultsPerPage;
			$this->PaggingMethod = $PaggingMethod;
	   }

	   function InfoArray() {
		  $this->TotalPages = $this->getTotalPages();
		  $this->CurrentPageName = $this->CurrentPageName();
		  $this->CurrentPage = (isset($attribute['CURRENT_PAGE']))?$attribute['CURRENT_PAGE']:$this->getCurrentPage();

		 	// Set the starting offset
			
		  if($this->CurrentPage <= 0)
		  {
		  	$this->Redirect('?'.$pagging_vars);
		  }
		  if(($this->CurrentPage>$this->TotalPages) && ($this->TotalResults!=0))
		  {
		  		$this->CurrentPage=1;
                // Temporary commented for displaying two grid in a page
		  		//$this->Redirect('?'.$pagging_vars);
		  }
		  if(($this->CurrentPage==1 && $this->TotalResults>0))
		  {
		  	$this->StartOffset=1;
		  }
		  elseif($this->CurrentPage==1 && $this->TotalResults==0)
		  {
		  	$this->StartOffset=0;
		  }
		  else
		  {
	   	  $this->StartOffset=$this->getStartOffset()+1;
		  }

		  	// Set the ending offset
		  if($this->CurrentPage==$this->TotalPages)
		  {
		  $this->EndOffset=$this->TotalResults;
		  }
		  else if($this->CurrentPage==1)
		  {
		  $this->EndOffset=$this->getEndOffset();
		  }
		  else
		  {
		  $this->EndOffset=$this->getEndOffset()+1;
		  }

		  $this->ResultArray = array(
							   "PREV_PAGE" => $this->getPrevPage(),
							   "NEXT_PAGE" => $this->getNextPage(),
							   "CURRENT_PAGE" => $this->CurrentPage,
							   "CURRENT_PAGE_NAME" => $this->CurrentPageName,
							   "TOTAL_PAGES" => $this->TotalPages,
							   "TOTAL_RESULTS" => $this->TotalResults,
							   "PAGE_NUMBERS" => $this->getNumbers(),
							   "MYSQL_LIMIT1" => $this->getStartOffset(),
							   "MYSQL_LIMIT2" => $this->ResultsPerPage,
							   "START_OFFSET" => $this->StartOffset,
							   "END_OFFSET" => $this->EndOffset,
							   "RESULTS_PER_PAGE" => $this->ResultsPerPage,
							   );

		  return $this->ResultArray;
	   }

	   /* Start information functions */
	   function getTotalPages() {
		  /* Make sure we don't devide by zero */
		  
		  if($this->TotalResults != 0 && $this->ResultsPerPage != 0) {
		   $result = ceil($this->TotalResults / $this->ResultsPerPage);
		  }
		  /* If 0, make it 1 page */
		  if((isset($result) && $result == 0) || !isset($result)) {
		  	 return 1;
		  } else {
		  	 return $result;
		  }
	   }

	   function getStartOffset() {
	   	  $offset = $this->ResultsPerPage * ($this->CurrentPage - 1);

		  //if($offset != 0)
		  //{
		        //$offset++;
		  //}


		  return $offset;

	   }

	   function getEndOffset() {
		  if($this->getStartOffset() > ($this->TotalResults - $this->ResultsPerPage)) {
			 $offset = $this->TotalResults;
		  } elseif($this->getStartOffset() != 0) {
			 $offset = $this->getStartOffset() + $this->ResultsPerPage - 1;
		  } else {
			 $offset = $this->ResultsPerPage;
		  }
		  return $offset;
	   }

	   function getCurrentPage() {
		  if(isset($_GET[$this->PageVarName]) && $_GET[$this->PageVarName]!='' && $_GET[$this->PageVarName]!=0) 
		  {
		  	 return $_GET[$this->PageVarName];
		  } 
		  else if(isset($_POST[$this->PageVarName]) && $_POST[$this->PageVarName]!='' && $_POST[$this->PageVarName]!=0) 
		  {
		  	 return $_POST[$this->PageVarName];
		  } 
		  else 
		  {
			 return $this->CurrentPage;
		  }
	   }

	   function CurrentPageName() {
	     if($_REQUEST['role_id']!='')
		 {
		 //	$concate_url='&role_id='.$_REQUEST['role_id'];
		 }
		 return $_REQUEST['page'].$concate_url;
	   }

	    function CurrentPageRequest() {
		 return $_REQUEST['page'];
	   }



	   function getPrevPage() {
		  if($this->CurrentPage > 1) {
			 return $this->CurrentPage - 1;
		  } else {
			 return false;
		  }
	   }

	   function getNextPage() {
		  if($this->CurrentPage < $this->TotalPages) {
			 return $this->CurrentPage + 1;
		  } else {
			 return false;
		  }
	   }

	   function getStartNumber() {
		  $links_per_page_half = $this->LinksPerPage / 2;
		  /* See if curpage is less than half links per page */
		  if($this->CurrentPage <= $links_per_page_half || $this->TotalPages <= $this->LinksPerPage) {
			 return 1;
		  /* See if curpage is greater than TotalPages minus Half links per page */
		  } elseif($this->CurrentPage >= ($this->TotalPages - $links_per_page_half)) {
			 return $this->TotalPages - $this->LinksPerPage + 1;
		  } else {
			 return $this->CurrentPage - $links_per_page_half;
		  }
	   }

	   function getEndNumber() {
		  if($this->TotalPages < $this->LinksPerPage) {
			 return $this->TotalPages;
		  } else {
			 return $this->getStartNumber() + $this->LinksPerPage - 1;
		  }
	   }

	   function getNumbers() {
		  for($i=$this->getStartNumber(); $i<=$this->getEndNumber(); $i++) {
			 $numbers[] = $i;
		  }
		  return $numbers;
	   }

	   function print_pagging($extra_vars="",$style=1)
	   {
            /* Print out our prev link */
			if($style==1)
			{
				$previous_link_name = "Newer";
				$next_link_name = "Older";
			}
			else if($style==2)
			{
				$previous_link_name = "Previous";
				$next_link_name = "Next";
			}
            $InfoArray = $this->InfoArray();			
            // Added Pagging Style CSS For ADMIN PANEL 23-4-2013
			if($style==3)
            {
				unset($extra_vars['page']);
				unset($extra_vars['pageno']);
				unset($extra_vars['internal']);
				foreach($extra_vars as $key=>$value)
				{
					unset($extra_vars['pageno']);
				   	$pagging_vars.= "&".$key."=".$value;
			    }

                if($this->PaggingMethod=="post")
                {
                    $ret = "<ul class='clearfix'>";
					/* Print out our prev link */
                    if($InfoArray["PREV_PAGE"])
                    {
                        $ret.="<li class='paging-tp'><a class='link-pre-next' href=javascript:submitPage('".$InfoArray["PREV_PAGE"]."')>Prev</a></li>";
                    }
                    else
                    {
                        //	$ret.="Previous";
                    }
                    $ret.= "  ";

				    for($i=0; $i<count($InfoArray["PAGE_NUMBERS"]); $i++)
				    {
					    if($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i])
					    {
						    $ret.="<li class='active'><a style=\"background-color:#391B10;color:#FFFFFF;font-weight:bold;\" >".$InfoArray["PAGE_NUMBERS"][$i] . "</a></li> ";
					    }
					    else
					    {
				  		    $ret.="<li><a class='link-pre-next' href=javascript:submitPage('".$InfoArray["PAGE_NUMBERS"][$i]."')>" . $InfoArray["PAGE_NUMBERS"][$i] . "</a></li>  ";
					    }
				    }

				    /* Print out our next link */
				    if($InfoArray["NEXT_PAGE"])
				    {
					    $ret.= " <li class='paging-tp'><a class='link-pre-next' href=javascript:submitPage('".$InfoArray["NEXT_PAGE"]."')>Next</a></li>";
				    }
				    else
				    {
				        //  $ret.="Next";
				    }
                    $ret.= "</ul>";
			    }
			    else
			    {
                    $ret = '<ul class="clearfix">';
                    if($InfoArray["CURRENT_PAGE"]!= 1)
                    {
                        $ret.="<li class='paging-tp'><a href='?pageno=1&page=".$InfoArray["CURRENT_PAGE_NAME"]. $pagging_vars."' title='First'><img src='../images/theme_images/first.png' alt='First' /></a></li>";
                    }

					/* Print out our prev link */
				    if($InfoArray["PREV_PAGE"])
				    {
                        $pagging_vars=$this->pageList($pagging_vars,$InfoArray["PREV_PAGE"]);
					    $ret.="<li class='paging-tp'><a href='?pageno=".$InfoArray["PREV_PAGE"]."&page=".$InfoArray["CURRENT_PAGE_NAME"]. $pagging_vars."' title='Prev'><img src='../images/theme_images/prev.png' alt='Prev' /></a></li>";
				    }
				    else
				    {
				        //	$ret.="Previous";
				    }

				    for($i=0; $i<count($InfoArray["PAGE_NUMBERS"]); $i++)
				    {
					    if($InfoArray["CURRENT_PAGE"] == $InfoArray["PAGE_NUMBERS"][$i])
					    {
						    $ret.="<li class='active'><a style=\"background-color:#391B10;color:#FFFFFF;font-weight:bold;\" >".$InfoArray["PAGE_NUMBERS"][$i] . "</a></li> ";
					    }
					    else
					    {
                            $pagging_vars=$this->pageList($pagging_vars,$InfoArray["PAGE_NUMBERS"][$i]);
				  		    $ret.="<li><a href='?pageno=".$InfoArray["PAGE_NUMBERS"][$i]."&page=".$InfoArray["CURRENT_PAGE_NAME"].$pagging_vars."'>" . $InfoArray["PAGE_NUMBERS"][$i] . "</a></li>  ";
					    }
				    }
				    /* Print out our next link */
				    if($InfoArray["NEXT_PAGE"])
				    {
                        $pagging_vars=$this->pageList($pagging_vars,$InfoArray["NEXT_PAGE"]);
					    $ret.= " <li class='paging-tp'><a href='?pageno=".$InfoArray["NEXT_PAGE"]."&page=".$InfoArray["CURRENT_PAGE_NAME"]. $pagging_vars."'><img src='../images/theme_images/next.png' alt='Next' /></a></li>";
				    }
				    else
				    {
				        //  $ret.="Next";
				    }

                    /* Print our last link */
			    	if($InfoArray["CURRENT_PAGE"]!= $InfoArray["TOTAL_PAGES"])
				    {
                        $ret.=" <li class='paging-tp'><a href='?page=".$InfoArray["CURRENT_PAGE_NAME"]."&pageno=" . $InfoArray["TOTAL_PAGES"]."' title='Last'><img src='../images/theme_images/last.png' alt='last' /></a></li>";
				    }
				    else
				    {
				    // $ret.=" &gt;&gt;";
				    }
                    $ret.="</ul>";
			    }
            }
            return $ret;
		}

        function pageList($pagging_vars,$data)
        {
            $gridId=1;
            $pageList;
            $tmpArray=explode('&',$pagging_vars);
            foreach($tmpArray as $key => &$value)
            {
                $tmpVar=explode('=',$value);

                if($tmpVar[0] == 'grid')
                {
                    $gridId=$tmpVar[1];
                }
                if($tmpVar[0] == 'page_list')
                {
                    $pageList=explode(',',$tmpVar[1]);
                    $pageList[$gridId-1]=$data;

                    $pageListString=implode(',',$pageList);
                    $tmpVar[1]=$pageListString;
                }
                $value=implode('=',$tmpVar);
            }
            $pagging_vars=implode('&',$tmpArray);
            return $pagging_vars;
        }
	}
?>