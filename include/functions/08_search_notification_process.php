<?php

	function search_notification_process($owner=-1,$search_saved=-1)
		{ ?>
		<script>
			alert('search_notification_process function called'); 
		</script>
		<?php
		global $lang,$baseurl,$search_notification_max_thumbnails;

		$saved_searches=sql_query("SELECT * FROM search_saved WHERE enabled=1" .
			($owner==-1 ? "" : " AND owner='{$owner}'") .
			($search_saved==-1 ? "" : " AND ref='{$search_saved}'") .
			" ORDER BY owner"
		);

		if (!function_exists("do_search"))
			{
			include __DIR__ . "/../../../include/search_functions.php";
			}

		foreach ($saved_searches as $search)
			{
			// pr('Saved Search from search_notification_process: '); 
			// 	vd($search); 
			$results=do_search($search['search'],$search['restypes'],'resourceid',$search['archive']);
			$resources_found=array(); 
			// var_dump($results);
			print_r('From line 26: ');
				vd($results[0]); 

		
			if (is_array($results) && count($results) > 0)
				{
				foreach ($results as $result)
				// //this provides the match
				// print_r('From line 30: ');
				// vd($result); 
					{
					array_push($resources_found, $result['ref']);
					}
				}

			$checksum_data=implode(',',$resources_found);
			$checksum=sha1('#' . $checksum_data);		// the '#' avoids blank checksum if no resources found
			$checksum_matches=count($resources_found);

			if ($checksum==$search['checksum'])		// nothing has changed so process the next saved search
				{
				continue;
				}

			if ($search['checksum'] != "")		// this search has been run before so work out differences
				{

				$resources_existing=$search['checksum_data']=='' ? array() : explode(',',$search['checksum_data']);		// ensure empty resource list produces zero array entries

				$resources_subtracted=array_diff($resources_existing,$resources_found);
				$resources_added=array_diff($resources_found,$resources_existing);
				$resources_added_count=count($resources_added);
				$resources_subtracted_count=count($resources_subtracted);

				$message=
				($resources_added_count == 1 ? "{$resources_added_count} {$lang['search_notifications_new match_found']}" : "") .
				($resources_added_count > 1 ? "{$resources_added_count} {$lang['search_notifications_new matches_found']}" : "") .
				($resources_added_count > 0 && $resources_subtracted_count > 0 ? " {$lang['and']} " : "") .
				($resources_subtracted_count == 1 ? "{$resources_subtracted_count} {$lang['search_notifications_resource_no_longer_matches']}" : "") .
				($resources_subtracted_count > 1 ? "{$resources_subtracted_count} {$lang['search_notifications_resources_no_longer_match']}" : "") .
				" {$lang['search_notifications_for_watch_search']} " . escape_check($search['title']) . "<br />";
				print_r('From line 63: '); 
				var_dump($message);  

				$added_to_message_count = 0;

				foreach ($resources_added as $resource_added)
					{
					if ($added_to_message_count == $search_notification_max_thumbnails)
						{
						break;
						}

					$thumb_file=get_resource_path($resource_added,true,'col');
					if (file_exists($thumb_file))
						{
						$thumb_url=get_resource_path($resource_added,false,'col');
						$message.="<a href='{$baseurl}/pages/view.php?ref={$resource_added}&search={$search['search']}&restypes={$search['restypes']}&archive={$search['archive']}'";
						$message.=" onclick='return ModalLoad(this,true);'>";
						$message.="<img src='{$thumb_url}' >";
						$message.="</a>";
						$added_to_message_count++;
						}
					}

				message_add(
					$search['owner'],
					$message,
					search_notification_make_url($search['search'],$search['restypes'],$search['archive'])
				);

				}

			// finally update with the new checksum, timestamp and resources
			sql_query("UPDATE search_saved SET checksum='{$checksum}',checksum_matches='{$checksum_matches}',checksum_when=NOW(),checksum_data='" . escape_check($checksum_data) . "' WHERE ref='{$search['ref']}'");

			}		// end for each saved search
		}