<?php
/**
 * Quickstart example for the SMS API
 * This example demonstrates basic operations: creating a contact, sending a message, and retrieving messages
 */

// Set your timezone
date_default_timezone_set("America/Guatemala");

// Include the SDK
require_once(__DIR__ . "/../src/SmsApi.php");

// Your API credentials
define('API_KEY', 'YOUR_API_KEY');
define('API_SECRET', 'YOUR_API_SECRET');
define('API_URL', 'YOUR_API_URL');

// Initialize the API client with array responses
$api = new SmsApi(API_KEY, API_SECRET, API_URL, true);

/**
 * Example 1: Create a contact
 */
echo "Creating a contact...\n";
$response = $api->contacts()->createContact(
    "12345678",    // phone number
    "502",         // country code
    "John",        // first name
    "Doe"        // last name
);

if ($response['ok']) {
    echo "Contact created successfully!\n";
} else {
    echo "Failed to create contact. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 2: Send a message
 */
echo "\nSending a message...\n";
$response = $api->messages()->sendToContact(
    "50212345678",     // msisdn
    "Hello from PHP SDK!",    // message
    "1234567890"             // id (optional)
);

if ($response['ok']) {
    echo "Message sent successfully!\n";
} else {
    echo "Failed to send message. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 3: Get messages
 */
echo "\nGetting messages...\n";
$response = $api->messages()->getMessages(
    date('Y-m-d', strtotime('-7 days')),  // start date (7 days ago)
    date('Y-m-d'),                        // end date (today)
    10,                                   // limit
    null,                                 // start
    null,                                 // msisdn
    true                                  // deliveryStatusEnable
);

if ($response['ok']) {
    echo "Messages retrieved successfully!\n";
    foreach ($response['data'] as $message) {
        echo "Message: " . $message['message'] . "\n";
    }
} else {
    echo "Failed to get messages. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
} 