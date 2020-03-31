<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	global $baseurl,$watched_searches_url;

	$watched_searches_url = $baseurl . '/plugins/rse_search_notifications/pages/watched_searches.php';

	include "functions/00_debugging_functions.php"; 
	include "functions/01_search_notifications_get.php"; 
	include "functions/02_search_notifications_add.php"; 
	include "functions/03_search_notifications_delete.php"; 
	include "functions/04_search_notifications_enable.php"; 
	include "functions/05_search_notifications_disable.php"; 
	include "functions/06_search_notifications_enable_all.php"; 
	include "functions/07_search_notification_disable_all.php"; 
	include "functions/08_search_notification_process.php"; 
	include "functions/09_search_notification_make_url.php"; 