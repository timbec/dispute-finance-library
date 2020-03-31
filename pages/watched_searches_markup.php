<div class="BasicsBox">
	<h1><?php echo $lang["search_notifications_watched_searches"]; ?></h1>

	<p><?php echo $lang["search_notifications_introtext"]; ?></p>

	<div class="TopInpageNav">

		<form method="post" action="<?php echo $url; ?>" onsubmit="return CentralSpacePost(this,true);">
            <?php generateFormToken("like_doc_notifications_watched_searches"); ?>
			<div class="Question">
				<div class="tickset">
					<div class="Inline">
						<input type="text" name="find" id="find" value="<?php echo htmlspecialchars($find); ?>" maxlength="100" class="shrtwidth">
					</div>
					<input type="hidden" name="offset" id="offset" value="0" />
					<div class="Inline"><input name="Submit" type="submit" value="<?php echo $lang["searchbutton"]; ?>"></div>
					<div class="Inline"><input name="Clear" type="button" onclick="document.getElementById('find').value=''; return CentralSpacePost(this.form,true);" value="<?php echo $lang["clearbutton"]; ?>"></div>
				</div>
				<div class="clearerleft"> </div>
			</div>
		</form>
		
		<?php
		//vd(WATCHED_SEARCHES_ITEMS_PER_PAGE); //int(10)
			if (count($watched_searches) > WATCHED_SEARCHES_ITEMS_PER_PAGE)
			{
			?>
			<div class="TopInpageNavLeft">
				<?php pager(true) ?>
			</div>
			<?php
			}
		?><div class="clearerleft"></div>

	<?php

	if (checkperm("a"))
		{
		?><form action="<?php echo $watched_searches_url; ?>" onchange="CentralSpacePost(this,true);">
            <?php generateFormToken("rse_search_notifications_watched_searches"); ?>
			<input type="hidden" name="offset" id="offset" value="0" />
			<input type="hidden" name="find" id="find" value="<?php echo htmlspecialchars($find); ?>" >
			<label for="allusers"><?php echo $lang['search_notifications_show_for_all_users']; ?></label>
			<?php
			if ($all_users_mode)
				{
				?>
                <input type="checkbox" name="allusers" id="allusers" value="0" checked="checked"><br />
            <?php
				$url.="&allusers=1&";
				}
			else
				{
				?><input type="checkbox" name="allusers" id="allusers" value="1"><br /><?php
				}
			?><div class="clearerleft"></div>
		</form>
		<br />
		<?php
		}

	$any_enabled = false;
	$any_disabled = false;

	foreach ($watched_searches as $ws)		// if there are unread messages show option to mark all as read
	{
		if ($ws['enabled']==1)
			{
			$any_enabled=true;
			}
		else
			{
			$any_disabled=true;
			}
		if ($any_enabled && $any_disabled)
			{
			break;
			}
		}

	if ($any_enabled)
		{
		?><a href="<?php echo $url; ?>&callback=disable_all" onclick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php
		echo $lang['disable_all']; ?></a>
		<?php
		}

	if ($any_enabled && $any_disabled)
		{
		?><br /><?php
		}

	if ($any_disabled)
		{
		?><a href="<?php echo $url; ?>&callback=enable_all" onclick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php
		echo $lang['enable_all']; ?></a>
		
	<?php
	}
	function render_sortable_header($title,$col_number)
		{
		global $orderby,$url;
		if ($orderby==$col_number || $orderby==-$col_number) { ?><span class="Selected"><?php }
		?><a href="<?php echo $url; ?>&offset=0&orderby=<?php if($orderby==$col_number) { ?>-<?php } echo $col_number; ?>" onclick="return CentralSpaceLoad(this);" ><?php echo $title; ?></a><?php
		if ($orderby==$col_number) { ?><div class="ASC">&nbsp;</div><?php }
		if ($orderby==-$col_number) { ?><div class="DESC">&nbsp;</div><?php }
		if ($orderby==$col_number || $orderby==-$col_number) { ?></span><?php }
		}

?></div> <!-- end of TopInpageNav -->

<?php
// pr('Watched Searches Found, line 111: ' . $watched_searches_found); 
	if(!$watched_searches_found)
	{
		echo $lang['search_notifications_no_watched_searches'];
        ?>
</div> <!-- end of BasicsBox -->

        <?php
		include "../../../include/footer.php";
		return;
	}
?>

<div class="Listview">
		<table border="0" cellspacing="0" cellpadding="0" class="ListviewStyle">
			<tr class="ListviewTitleStyle">
				<td><?php render_sortable_header($lang["columnheader-title"],4); ?></td>
				<td><?php render_sortable_header($lang["created"],2); ?></td>
				<td><?php render_sortable_header($lang["username"],3); ?></td>
				<td><?php render_sortable_header($lang["columnheader-last-found"],11); ?></td>
				<td><div class="Notifications"><?php echo $lang["notifications"]; ?></div></td>
				<!-- <td><?php render_sortable_header($lang["columnheader-enabled"],8); ?></td> -->
				<td><div class="ListTools"><?php echo $lang["tools"]; ?></div></td>
			</tr>
			<?php
			for ($i=$offset; $i<$offset + WATCHED_SEARCHES_ITEMS_PER_PAGE; $i++)
				{
				if(!isset($watched_searches[$i]))
					{
					break;
					}
				$ws = $watched_searches[$i];
				$view_search_url = search_notification_make_url($ws['search'],$ws['restypes'],$ws['archive']);
				?><tr>
					<td>

					<a href="<?php echo $view_search_url; ?>">
					<?php echo highlightkeywords(htmlspecialchars($ws["title"]),$find); ?>
					</a>
					</td>
					<td>
					<?php echo nicedate(htmlspecialchars($ws["created"]), false, true, true); ?>
					</td>
					<td><?php echo highlightkeywords(htmlspecialchars($ws["username"]),$find); ?></td>
					<td><a href="<?php echo $view_search_url; ?>"><?php echo htmlspecialchars($ws["checksum_matches"]); ?></a></td>
					<td>
						<label for="notification-options">
						Notification Options
						</label>
						<select name="notification options" id="notification-options">
						
						<option value="immediately">Immediately</option>
						<option value="daily">Daily</option>
						<option value="weekly">Weekly</option>
						</select>
					</td>
				<td>
					<div class="ListTools">
						<a href="<?php echo $view_search_url; ?>" onclick="return CentralSpaceLoad(this,true);">&gt;&nbsp;<?php echo $lang["searchbutton"]; ?></a>
						<?php
							if($ws['owner']==$userref)
								{
								?>
								<a href="<?php echo $url; ?>&callback=checknow&ref=<?php echo $ws["ref"]; ?>"
									onclick="return CentralSpaceLoad(this,true);">&gt;&nbsp;
									<?php echo $lang["checknow"]; ?></a>
								<?php
								}
						?>
				</td>
				</tr>
				<?php
			}
			?>
		</table>
	</div>	<!-- end Listview -->

	<?php
	if (count($watched_searches) > WATCHED_SEARCHES_ITEMS_PER_PAGE)
		{
		?>
		<div class="BottomInpageNav">
			<?php pager(false) ?>
		</div>
		<?php
		}
	?>

</div> <!-- end of BasicsBox -->