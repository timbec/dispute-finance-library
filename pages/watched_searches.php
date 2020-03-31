<?php

DEFINE('WATCHED_SEARCHES_ITEMS_PER_PAGE',10);

include_once "../../../include/db.php";
include_once "../../../include/general.php";
include_once "../../../include/authenticate.php";
include_once "../../../include/collections_functions.php";
include_once "../../../include/search_functions.php";
include_once "../../../include/search_do.php";

include_once "../include/search_notifications_functions.php";

$all_users_mode=getval("allusers",0)==1 && checkperm("a");
$find=getvalescaped("find","");
$callback=getvalescaped("callback","");
$orderby = getvalescaped("orderby",-1);

if ($callback!="")
	{
	$ref = getvalescaped("ref", -1,true);
	$search = getvalescaped("search", "");
	$restypes = getvalescaped("restypes", "");
	$archive = getvalescaped("archive", "");

	//TODO - Can take some of these out.
	/**
	 * 1) callback set by 'Enable All' link, which sets a url that sets $callback to 'enable'. 
	 * 2) the 'check now' link sets the $callback to 'checknow'
	 * 3) 'checknow' calls the 'search_notification_process()' function.
	 * 4) 'enable' calls the 'search_notification_enable()' function.
	 * 5) 'enable all' calls the 'search_notification_enable_all()' function
	 * 6) Only 'search_notification_process()' will update the number and send a message. 
	 * 7) I think the live site automatically sends messages because I selected 'enabled'
	 * 8) The dropdown could be used here. Immediately, Daily, Weekly, None (disabled)
	 * 
	 */
	// $callback = "enable"; 
	switch ($callback)
		{
		case "add":
			search_notification_add($search,$restypes,$archive);
			break;

		// case "delete":
		// 	search_notification_delete($ref,$all_users_mode);
		// 	break;

		case "enable":
			search_notification_enable($ref,$all_users_mode);
			break;

		case "enable_all":
			search_notification_enable_all($all_users_mode);
			break;

		case "disable":
			search_notification_disable($ref,$all_users_mode);
			break;

		case "disable_all":
			search_notification_disable_all($all_users_mode);
			break;

		case "checknow":
			search_notification_process($userref,$ref);
			break;
		}
		vd('Line 57: ' . $callback); 
	}

include "../../../include/header.php";

$watched_searches=array();
$watched_searches_found=search_notifications_get($watched_searches,($all_users_mode ? "" : $userref),false,$find,abs($orderby),($orderby > 0 ? "ASC" : "DESC"));

// then number of found searches
// pr('From watched_searches.php, line 63: ' . $watched_searches_found); returns 1

// ----- Start of pager variables

$offset=getval('offset',0,true);
$totalpages=ceil(count($watched_searches)/WATCHED_SEARCHES_ITEMS_PER_PAGE);
// vd($totalpages); //float(1)
$curpage=floor($offset/WATCHED_SEARCHES_ITEMS_PER_PAGE)+1;
$per_page=WATCHED_SEARCHES_ITEMS_PER_PAGE;
$jumpcount=1;

$url_set_params = array();

if($find != "")
    {
    $url_set_params["find"] = $find;
    }
if($all_users_mode)
    {
    $url_set_params["allusers"] = 1;
	}
$watched_searches_url = "http://localhost:8888/dispute_finance_library/plugins/like_doc_notifications/pages/watched_searches.php"; 
// print_r($watched_searches_url); 	
$url = generateURL($watched_searches_url, array("offset" => $offset), $url_set_params);

//vd('Line 86: ' . $url); 

// ----- End of pager variables

include "watched_searches_markup.php"; 

include "../../../include/footer.php";
