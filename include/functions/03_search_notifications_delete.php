<?php 


function search_notification_delete($ref,$force=false)
{
if ($force)
    {
    sql_query("DELETE FROM search_saved WHERE ref='{$ref}'");
    }
else
    {
    global $userref;
    sql_query("DELETE FROM search_saved WHERE ref='{$ref}' AND owner='{$userref}'");
    }
}