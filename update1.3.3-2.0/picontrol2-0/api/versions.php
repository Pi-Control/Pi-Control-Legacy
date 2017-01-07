<?php
$apiLatest = array('versioncode' => 1);

$apiVersions = array();

$apiVersions[0] = array('versioncode' => 1,
						'date' => 1443909600);

echo json_encode(array('versions' => $apiVersions, 'latest' => $apiLatest));
?>