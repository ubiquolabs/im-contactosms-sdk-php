<?php
/**
 * Tags example for the SMS API
 * This example demonstrates how to work with tags: retrieving, managing tag contacts, and tag operations
 */

require_once(__DIR__ . "/../src/SmsApi.php");

// Your API credentials
define('API_KEY', 'YOUR_API_KEY');
define('API_SECRET', 'YOUR_API_SECRET');
define('API_URL', 'YOUR_API_URL');

// Initialize the API client with array responses
$api = new SmsApi(API_KEY, API_SECRET, API_URL, true);

/**
 * Example 1: Get all tags
 */
echo "Getting tags...\n";
$response = $api->tags()->getTags(
    null,   // query (optional)
    10,     // limit (optional)
    0,      // start (optional)
    false   // shortResults (optional)
);

if ($response['ok']) {
    echo "Tags retrieved successfully:\n";
    foreach ($response['data'] as $tag) {
        echo $tag['name'] . "\n";
    }
} else {
    echo "Failed to get tags. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 2: Get specific tag
 */
echo "\nGetting tag 'tag_name'...\n";
$response = $api->tags()->getByShortName("tag_name");
if ($response['ok']) {
    echo "Tag found: " . $response['data']['name'] . "\n";
} else {
    echo "Failed to get tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 3: Get tag contacts
 */
echo "\nGetting contacts for tag 'tag_name'...\n";
$response = $api->tags()->getTagContacts(
    "tag_name",     // shortName
    10,         // limit (optional)
    0,          // offset (optional)
    null,       // status (optional)
    false       // shortResponse (optional)
);

if ($response['ok']) {
    echo "Tag contacts retrieved successfully:\n";
    foreach ($response['data'] as $contact) {
        echo $contact['msisdn'] . " - " . $contact['full_name'] . "\n";
    }
} else {
    echo "Failed to get tag contacts. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 4: Add tag to contact
 */
echo "\nAdding tag to contact...\n";
$response = $api->contacts()->addTagToContact(
    '50212345678',    // msisdn
    "tag_name_sdk_php"    // tag_name
);

if ($response['ok']) {
    echo "Tag added successfully!\n";
} else {
    echo "Failed to add tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 5: Remove tag from contact
 */
echo "\nRemoving tag from contact...\n";
$response = $api->contacts()->removeTagToContact(
    '50212345678',    // msisdn
    "tag_name"            // tag_name
);

if ($response['ok']) {
    echo "Tag removed successfully!\n";
} else {
    echo "Failed to remove tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 6: Delete tag
 */
echo "\nDeleting tag...\n";
$response = $api->tags()->deleteTag("tag_name");
if ($response['ok']) {
    echo "Tag deleted successfully!\n";
} else {
    echo "Failed to delete tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}



