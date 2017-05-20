<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Benutzer/Gruppen'); ?></span>
			<?php if ($data['users_cache_hint'] != NULL) echo $data['users_cache_hint']; ?>
		</div>
		<div class="inner-table overflow-auto">
			<table class="table table-borderless">
				<tr>
					<th style="width: 30%;"><?php _e('Benutzername'); ?></th>
					<th style="width: 15%;" class="table-center"><?php _e('Benutzer-ID'); ?></th>
					<th style="width: 15%;" class="table-center"><?php _e('Gruppen-ID'); ?></th>
					<th style="width: 8%;" class="table-center"><?php _e('Port'); ?></th>
					<th style="width: 17%;" class="table-center"><?php _e('Letzte Anmeldung'); ?></th>
					<th style="width: 15%;" class="table-center"><?php _e('Von'); ?></th>
				</tr>
				<?php foreach ($data['all_users'] as $value) { ?>
					<tr>
						<td><?php if ($value['isLoggedIn'] === true) { ?><strong class="green">[<?php _e('Angemeldet'); ?>] </strong><?php } echo $value['username']; if ($value['isLoggedIn'] === true) { ?><div style="color: #666666; margin-top: 3px; margin-left: 20px;"><?php foreach ($value['loggedIn'] as $value2) { _e('An %s am %s um %s von %s', $value2['port'], formatTime($value2['lastLogin'], 'd.m.Y'), formatTime($value2['lastLogin'], 'H:i'), '<a href="http://'.$value2['lastLoginAddress'].'" target="_blank">'.$value2['lastLoginAddress'].'</a>'); ?><br /><?php } ?></div><?php } ?></td>
						<td valign="top" class="table-center"><?php echo $value['userId']; ?></td>
						<td valign="top" class="table-center"><?php echo $value['groupId']; ?></td>
						<td valign="top" class="table-center"><?php echo ($value['port'] == '') ? '-' : $value['port']; ?></td>
						<td valign="top" class="table-center"><?php if ($value['lastLogin'] == 0) { _e('Noch nie angemeldet'); } else { echo formatTime($value['lastLogin']); } ?></td>
						<td valign="top" class="table-center"><?php if ($value['lastLoginAddress'] == '') { echo '-'; } else { echo '<a href="http://'.$value['lastLoginAddress'].'" target="_blank">'.$value['lastLoginAddress'].'</a>'; } ?></td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>