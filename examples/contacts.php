<?php
/**
 * Contacts example for the SMS API
 * This example demonstrates how to work with contacts: creating, retrieving, updating, and managing contact tags
 */

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
echo "Creating contact...\n";
$response = $api->contacts()->createContact(
    "1234567890",    // phone number
    "502",      // country code
    "prueba",   // first name
    null,       // last name (optional)
    null,       // custom_field1 (optional)
    null,       // custom_field2 (optional)
    null,       // custom_field3 (optional)
    null,       // custom_field4 (optional)
    null        // custom_field5 (optional)
);

if ($response['ok']) {
    echo "Contact created successfully!\n";
} else {
    echo "Failed to create contact. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 2: Get contacts
 */
echo "\nGetting contacts...\n";
$response = $api->contacts()->getContacts(
    '12345678',     // query (phone number or name)
    10,             // limit (optional)
    0,              // start (optional)
    'SUSCRIBED'     // status (optional)
);

if ($response['ok']) {
    echo "Contacts retrieved successfully:\n";
    foreach ($response['data'] as $contact) {
        echo "Phone Number: " . $contact['phone_number'] . ", Name: " . $contact['full_name'] . "\n";
    }
} else {
    echo "Failed to get contacts. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 3: Update contact
 */
echo "\nUpdating contact...\n";
$response = $api->contacts()->updateContact(
    "50212345678",  // msisdn
    "12345678",     // phone number (optional)
    "502",          // country code (optional)
    "Alberto",      // first name (optional)
    null,           // last name (optional)
    null,           // custom_field1 (optional)
    null,           // custom_field2 (optional)
    null,           // custom_field3 (optional)
    null,           // custom_field4 (optional)
    null            // custom_field5 (optional)
);

if ($response['ok']) {
    echo "Contact updated successfully!\n";
} else {
    echo "Failed to update contact. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 4: Get contact tags
 */
echo "\nGetting contact tags...\n";
$response = $api->contacts()->getContactGroups("50212345678");
if ($response['ok']) {
    echo "Contact tags retrieved successfully:\n";
    foreach ($response['data'] as $group) {
        echo $group['name'] . "\n";
    }
} else {
    echo "Failed to get contact tags. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}

/**
 * Example 5: Delete contact
 */
echo "\nDeleting contact...\n";
$response = $api->contacts()->deleteContact("50253919824");
if ($response['ok']) {
    echo "Contact deleted successfully!\n";
} else {
    echo "Failed to delete contact. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
