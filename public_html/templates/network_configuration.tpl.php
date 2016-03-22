<?php if (!defined('PICONTROL')) exit(); ?>
<script type="text/javascript" src="public_html/js/network_configuration.interface_refresh.js"></script>
<!-- Sidebar -->
<div class="sidebar">
	<div class="box">
		<div class="inner-navi">
			<a href="?s=network">&Uuml;bersicht</a>
			<a href="?s=network_configuration">Konfiguration</a>
		</div>
	</div>
	<div class="box dummy-1 display-none">
		<div class="inner-header">
			<span>Status</span>
		</div>
		<div class="inner"></div>
	</div>
</div>
<!-- Container -->
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span>Netzwerkkonfiguration</span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 45%;">Interface</th>
					<th style="width: 20%;">Protokoll</th>
					<th style="width: 20%;">Methode</th>
					<th style="width: 15%;"></th>
				</tr>
<?php foreach ($data['interfaces'] as $interface => $value) { ?>
				<tr>
					<td><?php echo $interface; ?></td>
					<td><?php echo formatInterfaceProtocol($value['protocol']); ?></td>
					<td><?php echo formatInterfaceMethod($value['method']); ?></td>
					<td class="table-center"><a href="?s=network_configuration&amp;edit=<?php echo urlencode($interface); ?>" style="margin-right: 8px;"><span class="svg-control-pen display-inline-block"></span></a><a href="?s=network_configuration&amp;delete=<?php echo urlencode($interface); ?>" style="margin-right: 8px;"><span class="svg-control-cross display-inline-block"></span></a><a href="#refresh" name="<?php echo urlencode($interface); ?>"><span class="svg-refresh display-inline-block"></span></a></td>
				</tr>
<?php } ?>
			</table>
		</div>
		<div class="inner-end">
			<a href="?s=network_configuration&amp;add"><button>Hinzuf&uuml;gen</button></a>
		</div>
	</div>
</div>