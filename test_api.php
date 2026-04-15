<?php
echo "<!DOCTYPE html>
<html>
<head>
<style>
body { font-family: monospace; background: #f0f0f0; padding: 20px; }
.box { background: white; border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
.header { background: #0066cc; color: white; padding: 10px; font-weight: bold; margin-top: 15px; }
.success { color: green; }
.error { color: red; font-weight: bold; }
.info { color: #0066cc; }
pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
</style>
</head>
<body>

<h2>🔍 NeptunePlay API Test Report</h2>

<div class='box'>
<div class='header'>Server Information</div>
<p><strong>Server IP:</strong> <span class='info'>76.13.188.83</span></p>
<p><strong>Endpoint:</strong> https://bs.sxvwlkohlv.com/api/v2/auth/createtoken</p>
<p><strong>Client ID:</strong> Royaltech</p>
<p><strong>Client Secret:</strong> N2aftBsrYchTQ6G5R9zLSxUwGxtoK7bv</p>
<p><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</p>
</div>

<div class='box'>
<div class='header'>cURL Test</div>
";

$clientId = 'Royaltech';
$clientSecret = 'N2aftBsrYchTQ6G5R9zLSxUwGxtoK7bv';
$url = 'https://bs.sxvwlkohlv.com/api/v2/auth/createtoken';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'clientId' => $clientId,
    'clientSecret' => $clientSecret
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>HTTP Status Code:</strong> <span class='" . ($http_code == 200 ? 'success' : 'error') . "'>$http_code</span></p>";
if ($error) {
    echo "<p><strong>cURL Error:</strong> <span class='error'>$error</span></p>";
}

echo "<p><strong>Response Body:</strong></p>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

echo "
</div>

<div class='box'>
<div class='header'>Conclusion</div>
<p>";
if ($http_code == 200) {
    echo "<span class='success'>✓ API Connection Success!</span>";
} else {
    echo "<span class='error'>✗ API Returned HTTP $http_code - Credentials may not be white-listed for this IP</span>";
}
echo "</p>
</div>

</body>
</html>
";
?>