<?php
$apiLatest = array('version' => 1, 'index' => 0);

$apiVersions = array();

$apiVersions[0] = array();
$apiVersions[0]['version'] = 1;
$apiVersions[0]['date'] = 1443909600;

echo json_encode(array('versions' => $apiVersions, 'latest' => $apiLatest));
?>