<?php if (!defined('PICONTROL')) exit(); ?>
<script type="text/javascript">var languageArray = <?php echo $data['languageArray']; ?></script>
<script type="text/javascript" src="public_html/js/install.language.js"></script>
<style>
@-webkit-keyframes move {
    0% {
       width: 0%;
    }
    30% {
       width: 20%;
    }
}
</style>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Fortschritt'); ?></span>
		</div>
		<div class="inner">
			<div class="progressbar"><div style="width: 20%; -webkit-animation: move 1.5s linear forwards;">&nbsp;</div></div>
		</div>
		<div class="inner text-center">
			<strong>20%</strong>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('1. Schritt'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Bitte w&auml;hle nachfolgend aus den vorhandenen Sprachen, deine bevorzugte Sprache aus. Die Sprache kann nachtr&auml;glich ge&auml;ndert werden.'); ?>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Sprachauswahl'); ?></span>
		</div>
		<form action="?s=install_language" method="post">
			<div class="inner install-language-flex-container">
				<input type="radio" name="language" value="de" id="rb-de"<?php if ($data['language'] == 'de') echo ' checked="checked"'; ?> />
				<label for="rb-de">Deutsch</label>
				<input type="radio" name="language" value="en" id="rb-en"<?php if ($data['language'] == 'en') echo ' checked="checked"'; ?> />
				<label for="rb-en">English</label>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('N&auml;chster Schritt'); ?>" />
			</div>
		</form>
	</div>
</div>