<?php 

// *I think* retrieves the saved search. 
function search_notifications_get(&$results, $user="",$enabled_only=true,$search="",$orderby=1,$orderbydirection="DESC")
{
$results=sql_query(
    "SELECT search_saved.*,u.username FROM search_saved JOIN `user` u ON search_saved.owner=u.ref " .
    ($user=="" ? "" : " WHERE `owner`='{$user}'") .
    ($enabled_only ? ($user=="" ? " WHERE `enabled`=1" : " AND `enabled`=1" ) : "") .
    ($search=="" ? "" : " AND (title LIKE '%{$search}%' OR u.username LIKE '%{$search}%')") .
    " ORDER BY '{$orderby}' '{$orderbydirection}'"
);

// outputs the list of saved searches
return count($results) > 0;
}