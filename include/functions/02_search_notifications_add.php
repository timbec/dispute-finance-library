<?php 


function search_notification_add($search,$restypes,$archive)
{
global $userref;
if (sql_value("SELECT COUNT(*) AS value FROM search_saved WHERE owner='{$userref}' AND search='" . escape_check($search) . "' AND restypes='" . escape_check($restypes) . "'  ",0)!=0)
    {
    return;		// we do not want dupes or empty searches
    }
if ($archive=="")
    {
    $archive=0;
    }
$restypes_names = sql_value("SELECT GROUP_CONCAT(name SEPARATOR ', ') AS value FROM resource_type WHERE ref IN('" .
implode("','",explode(",",$restypes))
. "')","");

// Because search can contain direct node IDs searches like @@257
// We resolve them back into node names
$rebuilt_search  = $search;
$node_bucket     = array();
$node_bucket_not = array();
$searched_nodes  = resolve_given_nodes($rebuilt_search, $node_bucket, $node_bucket_not);

foreach($node_bucket as $searched_nodes)
    {
    $searched_node_data = array();

    foreach($searched_nodes as $searched_node)
        {
        if(!get_node($searched_node, $searched_node_data))
            {
            continue;
            }

        $rebuilt_search .= rebuild_specific_field_search_from_node($searched_node_data);
        }
    }
$rebuilt_search = str_replace('"', '', $rebuilt_search);

sql_query("INSERT INTO search_saved(created,owner,title,search,restypes,archive,enabled) VALUES (
    NOW(),
    '{$userref}',
    '\"" . escape_check($rebuilt_search) . '"' . ($restypes_names == "" ? "" : " (" . escape_check(i18n_get_translated($restypes_names)) . ")") . "',
    '" . escape_check($search) . "',
    '" . escape_check($restypes) . "',
    '" . escape_check($archive) . "',
    1
)");
search_notification_process($userref,sql_insert_id());
}