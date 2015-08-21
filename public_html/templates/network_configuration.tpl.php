<script type="text/javascript"><?php echo $data['js_variables']; ?></script>
<script type="text/javascript" src="public_html/js/network_interface_refresh.js"></script>
<!-- Sidebar -->
<div class="sidebar">
	<div class="box">
		<div class="inner-navi">
			<a href="?s=network">Übersicht</a>
			<a href="?s=network_configuration">Konfiguration</a>
		</div>
	</div>
	<div class="box network_status" style="display: none;">
		<div class="inner-header">
			<span>Status</span>
		</div>
		<div class="inner">
		
		</div>
	</div>
</div>
<!-- Container -->
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span>Netzwerkkonfiguration</span>
		</div>
<?php if ($data['interface_loopback'] <= 1) { ?>
		<div class="inner">
			<strong class="red">Es wurde ein Problem in der Konfigurationsdatei festgestellt. Die Datei ist womöglich nicht richtig konfiguriert. Daher kann es zu Problemen kommen.</strong>
		</div>
<?php } ?>
		<div class="inner-table">
			<table class="table table-borderless">
				<tr>
					<th style="width: 45%;">Interface</th>
					<th style="width: 20%;">Protokoll</th>
					<th style="width: 20%;">Methode</th>
					<th style="width: 15%;"></th>
				</tr>
<?php foreach ($data['interfaces'] as $value) { ?>
				<tr>
					<td><?php echo $value[0][1]; ?></td>
					<td><?php echo formatInterfaceProtocol($value[0][2]); ?></td>
					<td><?php echo formatInterfaceMethod($value[0][3]); ?></td>
					<td style="text-align: center; vertical-align: middle;"><a href="?s=network_configuration&amp;edit=<?php echo $value[0][1]; ?>" style="margin-right: 8px;"><img src="public_html/img/edit.png" style="width: 16px; vertical-align: middle;" /></a><a href="?s=network_configuration&amp;delete=<?php echo $value[0][1]; ?>" style="margin-right: 8px;"><img src="public_html/img/delete.png" style="width: 16px; vertical-align: middle;" /></a><a href="#" name="refresh" lang="<?php echo $value[0][1]; ?>"><img src="public_html/img/refresh_black.png" style="width: 16px; vertical-align: middle;" /></a></td>
				</tr>
<?php } ?>
			</table>
		</div>
		<div class="inner-end">
			<a href="?s=network_configuration&amp;new"><button>Hinzufügen</button></a>
		</div>
	</div>
</div>