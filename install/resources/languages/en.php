<?php
if (!defined('PICONTROL')) exit();

// English
$langArray = array(
	'c36ee745' => 'Introduction',
	'7cd8fb6e' => 'Installation',
	'22cbd69b' => 'Welcome to the installation of Pi Control for your Raspberry Pi!<br /><br />Thank you that you have decided for Pi Control.<br />The installation guides you through a series of steps and takes about three minutes. Right you will find a progress bar that informs you about the status of the installation.<br /><br />Let\'s go. Click on the button below.',
	'3310b21b' => 'Start installation',
	'adcbc9a8' => 'Progress',
	'cd3e7503' => 'Second step',
	'fb888faf' => 'In order to get the best from Pi Control, certain requirements must be met. Below you will find a list of these.',
	'69352ae8' => 'Requirements',
	'2fec3923' => 'PHP',
	'3c972504' => 'Version %s',
	'106a779f' => 'SSH2-Extension installed',
	'8c985076' => 'Mcrypt-Extension installed',
	'6d87a3c2' => 'CLI-Extension installed',
	'580d8a82' => 'cURL-Extension installed',
	'2b884626' => 'ZipArchive-Extension installed',
	'652fe7cf' => 'Loading external content enabled',
	'053b183c' => 'Files and folders',
	'3a84f77b' => 'Available',
	'ed74d5f1' => 'Permissions',
	'cda74ec7' => 'At least %d errors',
	'f54fb898' => 'Next step',
	'f82f481b' => 'Resolve problems to continue!',
	'08f613b2' => 'Refresh page',
	'bf2129ae' => 'Other',
	'f0bac093' => 'Distribution',
	'eab6f8ea' => 'Cookies enabled',
	'b15b7b03' => 'User',
	'a9191629' => 'User already created',
	'95a05cd9' => 'It was already created a user for Pi Control. You can <a href="%s">skip this step</a> or just overwrite the current user, by creating a new user here.',
	'60c06a93' => 'There was an error during a file operation! Error code: %s',
	'11379b49' => 'The specified passwords do not match!',
	'c2a659cc' => 'Unfortunately, the password is invalid! The password must consist of 4 to 64 characters and must contain only the following characters: A-Z 0-9 - _ + * / #.',
	'fceff235' => 'Unfortunately, the username is invalid! The username must consist of 2 to 32 characters. The first character must be a letter, and only the following characters are allowed: A-Z 0-9 - _',
	'0f9a3027' => 'Please fill out all fields!',
	'd4c21508' => 'At this step you can create a user for Pi Control. This user has nothing to do with the SSH login and will be used only to log on to Pi Control.',
	'4742673z' => 'Create user',
	'cca8493f' => 'Username',
	'3e45af4c' => 'Password',
	'1d8982a9' => 'Repeat password',
	'7eefd2b2' => 'First step',
	'c871d098' => 'Third step',
	'c75a5ab4' => 'Finish',
	'9da86aaf' => 'Fourth step',
	'bbe35208' => 'Yay, now you\'re done with the installation of Pi Control. At the last step, everything will ready configured and created. Then you\'ll redirected to the Pi Control.<br /><br />Have fun!',
	'bea4c2c8' => 'Feedback',
	'9a4883bb' => 'App in Play Store',
	'64ed4ee0' => 'Open at Play Store',
	'57d0632a' => 'Help',
	'9c0864a5' => 'ABOUT ME',
	'62db69f3' => 'My blog',
	'd3b7c913' => 'GitHub',
	'2491bc9c' => 'Twitter',
	'f41ea892' => 'Donate',
	'021321ez' => 'VERSION',
	'922d7e28' => 'LICENSE',
	'1034eec6' => 'Raspberry Pi is a trademark<br />of the %s.',
	'28ba4993' => 'With %s developed by %s.',
	'c8416a15' => 'Language selection',
	'183b673b' => 'Please choose your preferred language below from the existing languages. The language can be changed afterwards.',
	'3cee9f12' => 'Unsupported browser version',
	'e23dd547' => 'Your current browser version is not supported by Pi Control. Please update your browser or use another!',
	'96ae48ee' => 'Pi Control %s is available',
	'32e1106z' => 'There is an update available. Please update to the latest version before you start the installation: <a href="%s" target="_blank">Download</a>',
	'742937f0' => 'Troubleshooting',
	'de9af927' => 'Exists',
	'f913ca67' => 'Permission',
	'45b589ec' => 'User:Group',
	'2aa12a5f' => 'Size',
	'5b3b6a11' => 'Guide to troubleshooting',
	'be5215cb' => 'File / Folder',
	'a4f68fac' => 'Invalid port. The port must be between 0 and 65535.',
	'd3f7826c' => 'Connection to the Raspberry Pi was not successful.<br /><br />Please check the entered data. If a new attempt with correct data fail, sent me under "Feedback" a message please, I\'ll help you as soon as possible.',
	'c35b8744' => 'A cron is required to automate the Pi Control and regularly perform tasks in the background. Below, this can be created automatically or but you <a href="%s">skip this step</a> and manually put the cron later.',
	'280d3e18' => 'SSH-Login',
	'1777295z' => 'Your data are not stored for this function and required only once to entering a cron in /etc/crontab.',
	'f2fba58e' => 'Log in using a password',
	'8617afc3' => 'Click to activate',
	'3139471z' => 'SSH-Port',
	'6673e2ed' => 'SSH-Username',
	'1335e66z' => 'SSH-Password',
	'6fc3213d' => 'Standard: %d',
	'30aefd8a' => 'OR',
	'0118609z' => 'Log in using a publickey',
	'dbf970b1' => 'SSH-Privatekey',
	'f20c15b5' => 'SSH-Password (if required)',
	'd34631dd' => 'Log in and create cron',
	'cf3fe581' => 'Fifth step',
	'4ec7b938' => 'Update notification',
	'be950317' => 'If you want to receive an email when a new version of the Pi Control is out, then you can register <a href="%s" target="_blank">here in the list</a>.',
	'81d78b94' => 'Unfortunately, the installation could not successfully be completed! Please delete the folder "%s" or rename it. When it\'s done, you come <a href="%s">here to the Pi Control</a>.',
	'80516c10' => 'Unfortunately an error has occurred while reading the Pi Control user. Please repeat the installation.',
	'ab72181c' => 'The user for the Pi Control was created successfully.',
	'3797658z' => 'The cron for the Pi Control was created successfully.',
	'ccd2cb88' => 'Unfortunately an error has occurred while reading the Pi Control user! Please repeat the installation.',
	'744e8e56' => 'Couldn\'t find the selected language! Please try again.',
	'52e5304c' => 'Fault',
	'352619fd' => 'Currently known restrictions',
	'2cbfa862' => '- The terminal works only under HTTP, not HTTPS.<br />- Still no Android app. Will be also submitted.',
	'2963f17d' => 'Unfortunately, an unexpected error has occurred. Please close the feedback window and try again. Otherwise, write me at <a href="%%s" target="_blank">contact</a>.',
	'8311b9b6' => 'Close',
	'28c330db' => 'Some data must be collected for the feedback.',
	'fcb4bb2f' => 'Diagnostic data was collected. A new window will open when you click on the button below.',
	'db72d0d6' => 'Open feedback'
);
?>