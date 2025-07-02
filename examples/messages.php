<?php
/**
 * Messages example for the SMS API
 * This example demonstrates how to work with messages: retrieving, sending to contacts, and sending to tags
 */

date_default_timezone_set("America/Guatemala");
require_once("../src/SmsApi.php");

// Your API credentials
define('API_KEY', 'YOUR_API_KEY');
define('API_SECRET', 'YOUR_API_SECRET');
define('API_URL', 'YOUR_API_URL');

// Initialize the API client with array responses
$api = new SmsApi(API_KEY, API_SECRET, API_URL, true);

/**
 * Example 1: Get messages
 */
echo "Getting messages...\n";
$response = $api->messages()->getMessages(
    "2015-03-01",  // start date
    "2015-03-10",  // end date
    10,            // limit (optional)
    0,             // start (optional)
    null,          // msisdn (optional)
    null           // groupShortName (optional)
);

if ($response['ok']) {
    echo "Messages retrieved successfully:\n";
    foreach ($response['data'] as $message) {
        echo $message['message'] . "\n";
    }
} else {
    echo "Failed to get messages. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 2: Send message to contact
 */
echo "\nSending message to contact...\n";
$response = $api->messages()->sendToContact(
    "50245858369",     // msisdn
    "Sent from PHP SDK", // message
    "123"              // id (optional)
);

if ($response['ok']) {
    echo "Message sent successfully!\n";
} else {
    echo "Failed to send message. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 3: Send message to tag
 */
echo "\nSending message to tag...\n";
$response = $api->messages()->sendToTag(
    ["test"],          // groups array
    "Test message to group", // message
    "12434"            // id (optional)
);

if ($response['ok']) {
    echo "Message sent to tag successfully!\n";
} else {
    echo "Failed to send message to tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
