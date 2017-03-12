<?php
if (!defined('PICONTROL')) exit();

// English
$langArray = array(
	'920c3931' => 'Failed to get IP address!',
	'2963f17d' => 'Unfortunately, an unexpected error has occurred. Please close the feedback window and try again. Otherwise, write me at <a href="%%s" target="_blank">contact</a>.',
	'8311b9b6' => 'Close',
	'bea4c2c8' => 'Feedback',
	'28c330db' => 'Some data must be collected for the feedback.',
	'fcb4bb2f' => 'Diagnostic data was collected. A new window will open when you click on the button below.',
	'db72d0d6' => 'Open feedback',
	'd4d2d713' => 'The interface will be restarted...',
	'808784ec' => 'The interface has been restarted.',
	'ed6aabd6' => 'An unexpected error has occurred!',
	'97be4473' => 'An error has occurred! Probably, the connection was closed.',
	'995ae7be' => 'It can be restarted only one interface at the same time.',
	'2ff2ad4e' => 'The password should be at least 8 characters.',
	'08ca4b4b' => 'Disconnects...',
	'77c09673' => 'Connects...',
	'79435d54' => 'Find IP address of connection...',
	'991b47c2' => 'Connection with "%%s" was successful.',
	'74d42e1c' => 'IP address',
	'4694c564' => 'Fault!',
	'229de263' => 'Online - You will be redirected immediately',
	'8d9da4bc' => 'Offline',
	'1710fb15' => 'No values are available. Values will be registered every %%s minutes.',
	'8b46562c' => 'An error has occurred! Error: %%s',
	'7182718z' => 'Time',
	'21d0815d' => 'An error has occurred! Error code: %%s',
	'7833f4fb' => 'Connect...',
	'c68f2cae' => 'Please enter a command!',
	'62cfb21c' => 'Connected',
	'54f664c7' => 'Online',
	'3ab1b0ef' => 'Error occurred!',
	'e9c4586f' => 'Reconnect',
	'306964c0' => 'Disconnected<br />(Log on to another window)',
	'9121f198' => 'Disconnected<br />(No permission)',
	'70b55b88' => 'Disconnected',
	'ab0cf104' => 'Cache',
	'2958e45z' => 'Activate',
	'63909f76' => 'Use',
	'2aa12a5f' => 'Size',
	'a2db4ba7' => 'Last change',
	'667a3040' => 'Cache time',
	'33dddf44' => 'Clear',
	'b97d23bf' => 'Save',
	'e76cf8a6' => 'You',
	'c0f3a410' => 'Your devices',
	'a4afc7ee' => 'Verified as ',
	'e21141e5' => 'Device name',
	'9bc8a493' => 'Create date',
	'490ba398' => 'Notification',
	'5be418d9' => 'The notifications are realised by Pushbullet. Therefore, an account at Pushbullet is necessary.',
	'aba85c65' => 'Access token',
	'19279a9e' => 'Test notification',
	'02efac7b' => 'Send now',
	'540356eb' => 'Notify me when...',
	'c3829179' => 'a new Pi Control Version appears',
	'83b6557b' => 'the CPU temperature exceeds a value',
	'4bb63b47' => 'the memory usage (total memory) exceeds a value',
	'756330b2' => 'Settings for overview',
	'9a899bca' => 'Interval overview update',
	'8472022z' => 'Seconds',
	'238c21ae' => 'Show "Connected devices"',
	'e8e6f5ed' => 'Activation causes a longer loading time of the overview.',
	'8328b196' => 'Weather',
	'd5d9a3bf' => 'Service',
	'91aa3dc3' => 'Location',
	'512ef7c6' => 'Country',
	'3c2f8b8c' => 'Germany',
	'ba9160fb' => 'Austria',
	'7a0be5a1' => 'Swiss',
	'b7709fb2' => 'United Kingdom',
	'e3a7b549' => 'Server not reachable!',
	'40acacf0' => 'Postcode',
	'c05fc5cd' => 'City',
	'e458e5b8' => 'Settings for Pi Control',
	'9c22f572' => 'Theme color',
	'6e2ee1c6' => 'Label',
	'9b22aa23' => 'Give your Pi Control a name to identify it better in notifications.',
	'ff940ad7' => 'Language',
	'8f0ca218' => 'German',
	'319be822' => 'English',
	'fc1629ac' => 'External access',
	'eab46a55' => 'Accessible outside of the local network? If necessary think of port forwarding.',
	'eb081ce8' => 'Temperature monitoring',
	'3955039z' => 'Activate this option to be notified by your Raspberry Pi, when a certain temperature is exceeded. In this case, it can be set specific behavior.',
	'15b9ca9e' => 'Paused for %s',
	'16f93748' => 'Maximum temperature',
	'e6c7a581' => 'Action',
	'bdabbcc7' => 'Send email',
	'dec44579' => 'Confirm',
	'0d225a0d' => 'Check',
	'ba515a56' => 'Verified',
	'637b84d1' => 'Shell command',
	'0ab43de2' => 'Runs as %s.',
	'ae455599' => 'Remove Pi Control',
	'4c51f524' => 'If you don\'t like the Pi Control and you want to remove it, let me know the reason below under "Feedback" please. So I can better take on possible problems and constantly improve Pi Control.',
	'7a92f000' => 'Instructions to remove',
	'a28a5e7e' => 'Settings for plugins - Delete plugin',
	'9323a797' => 'Would you really irrevocably delete the plugin <strong>%s</strong>?',
	'49c81cd0' => 'Delete',
	'3c45a1e8' => 'Settings for plugins',
	'3c972504' => 'Version %s',
	'27014dd1' => 'Settings',
	'5e3e0783' => 'Disable',
	'570e8f21' => 'Settings for statistic',
	'664006b3' => 'Hide not used statistics to improve the loading times and to keep track.',
	'f2d37233' => 'Statistics are, regardless of the display status, recorded.',
	'49ee3087' => 'Name',
	'801ab246' => 'Download',
	'9b151bfe' => 'Reset',
	'7b213e2d' => 'Settings for statistic - Reset history',
	'9d266f6d' => 'Do you really want to reset the history of %s?',
	'742937f0' => 'Troubleshooting',
	'ed7243fc' => 'The troubleshooter can help you with problems and even remove them if necessary.',
	'053b183c' => 'Files and folders',
	'e2aa67cf' => 'File',
	'45a882e7' => 'Folder',
	'de9af927' => 'Exists',
	'f913ca67' => 'Permission',
	'45b589ec' => 'User:Group',
	'5b3b6a11' => 'Guide to troubleshooting',
	'daa242c9' => 'Cron for Pi Control',
	'ae7f5a76' => 'The cron for your Pi Control is as follows:',
	'c9ccf692' => 'Mark all with double click.',
	'519f3478' => 'Registered in crontab',
	'3231457z' => 'PHP-CLI installed',
	'c89b288f' => 'Last execution',
	'e8a54e95' => 'Last entry log',
	'b846f886' => 'Fix problem',
	'34b6cd75' => 'Version',
	'50c6a2e1' => 'Email notification',
	'f8bc0f0d' => 'If you want to receive an email when a new version of the Pi Control is out, you can register via the following button in a list.',
	'e4e816c4' => 'Enter email',
	'a5b3f2c8' => 'Update',
	'2688c7f7' => 'Your Pi Control is no longer up-to-date. Version %s is available!',
	'2f0deed4' => 'What\'s new in version %s',
	'c6e641f2' => 'Your Pi Control is up-to-date.',
	'2009c832' => 'An error occurred when connecting to the server. The server sends a invalid response.',
	'659126ez' => 'An unexpected error occurred when connecting to the server. Error code: %s',
	'58d37757' => 'Install update',
	'0a9c591b' => 'Is an update available, you can download this and install over the button below.',
	'fa1ba9e2' => 'Download &amp; install update',
	'c95c08dd' => 'Settings for user',
	'cca8493f' => 'Username',
	'7c6cb747' => 'Created',
	'6d6c763b' => 'Last activity',
	'01e91d2e' => 'Never logged in',
	'5ad05dd4' => 'Edit',
	'd9901e46' => 'Add',
	'bd586bb0' => 'Users logged-in',
	'3b6f8e4f' => 'Non-fixed login users are automatically logged off after 12 hours of inactivity.',
	'f50c371f' => 'Logged in at',
	'86c735a6' => 'Logged in by',
	'08c540e1' => 'Remember me',
	'e4cc0dcc' => 'Current session',
	'375ee260' => 'Yes',
	'b397ec0b' => 'No',
	'03b246c1' => 'Logout',
	'5d83abec' => 'Settings for user - Add user',
	'3e45af4c' => 'Password',
	'1d8982a9' => 'Repeat password',
	'f081d2a0' => 'Settings for user - Delete user',
	'546baa5f' => 'Please enter the password of the user for confirmation.',
	'7be0c4a0' => 'Settings for user - Edit user',
	'd5a1b623' => 'Old password',
	'c8a18c65' => 'New password',
	'7d93c125' => 'Repeat new password',
	'0d94a8c5' => 'Detailed overview',
	'0a6892b9' => 'General',
	'622fcb1e' => 'Raspberry Pi Model',
	'b86aa88e' => 'Manufacturer',
	'32c676ac' => 'Revision',
	'1f4e5730' => 'PCB Revision',
	'e65a75be' => 'Serial number',
	'c32566d1' => 'Current time',
	'9e6a07bb' => 'Current timezone',
	'77f0ba28' => 'Run time',
	'6d202d5f' => 'Last start',
	'719d067b' => 'Software',
	'f0bac093' => 'Distribution',
	'6ff9f444' => 'Kernel',
	'a45da96d' => 'System',
	'66bd721f' => 'Running tasks',
	'69032d57' => 'Installed packages',
	'2e6ed7fa' => 'List packages',
	'7417eeb4' => 'Webserver',
	'e69c69c6' => 'HTTP-Server',
	'7bde7505' => 'PHP-Version',
	'b15b7b03' => 'User',
	'3b80444f' => 'Processor',
	'beea054d' => 'Clock',
	'4db9161e' => 'Maximum clock',
	'2002d516' => 'Total load',
	'6016245z' => 'Load %s',
	'c2ea8433' => 'Type',
	'd490a3ea' => 'Model',
	'898901aa' => 'Temperature',
	'b71b74e6' => 'Main memory',
	'927cee1b' => 'Total',
	'd4bbf105' => 'Load',
	'52404cb6' => 'Total memory',
	'3349300z' => 'Partition',
	'0d18c373' => 'Mountpoint',
	'74533df5' => 'Used',
	'6956635z' => 'Free',
	'2722ef4e' => 'All user',
	'34f43597' => 'User-ID',
	'8ddf190a' => 'Group-ID',
	'60aaf44d' => 'Port',
	'197bf841' => 'Last login',
	'ac9bebfc' => 'By',
	'31493eab' => 'Logged in',
	'339ed42d' => 'At %s on %s at %s from %s',
	'9ba4b588' => 'Statistic',
	'bb38096a' => 'Plugins',
	'71f221d8' => 'Count',
	'aef86156' => 'Total',
	'7e0cea5f' => 'Installed',
	'0693306z' => 'Update',
	'e729d5b1' => 'Disabled',
	'cc28ac9e' => 'Discover plugins',
	'47ab20bc' => 'There are currently no plugins available.',
	'6c6655f3' => 'Install',
	'15ad4b1c' => 'Go to plugin',
	'bf3cfcc6' => 'Update',
	'7710266e' => 'The plugin is out of date. Version %s is available!',
	'cda4c3b2' => 'Published at',
	'17eeee92' => 'Last update',
	'35bedb45' => 'Description',
	'bf6c5608' => 'Languages',
	'7306fb5c' => 'Manual',
	'c2efebc7' => 'Requirements',
	'743966b9' => 'Changes with version %s',
	'46fa564b' => 'Screenshots',
	'9c0864a5' => 'ABOUT ME',
	'021321ez' => 'VERSION',
	'57d0632a' => 'Help',
	'b6d49679' => 'Open at Play Store',
	'9a4883bb' => 'App in Play Store',
	'62db69f3' => 'My blog',
	'2491bc9c' => 'Twitter',
	'd3b7c913' => 'GitHub',
	'f41ea892' => 'Donate',
	'922d7e28' => 'LICENSE',
	'1034eec6' => 'Raspberry Pi is a trademark<br />of the %s.',
	'28ba4993' => 'With %s developed by %s.',
	'1cb3236e' => 'Logged in as %s',
	'417b5de9' => 'Overview',
	'a983288c' => 'Network',
	'514d8a49' => 'Terminal',
	'3cee9f12' => 'Unsupported browser version',
	'e23dd547' => 'Your current browser version is not supported by Pi Control. Please update your browser or use another!',
	'96ae48ee' => 'Pi Control %s is available',
	'fa13b1ea' => 'Go to <a href="%s">update</a> to view and start it.',
	'c3049ac9' => 'Error with the cron',
	'38a39b09' => 'Apparently, there\'s an issue with the cron for the Pi Control. It was no longer running for more than 2 minutes. If this message still appears in about 5 minutes, perform <a href="%s">troubleshooting</a>.',
	'567df44c' => 'Number of installed packages: %s',
	'f90b8f32' => 'Package name',
	'99dea780' => 'Login',
	'694c5fec' => 'Cookies must be enabled for this feature.',
	'ecff6ecb' => 'Log in',
	'2255f45c' => 'Raspberry Pi is a trademark of the Raspberry Pi Foundation.',
	'7041838z' => 'Configuration',
	'e7935ae6' => 'Traffic',
	'3c1aac82' => 'Interface',
	'fa02f30e' => 'Sent',
	'0c78bbf3' => 'Received',
	'c8f4b8c4' => 'Hostname',
	'317907a0' => 'Your Raspberry Pi will be displayed at network under the following name: <strong>%s</strong>',
	'279f3416' => 'Change',
	'a12a3079' => 'IP',
	'eb9de48f' => 'MAC-Address',
	'17b6fa0e' => 'Not connected',
	'29f0e281' => 'Network name',
	'46393c49' => 'Security',
	'84eb8685' => 'Channel',
	'394a653c' => 'No WiFi networks found. <a href="%s">Scan.</a>',
	'ec53a8c4' => 'Status',
	'8385a5ce' => 'Network configuration',
	'd1fd5dc4' => 'Protocol',
	'41aa696a' => 'Method',
	'28bba8c5' => 'Network configuration - Add interface',
	'b3370824' => 'Please make more settings for IPX/SPX yourself',
	'54201c55' => 'DHCP',
	'292176d4' => 'Static',
	'52a4c908' => 'Manual',
	'50d46c2f' => 'Address',
	'e701001d' => 'Netmask',
	'926dec94' => 'Gateway',
	'bb2fa70e' => 'Network configuration - Delete interface',
	'ff4b5d6b' => 'Would you really irrevocably delete the interface <strong>%s</strong>?',
	'a026a765' => 'Network configuration - Edit interface',
	'f93acacf' => 'Please activate JavaScript to be able to connect to a WLAN network.',
	'f6771532' => 'Connect to WLAN network',
	'55ad9ac8' => 'Password (if needed)',
	'b70ad43d' => 'Connect',
	'ec4482f9' => 'Connecting to "%s"...',
	'66c09a2c' => 'Connection with "%s" was not successful! <a href="%s">Try again.</a>',
	'3bb82d0b' => 'If you change the hostname, the new name is visible only after a reboot.',
	'4ffd5e49' => 'Do you really want restart your Raspberry Pi?',
	'bad5b81c' => 'Restart',
	'4da1bb14' => 'Do you really want shutdown your Raspberry Pi?',
	'4eac40b9' => 'Shutdown',
	'4ce188e1' => 'The current postal code is invalid.',
	'6500e84c' => 'Weather has not been configured. <a href="%s">To the settings.</a>',
	'c5e7fef5' => 'The weather can not be accessed currently.',
	'913a8b4e' => 'Wind force | Humidity',
	'b6f55f38' => 'Start time',
	'742c5587' => 'CPU-Clock',
	'a35c5056' => 'CPU-Load',
	'719e599e' => 'CPU-Temperature',
	'e53619c1' => 'RAM',
	'5582e12z' => 'Memory used',
	'785c3f86' => 'Memory free',
	'4f02abcd' => 'Show more',
	'0ffcd1dc' => 'Connected devices',
	'84186a7b' => 'There are currently no plugins installed.',
	'5a457123' => 'Raspberry Pi is restarting',
	'1a319d8b' => 'As soon as your Raspberry Pi is available again, you\'ll automatically redirected to the overview.<br />Should you not be redirected, you come <a href="%s">here back to the overview.</a><br /><br />',
	'e713f04a' => 'Current status: <strong class="green">Online</strong>',
	'6734713f' => 'Raspberry Pi shuts down',
	'6463ed80' => 'As soon as your Raspberry Pi is available again, you come <a href="%s">here back to the overview.</a>',
	'280d3e18' => 'SSH-Login',
	'ad16b2ee' => 'You are not logged in. Thereby you cannot use some functions.',
	'f2fba58e' => 'Log in with a password',
	'8617afc3' => 'Click to activate',
	'3139471z' => 'SSH-Port',
	'6fc3213d' => 'Standard: %d',
	'6673e2ed' => 'SSH-Username',
	'1335e66z' => 'SSH-Password',
	'ca3b9b74' => 'SSH-Login saving?',
	'eb6694f7' => 'Saves the password to not have to be re-log in after each session.',
	'30aefd8a' => 'OR',
	'0118609z' => 'Log in with a publickey',
	'40431ebc' => 'Standard: 22',
	'dbf970b1' => 'SSH-Privatekey',
	'f8805681' => 'Privatekey-Password (if required)',
	'6583ab9a' => 'You are already logged in with the user %s.',
	'3067c491' => 'Please enable JavaScript to view the statistics.',
	'87f4a935' => 'Period of time',
	'71c3e827' => 'All (7 days)',
	'4c0e0cf7' => 'Last 6 days',
	'6c8a29be' => 'Last 5 days',
	'059b6bde' => 'Last 4 days',
	'e52b6b76' => 'Last 3 days',
	'9e134b29' => 'Last 2 days',
	'8591edd8' => 'Last 24 hours',
	'1dfceccb' => 'All statistics are hidden!',
	'9a1195e0' => 'There are still no statistics available. Values are entered every 5 minutes.',
	'e65569e7' => 'Load...',
	'88e7e4ab' => 'Terminal %d (%s)',
	'3a12ad89' => 'Disconnect',
	'c737e167' => 'The Terminal offers you the ability to perform simple commands directly in the Pi Control.',
	'8b622980' => 'Command: ',
	'10ec760e' => 'Send',
	'21f5916a' => 'Cache cleared',
	'35fa6d1c' => 'The cache has been successfully cleared.',
	'52e5304c' => 'Fault',
	'786d9da7' => 'Unfortunately, the cache could not be cleared!',
	'fbf49a53' => 'Please assign a valid number between 1 and 9999 for the storage time.',
	'62c128cf' => 'Settings saved',
	'8e2b9292' => 'The settings have been successfully saved.',
	'ca4a2339' => 'Unfortunately, the specified access token is not valid!',
	'3cd46c39' => 'Unfortunately, the specified temperature is invalid!',
	'55e6ff09' => 'Unfortunately, the specified percentage is invalid!',
	'daf2902f' => 'Enable notification',
	'3c93ba20' => 'The notification was enabled.',
	'3bf35e85' => 'Could not activate the notification!',
	'dfcedc9d' => 'Disable notification',
	'bed43f6f' => 'The notification was disabled.',
	'c24fbff7' => 'Could not disable the notification!',
	'191b8d9c' => 'Token removed',
	'206a2719' => 'The token was successfully removed.',
	'021ce11e' => 'Please fill out all required fields and choose one or more of the actions!',
	'6ba29415' => 'Your Pi Control "%s" has sent you a test notification.',
	'4730e82z' => 'Connection error',
	'7d72dd71' => 'An unexpected error occurred when connecting to Pushbullet. Error code: %d',
	'a5b12f8c' => 'Pushbullet reports an error with a request: %s',
	'5c234b18' => 'Please assign a valid value between 1 and 9999 for the interval.',
	'ec6c4274' => 'Unfortunately, the specified API key is too short!',
	'5f30639f' => 'Unfortunately, the specified postcode is invalid!',
	'433e876z' => 'Unfortunately, the town name is invalid!',
	'5805e7ce' => 'Please fill out all required fields!',
	'9c7509af' => 'The settings have been successfully saved.<br /><br />Tip: To make effective the theme change, empty your browser cache with Ctrl+ F5 (Windows) / &#8997;&#8984; + E (OS X / Safari) / &#8679;&#8984; + R (OS X / Chrome)',
	'25641a9a' => 'Unfortunately, the label is invalid! The name must consist of 2 to 32 characters. The first character must be a letter, and only the following characters are allowed: A-Z 0-9 _ - + / . ( ) [ ] "Spaces"',
	'c89cee57' => 'Please assign a label for your Pi Control!',
	'4068485z' => 'Please enter a valid email address.',
	'8397f10d' => 'Please enter a valid shell command.',
	'7a80a6b0' => 'Please choose at least one action.',
	'41cfb898' => 'Temperature monitoring enabled',
	'3ac6f708' => 'The temperature monitoring has been enabled.',
	'885f7934' => 'Could not activate the temperature monitoring!',
	'54ffcc1a' => 'Temperature monitoring disabled',
	'd3618a3d' => 'The temperature monitoring has been disabled.',
	'7ba545da' => 'Could not disable the temperature monitoring!',
	'8421b3f6' => 'Unfortunately, an error has occurred. Please repeat the assignment of the name and the email address.',
	'e8113527' => 'An unexpected error occurred when connecting to the server. Error code: %d (%s)',
	'9e539a2e' => 'Unfortunately, no connection to the server was able to establish because it is at the moment probably unreachable. Error code: %d',
	'e746cbb2' => 'An unexpected error occurred when connecting to the server. Error code: %d',
	'5c9ed40b' => 'Server error',
	'fc95e503' => 'An error occurred when connecting to the server. The server sends an empty response.',
	'315f97dd' => 'Processing error',
	'a71c8512' => 'Email sent',
	'5244a7b2' => 'An email with a confirmation link has been sent to <strong>%s</strong>. In the email is a link that must be clicked or opened. Should the email not be arrived after 10 minutes, look in your spam folder. Otherwise repeat the process.',
	'04d0ced9' => 'Plugin deleted',
	'73d87db2' => 'The plugin "%s" was successfully deleted.',
	'ba28d467' => 'The plugin "%s" could not be deleted.',
	'afa753b4' => 'Plugin enabled',
	'11d79a74' => 'The plugin "%s" has been successfully enabled.',
	'75b0b99e' => 'The plugin "%s" could not be enabled.',
	'8e9b5fa6' => 'Plugin disabled',
	'a56a6e08' => 'The plugin "%s" has been successfully disabled.',
	'4a2e71b8' => 'The plugin "%s" could not be disabled.',
	'953fbe20' => 'Could value not save in configuration file!',
	'e815ef70' => 'The history could not be found: %s',
	'58876dbf' => 'History reseted',
	'707a0c9b' => 'History has been reset successfully.',
	'b11d776d' => 'History could not be reset.',
	'4c30bad9' => 'Pi Control updated to version %s',
	'ef0db072' => 'Your Pi Control was successfully updated and is now ready for use. Should problems occur, click below on "Feedback" and write me. Have fun!<br /><br />Tip: Clear your browser cache with Ctrl + F5 (Windows) / &#8997;&#8984; + E (OS X / Safari) / &#8679;&#8984; + R (OS X / Chrome)',
	'9ec860ec' => 'Update blocked',
	'e203e667' => 'It was found at least one error with the files or folders of the Pi Control. Please fix the problem using the <a href="%s">troubleshooting</a> first, otherwise an update is not possible.',
	'21caa2f6' => 'User created',
	'8c5d3ec0' => 'The user "%s" was successfully created.',
	'11379b49' => 'The specified passwords do not match!',
	'c2a659cc' => 'Unfortunately, the password is invalid! The password must consist of 4 to 64 characters and must contain only the following characters: A-Z 0-9 - _ + * / # .',
	'8f37b0f8' => 'Unfortunately, the username is already taken! Please choose another.',
	'fceff235' => 'Unfortunately, the username is invalid! The username must consist of 2 to 32 characters. The first character must be a letter, and only the following characters are allowed: A-Z 0-9 - _',
	'23341f35' => 'Please fill all fields.',
	'73bd321c' => 'User deleted',
	'426ef24b' => 'The user was successfully deleted!',
	'05525c21' => 'The password is not correct!',
	'6ef228fa' => 'Unfortunately, the user does not exist!',
	'7264b91f' => 'User edited',
	'832e48a1' => 'The user "%s" was successfully edited and saved.',
	'73377bad' => 'The new password does not match the repetition!',
	'a3e990a1' => 'The old password is not correct!',
	'78f14302' => 'User logged out',
	'c0e2703c' => 'The user has been logged out successfully.',
	'92fcd39f' => 'No plugins!',
	'4b68e7b6' => 'Pluginfolder not found!',
	'fc877665' => 'Access is only available in the local area network (LAN)!',
	'7c4bd417' => 'Login blocked for %d seconds!',
	'6ae0a563' => 'Login failed!<br />Login blocked for %d seconds!',
	'266342aa' => 'Login failed!<br />Too many failed attempts. Login blocked for %d seconds!',
	'491b8c75' => 'Login failed!<br />Too many failed attempts. Login blocked for %d minute!',
	'df7545f1' => 'Login failed!<br />Too many failed attempts. Login blocked for %d minutes!',
	'dbee4e1e' => 'Error during login!',
	'101b60f8' => 'Hostname saved',
	'91c90c67' => 'In order for the change to take effect, your Raspberry Pi needs to be restarted. <a href="%s">Now restart.</a>',
	'50f84dd2' => 'Failed to change the hostname! Error code: %s',
	'5a7d26a2' => 'The hostname is invalid! It must consist of at least 1 to 24 characters and may contain only the following characters: A-Z a-z 0-9 -<br />The hostname cannot start or end with a dash.',
	'9bd19732' => 'Interface added',
	'cb892b7e' => 'Interface has been added successfully. The interface must be restarted in order for these settings to be effective however.',
	'63f897f0' => 'Interface deleted',
	'3cb08490' => 'Interface was successfully deleted.',
	'5f361165' => 'Interface not available',
	'b33f8f3b' => 'No interface with the specified name was found.',
	'e82d2f60' => 'Unfortunately, the interface could not be saved. The name for this interface is already in use.',
	'2d5b67f7' => 'Unfortunately, the interface could not be saved. An error occurred during transmission.',
	'89cf06f3' => 'Unfortunately, the configuration file has been modified in the meantime, try therefore again.',
	'4ca9aa46' => 'Please assign an interfacename, a protocol and a method!',
	'492f4319' => 'Unfortunately, the interface could not be deleted. An error occurred during transmission.',
	'23950fb1' => 'Interface saved',
	'ac3dbb12' => 'Interface has been saved successfully. The interface must be restarted in order for these settings to be effective however.',
	'd51fd6f1' => 'Failed to load the plugins',
	'2eaf52d4' => 'The selected plugin supports at the moment no settings.',
	'1a8204f0' => 'Plugin is incompatible',
	'9ebb114d' => 'Currently, the plugin you\'re looking for cannot be opened because it is not compatible. Please update your Pi Control to continue to use the plugin.',
	'8ac6b33b' => 'Plugin is disabled',
	'73ac414b' => 'Currently, the plugin you\'re looking for cannot be opened because it is disabled.',
	'df67d56c' => 'Plugin not found',
	'ded55eda' => 'Currently the plugin you\'re looking for can not be found or it does not exist.',
	'14fca6f1' => 'Restart not possible',
	'8c73b4a2' => 'Shutdown not possible',
	'f9f52265' => 'Connected',
	'965122d5' => 'Connection to Raspberry Pi was prepared.',
	'd3f7826c' => 'Connection to Raspberry Pi was unsuccessful!<br /><br />Please check your entered dates. Failed a second try to log in with correct dates please contect me under "Feedback" at the bottom. I will help you as soon as possible.',
	'88a298d6' => 'Error while saving the dates!',
	'a4f68fac' => 'Invalid port. The port must to be between 0 and 65535.',
	'3e0b52a8' => 'Successfully logged out.',
	'3608adcd' => 'When log off an error has occurred!',
	'122d6738' => 'Cannot read SSH-Information.',
	'059fd062' => 'Terminal stopped',
	'484bd720' => 'The terminal has been stopped successfully.',
	'4ead1058' => 'Update available',
	'424332a2' => 'Pi Control version %s is now available for download for your Pi Control "%s".',
	'6aaa0133' => 'Temperature exceeded',
	'e9e4c44a' => 'Your Pi Control "%s" reports an increased temperature of the CPU by %s °C.',
	'bcd5b6b5' => 'Memory used',
	'ce102228' => 'Your Pi Control "%s" reports a memory consumption of %d%%.',
	'f5e531e3' => 'Status',
	'064bede9' => 'Cached',
	'35c9c1df' => 'Back',
	'fdb69343' => 'Click for help',
	'f9a26283' => 'Unknown',
	'c63bc415' => 'File "%s" does not exists or is not a valid file.',
	'55f91856' => 'Could not open and read file "%s".',
	'72ee0a08' => 'Redirection',
	'c12b82b7' => 'Header already sent. Redirect not available. Please click on <a href="%s">this link</a>.',
	'19aa065f' => 'SSH-Authentification error',
	'3fa4345d' => 'No SSH-Access, please log in! <a href="%s">Log in now.</a>',
	'f3b2bd21' => 'Email confirmed',
	'f67d50cd' => 'Your email has been successfully confirmed.',
	'f2024246' => 'The server could not find related information. Assure that you have confirmed the email.',
	'54ddb475' => 'Could not delete log file: %s',
	'4ef54a08' => 'Could not open log file: %s',
	'd874be69' => 'Unfortunately, an error is occurred when unzip the update! Error code: %s',
	'a6c5267f' => 'The update was not completely downloaded. Please try again. Should the error persist, write me at <a href="%s" target="_blank">contact</a>, so that I can help you as quickly as possible.',
	'8faea4b1' => 'Could not cache the update! Please write me at  <a href="%s" target="_blank">contact</a> so that I can help you as quickly on.',
	'a03e97c2' => 'Couldn\'t find the update on the server! Please write me at  <a href="%s" target="_blank">contact</a> so that I can help you as quickly on.',
	'dbfe2368' => 'Unfortunately, an error has occurred during the update: %s<br />Please write me at  <a href="%s" target="_blank">contact</a> so that I can help you as quickly on.',
	'c0b73d75' => 'Return to the update',
	'4dd11823' => 'Access error',
	'f35106fb' => 'The expected page is not available.',
	'b1722ba6' => 'Unfortunately, an error has occurred in the structure of the page.<br /><br />%s',
	'7e28887b' => 'Please report the problem <a href="%s" target="_blank">here</a>.',
	'359cabda' => 'Dynamic',
	'802238f7' => 'There has been an error! Error code: %s',
	'c1d48b16' => 'Filename "%s" is invalid.',
	'9ba51734' => 'Date',
	'5a9ed090' => 'Temperature in degrees Celsius',
	'e056e124' => 'Load in percent',
	'25a22b12' => 'Sent in bytes',
	'a2c9a77d' => 'Receiving in bytes',
	'01ac370c' => 'Degrees Celsius',
	'4b4f4d53' => 'Load %%',
	'5185a813' => 'Traffic (MB)',
	'15a8022d' => 'UPDATE',
	'4eb57703' => 'An error has occurred! Error: %s',
	'431e27fd' => 'Plugin installation',
	'481c6ece' => 'Back to plugin',
	'ca7290c4' => 'Unfortunately, an error is occurred when unzip the plugin! Error code: %s',
	'daa48153' => 'The folder for the plugin could not be created. Check folder permissions and try again. Should the error persist, write me at <a href="%s" target="_blank">contact</a>, so that I can help you as quickly as possible.',
	'38ce1a5f' => 'The folder for the plugin already exists. Please delete the folder.',
	'a62b406c' => 'The plugin was not completely downloaded. Please try again. Should the error persist, write me at <a href="%s" target="_blank">contact</a>, so that I can help you as quickly as possible.',
	'c54273b0' => 'Could the plugin not cache! Please write me at  <a href="%s" target="_blank">contact</a> so that I can help you as quickly on.',
	'8408cdf7' => 'Couldn\'t find the plugin on the server! Please write me at  <a href="%s" target="_blank">contact</a> so that I can help you as quickly on.',
	'15ae31f8' => 'Couldn\'t find the PluginID on the server! Please write me at  <a href="%s" target="_blank">contact</a> so that I can help you as quickly on.',
	'97c5ea2f' => 'Unfortunately, an error has occurred while retrieving the plugins. Error code: %s<br />Please write me at <a href="%s" target="_blank">contact</a> so that I can help you as quickly on.',
	'cd9ff94e' => 'The folder for the plugin does not exist. Please install the plugin first.',
	'de3e456f' => 'Total in byte',
	'8e19b4db' => 'Used in byte',
	'4ad0e6c0' => 'Usage in MHz',
	'532d516e' => 'Error while retrieving',
	'26c88659' => 'Unfortunately, an error has occurred while retrieving the plugin. Error code: %s',
	'01c5cdaa' => 'The plugin is already installed.',
	'9d34e0e2' => 'The plugin you are looking for could not be found.',
	'eef7f68b' => 'Character encoding',
	'cdb36567' => 'RAM-Load',
	'50ba55ca' => 'Terminal not available',
	'ca9e3c3f' => 'The terminal cannot be used under HTTPS at the moment.',
	'fc1cafe2' => 'Unfortunately, the specified coordinates are invalid!',
	'71032b12' => 'Without API key',
	'fe4b1dff' => 'With API key',
	'fb79710d' => 'To the provider',
	'a520cdd1' => 'Coordinates',
	'9440e2eb' => 'Latitude',
	'e045e08f' => 'Longitude'
);
?>