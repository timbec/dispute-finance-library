<?php

function search_notification_disable_all($force=false)
{
    ?>
    <script>
        alert('search_notification_disable_all() function called'); 
    </script>
    <?php
if ($force)
    {
    sql_query("UPDATE search_saved SET enabled=0");
    }
else
    {
    global $userref;
    sql_query("UPDATE search_saved SET enabled=0 WHERE owner='{$userref}'");
    }
}