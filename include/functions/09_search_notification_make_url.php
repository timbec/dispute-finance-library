<?php 


function search_notification_make_url($search,$restypes,$archive)
{
global $baseurl;
return $baseurl . "/pages/search.php?search=" . urlencode($search) . "&restypes=" . urlencode($restypes) . "&archive=" . $archive;
}