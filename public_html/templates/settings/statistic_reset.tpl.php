<div>
	<div class="box">
		<div class="inner-header">
			<span>Einstellungen zur Statistik - Verlauf zurücksetzen</span>
			<?php showGoBackIcon('?s=settings&amp;do=statistic'); ?>
		</div>
		<form action="?s=settings&amp;do=statistic&amp;reset=<?php echo $data['log']; ?>&amp;confirm" method="post">
			<div class="inner">
				Möchtest du den Verlauf von <strong><?php echo $data['label']; ?></strong> wirklich zurücksetzen?
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="Zurücksetzen" />
			</div>
		</form>
	</div>
</div>