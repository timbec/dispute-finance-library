<?php 

// NOTE: Don't need this one
function search_notification_disable($ref,$force=false)
{
if ($force)
    {
    sql_query("UPDATE search_saved SET enabled=0 WHERE ref='{$ref}'");
    }
else
    {
    global $userref;
    sql_query("UPDATE search_saved SET enabled=0 WHERE ref='{$ref}' AND owner='{$userref}'");
    }
}