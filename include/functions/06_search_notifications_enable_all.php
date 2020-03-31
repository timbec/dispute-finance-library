<?php

//NOTE: Don't need this one either
function search_notification_enable_all($force=false)
{
    ?>
		<script>
			alert('search_notification_enable_all() function called'); 
		</script>
		<?php
if ($force)
    {
    sql_query("UPDATE search_saved SET enabled=1");
    }
else
    {
    global $userref;
    sql_query("UPDATE search_saved SET enabled=1 WHERE owner='{$userref}'");
    }
}