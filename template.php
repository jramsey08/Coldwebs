<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// COLDWEBS TEMPLATE.PHP FILE USED TO DETERMINE WHAT WOULD BE DISPLAYED ON THE VISITORS COMPUTER SCREEN DEPENDING   // 
// ON THE WEBSITE AND URL THAT IS PROVIDED. THIS FILE IS VERY ESSENTIAL TO THE FUNCTION OF THIS WEBSITE AND ANY     //
// CHANGES SHOULD ONLY BE MADE BY THOSE WHO ARE FAMILIAR TO THE COLDWEB CONTENT MANAGEMENT SYSTEM CODING STRUCTURE. //
// TO ENSURE YOUR SITE IS FULLY SAFE PLEASE ENROLL YOUR SITE AT COLDWEBS.COM TO ENSURE YOUR WEBSITE IN MONITORED    // 
// DAILY AGAINST ALL THREATS AND ARE RECEIVING ALL NEEDED UPDATES.                                                  //
// AUTHOR: CEO, JUBAR D. RAMSEY     "VISIT COLDWEBS.COM TO BECOME A COLDWEBS CMS PLATFORM DEVELOPER."               //
// FILE VERSION 3.0 LAST UPDATED ON 2015-10-05                                                                      //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





// INITIALIZE VARIABLES \\
$ThemeLayout = "$THEME/layout.php";
$THEME = "theme/" . $SiteInfo['theme'];
$Structure_Type = "default";

// DETERMINE THE TEMPLATE BASED ON THE PAGE REQUESTED  \\
$query = "SELECT * FROM page_template WHERE url='$Get_Url' AND active='1' AND trash='0'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
if($row['active'] == "1"){
    $Page_Structure_Article = $row['article'];
    if($Get_Type == ""){
        if($row['template'] == "default" OR $row['template'] == ""){
            $Structure_Template = $Array['sitetheme'];
        }else{
            $Structure_Template = $row['template'];
        }
        $theme = $Structure_Template;
        $THEME = "theme/$theme";
        $Array['page']['template'] = $row;
        unset($row);
// PULL NECESSARY SETTINGS BASED ON THE PAGE REQUESTED \\
        $query = "SELECT * FROM page_settings WHERE article='$Get_Url'";
        $result = mysql_query($query) or die(mysql_error());
        $row = mysql_fetch_array($result);
        $Login_Required = $row['secure'];
        $Array['page']['settings'] = $row;
        unset($row);
// SELECT THE CORRECT PAGE STRUCTURE BASED ON THE PAGE REQUESTED \\
        $query = "SELECT * FROM page_structure WHERE url='$Get_Url' AND urltype='$Get_Type' AND end='$Get_End' AND urlid='$Get_Id' AND active='1' AND trash='0' AND template='$Structure_Template' 
        OR url='$Get_Url' AND urltype='$Get_Type' AND end='$Get_End' AND urlid='$Get_Id' AND active='1' AND trash='0' AND template='default'";
        $result = mysql_query($query) or die(mysql_error());
        $row = mysql_fetch_array($result);
        $Structure_Type = $row['type'];
        $Array['page']['structure'] = $row;
        unset($row);
    }
}

//INCLUDE THEME SETTINGS.PHP FILE TO LOAD THEME SPECIFIC VARIABLES \\
if($theme == ""){
    $theme = $SiteInfo['theme'];
    $THEME = "theme/$theme";
}
	//INCLUDE IMPORTANT THEME INFORMATION \\
$filename = "$THEME/settings.php";
if(file_exists($filename)){
    include("$THEME/settings.php");
}

// DETERMINES THE ARTICLE INFORMATION FOR THE REQUESTED PAGE \\
    // SEARCH THE ARTICLES TABLE FOR INFORMATION NEEDED TO PULL THE REQUESTED CATEGORY INFORMATION \\

$CategoryInfo = $row;
unset($row);
    if($CategoryInfo['other']['structure'] == ""){
}else{
    $Structure_Type = $CategoryInfo['other']['structure'];
}
$CategoryId = $CategoryInfo['id'];
$Array['category'] = $CategoryInfo;
    // SEARCH THE ARTICLES TABLE FOR INFORMATION NEEDED TO PULL THE REQUESTED POST INFORMATION \\
if($Cw_Multiple_Cat['active'] == "1"){
	$query = "SELECT * FROM articles WHERE id='$Page_Structure_Article' AND category LIKE '%-" . $CategoryId . "-%' AND active='1' AND trash='0' AND type='post' OR 
	url='$Get_Type' AND category LIKE '%-" . $CategoryId . "-%'  AND active='1' AND trash='0' AND type='post' OR id='$Page_Structure_Article' AND type='post' AND active='1' AND trash='0'OR url='$Get_Type' 
    AND active='1' AND trash='0'";
}else{
	$query = "SELECT * FROM articles WHERE id='$Page_Structure_Article' AND type='post' AND category='$CategoryId' AND active='1' AND trash='0' OR 
    url='$Get_Type' AND type='post' AND category='$CategoryId' AND active='1' AND trash='0' OR id='$Page_Structure_Article' AND type='post' AND active='1' AND trash='0' OR url='$Get_Type' 
    AND active='1' AND trash='0'";
}
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
$row = PbUnSerial($row);
$PostInfo = $row;
unset($row);
if($PostInfo['other']['structure'] == ""){
}else{
    $Structure_Type = $PostInfo['other']['structure'];
}
$PostId = $PostInfo['id'];
$Array['post'] = $PostInfo;
    // INITIALIZES THE VARIABLE WITH THE NECESSARY INFORMATION FOR THE REQUESTED PAGE \\
if($OverRight['cwmedia'] == "1"){
    $query = "SELECT * FROM articles WHERE url='$Get_Type' AND type='$OverRight[cwmediatype]' AND active='1' AND trash='0' OR 
    id='$Get_Type' AND type='$OverRight[cwmediatype]' AND active='1' AND trash='0'";
    $result = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_array($result);
    $row = PbUnSerial($row);
    $ActiveArticle = $row;
	unset($row);
}else{
    if($CategoryId == ""){
        if($PostId == ""){
		}else{
		    $ActiveArticle = $PostInfo;
	    }
    }else{
        $ActiveArticle = $CategoryInfo;
    }
}

// SETS DEFAULT PAGE STRUCTURE BASED ON REQESTED PAGE, IF NO STRUCTURE WAS SELECTED \\
if($Get_Url == "unlisted" AND $Get_Type != ""){
	if($PostInfo['active'] == "1" ){
		$Structure_Type = DEFAULTSTRUCTUREPOST;
	}
}
$StructureLoc = "$THEME/structure/settings.php";
if($Structure_Type == ""){
	if(file_exists($StructureLoc)){
		if($ActiveArticle['type'] == "category" ){
			$Structure_Type = DEFAULT_STRUCTURE_CATEGORY;
		}else if($ActiveArticle['type'] == "post"){
			$Structure_Type = DEFAULT_STRUCTURE_POST;
		}
	}else{
		$Structure_Type = "default";
	}
}
    // ??? DETERMINE WHAT THIS CODE IS MEANT FOR ??? \\
if($Get_Url == "home"){
}else{
    if($View_site ==" 0"){
        if($ColdWeb_Control == "1"){
            $Structure_Show = "0";
        }else{
            $Structure_Show = "1";
        }
    }else{
        $Structure_Show = "1";
    }
    if($Structure_Show == "1"){
        if($Structure_Type == "" OR $Structure_Type == "default"){
            include("config/structure.php");
            $Default_Structure = $ActiveArticle['type'];
            $Structure_Type = $StructureDefault["$Default_Structure"];
        }
    }
}

//SES DEFAULT INFORMATION DEPENDING ON THE REQUESTIONG PAGE \\
    // CHECKS AGAINST ROOT URL FILES \\
if($ActiveArticle['type'] == "root"){
    include("$ActiveArticle[info]");
}else{
	// COUNT THE AMOUNT OF VIEWS EACH ARTICLE RECEIVES \\
    $NewHits = $ActiveArticle['hits'] + 1;
    if($NewHits > "99"){
       $NewHits = "0" . $NewHits;
    }
    $result = mysql_query("UPDATE articles SET hits='$NewHits' WHERE id='$ActiveArticle[id]'") 
    or die(mysql_error());
    // SETS THE DATE FOR ANY ARTICLE THAT HAS FAILED TO SET ONE \\
    if($ActiveArticle['date'] == ""){
        $result = mysql_query("UPDATE articles SET date='$Date' WHERE id='$ActiveArticle[id]'") 
        or die(mysql_error());
    }
    // DETECT AND LOAD MOBILE THEME \\
    if($Mobile['phone'] == "1"){
        include("$THEME/settings.php");
        if($Array['other']['mobiletheme'] == ""){
            if(TEMPLATEMOBILE == ""){
            }else{
                $theme = TEMPLATEMOBILE;
            }
        }else{
            $theme = $Array['other']['mobiletheme'];
        }
        $THEME = "theme/$theme";
    }

// DISPLAY A CERTAIN PAGE DEPENDING ON THE USER ACCESS WHEN THE WEBSITE IS OFFLINE \\
    if($UserSiteAccess['viewoffline'] == "1"){
    }else{
        if($View_site == "0"){
            if($ColdWeb_Control == "1"){
                SiteOffline($Array);
            }else{
                $theme = $OfflineTheme;
                $THEME = "theme/$theme";
                $Structure_Type = "default";
                $ActiveArticle = $Offline_Article;
            }
        }
    }
    $Array['activearticle'] = $ActiveArticle;

// MANUAL URL OVERRIDE REQUESTS \\
    if($OverRight['file'] == ""){
    }else{
        if($OverRight['theme'] == "default"){
            $THEME = $THEME;
        }else{
            $THEME = $OverRight['theme'];
        }
        $Structure_Type = $OverRight['file'];
    }

// USES DEFAULT SETTINGS \\
    if($theme == ""){
        if($View_site == "1"){
            $theme = CWDEFAULTTHEME;
        }else{
            $theme = CWDEFAULTOFFLINETHEME; 
        }
        $THEME = "theme/$theme";
    }

// VERIFY ALL REQUESTED STRUCTURE FILES EXISTS AND SHOW DEFAULT LAYOUTS IF NOT FOUND \\ 
    $filename = "$THEME/structure/$Structure_Type.php";
    if(file_exists($filename)){
        $Structure_Type = $Structure_Type;
    }else{
        $errorfile = "$THEME/structure/404.php";
        if(file_exists($errorfile)){
            $Structure_Type = "404";
        }else{
            $Structure_Type = "default";
        }
    }

	if($ActiveArticle['id'] == ""){
		if($Get_Url == "home" OR $Get_Url == ""){
		}else{
			if($Structure_Type != ""){
				$Structure_Type = $Structure_Type;
			}else{
				$errorfile = "$THEME/structure/404.php";
				if(file_exists($errorfile)){
					$Structure_Type = "404";
				}else{
					$Structure_Type = "default";
				}
			}
		} 
	}

// CONNECT TO THE APPROPRIATE THEME AND STRUCTURE \\
    include("$THEME/functions.php");
    include("$THEME/settings.php");
    if($Login_Required == "1"){
        include("forms/logincheck.php");
    }
    include("$ThemeLayout");
}
