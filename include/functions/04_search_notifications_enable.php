<?php 


function search_notification_enable($ref,$force=false)
{
    ?>
		<script>
			alert('search_notification_enable() function called'); 
		</script>
		<?php
if ($force)
    {
    sql_query("UPDATE search_saved SET enabled=1 WHERE ref='{$ref}'");
    }
else
    {
    global $userref;
    sql_query("UPDATE search_saved SET enabled=1 WHERE ref='{$ref}' AND owner='{$userref}'");
    }
}