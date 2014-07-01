<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="<?php echo CSS_PATH; ?>stylesheet.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo JS_PATH?>jquery-1.9.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$(".tab-headings li a").click(function()
			{
				var thisId = $(this).attr("rel");
				$(".tab-headings li").removeClass("selected");
				$(this).parent('li').addClass("selected");
				$(".profile-content").hide();
				//alert(thisId);
				$(".add-comment-box").hide();
				$(thisId).show();
		});
	});
</script><!--Accordion Jquery -->
<script type="text/javascript" src="<?php echo JS_PATH?>ddaccordion.js"></script>
<script type="text/javascript">
ddaccordion.init({
	headerclass: "expandable", //Shared CSS class name of headers group that are expandable
	contentclass: "left-subnav", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false
	defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc]. [] denotes no content
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", "openheader"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["prefix", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})
</script>

<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH ?>tabcontent.css" />
<script type="text/javascript" src="<?php echo JS_PATH ?>tabcontent.js"></script>
<script language="javascript" src="<?php echo JS_PATH ?>common.js"></script>
</head>
<?php $headerNotIncludePages = array('login');?>
<body <?php if(in_array($page,$headerNotIncludePages)){?>class="login-wrapper"<?php }?>>
	<div id="wrapper">
      <!--main area start-->
        <?php if(!in_array($page,$headerNotIncludePages)){?>
            <?php include_once(ADMIN_VIEW_PATH."common_header.php");  ?>
        <?php }?>
        <?php if(!in_array($page,$headerNotIncludePages)){  ?>
        <div class="content clearfix">
            <table class="main-content-table" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                   	<td width="200px" class="left-sidebar" valign="top">
                        <?php include_once(ADMIN_VIEW_PATH."left_menu.php"); ?>
                    </td>
                    <td valign="top">
                       
                        <?php include(ADMIN_VIEW_PATH . $page . '.php');   ?>
                    </td>
                </tr>
            </table>
         </div>
        <?php } else {   ?>
        <?php include(ADMIN_VIEW_PATH . $page . '.php');   ?>
        <?php }?>
        <?php if(!in_array($page,$headerNotIncludePages)){ ?>
            <?php include_once(ADMIN_VIEW_PATH."common_footer.php");   ?>
        <?php }?>
</div>
</body>
</html>