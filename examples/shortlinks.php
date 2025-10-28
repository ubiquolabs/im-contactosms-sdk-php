<?php

require_once("../src/SmsApi.php");

$apiKey = getenv('API_KEY');
$apiSecret = getenv('API_SECRET');
$apiUrl = getenv('URL');

$api = new SmsApi($apiKey, $apiSecret, $apiUrl, true);

echo "Shortlinks SDK Example\n";
echo "========================\n\n";

try {
    echo "1. Creating shortlink...\n";
    $shortlink = $api->shortlinks()->createShortlink(
        "https://www.example.com/very-long-url-with-many-parameters",
        "Example Shortlink",
        "ACTIVE"
    );
    
    echo "Created shortlink: " . json_encode($shortlink['data'], JSON_PRETTY_PRINT) . "\n\n";

    echo "2. Listing shortlinks...\n";
    $shortlinks = $api->shortlinks()->listShortlinks(null, null, 10, -6);
    echo "Shortlinks: " . json_encode($shortlinks['data'], JSON_PRETTY_PRINT) . "\n\n";

    if (isset($shortlink['data']['url_id'])) {
        $shortlinkId = $shortlink['data']['url_id'];
        
        echo "3. Getting shortlink by ID...\n";
        $details = $api->shortlinks()->getShortlinkById($shortlinkId);
        echo "Shortlink details: " . json_encode($details['data'], JSON_PRETTY_PRINT) . "\n\n";

        echo "4. Updating shortlink status...\n";
        $updated = $api->shortlinks()->updateShortlinkStatus($shortlinkId, "INACTIVE");
        echo "Updated shortlink: " . json_encode($updated['data'], JSON_PRETTY_PRINT) . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

