# SMS API PHP SDK

A PHP SDK for interacting with the SMS API service. This SDK provides easy-to-use methods for managing contacts, sending messages, and handling tags.

## Requirements

- PHP 7.0 or higher
- PHP cURL extension enabled
- PHP JSON extension enabled

## Installation

1. Clone or download this repository to your project
2. Include the SDK in your PHP file:
```php
require_once("path/to/src/SmsApi.php");
```

## Configuration

Before using the SDK, you need to configure your API credentials. You'll need:
- API Key
- API Secret
- API URL

```php
// Your API credentials
define('API_KEY', 'YOUR_API_KEY');
define('API_SECRET', 'YOUR_API_SECRET');
define('API_URL', 'YOUR_API_URL');

// Initialize the API client with array responses
$api = new SmsApi(API_KEY, API_SECRET, API_URL, true);
```

## Features

### Contact Management

#### Create a Contact
```php
$response = $api->contacts()->createContact(
    "12345678",    // phone number
    "502",         // country code
    "John",        // first name
    "Doe",         // last name (optional)
    null,          // custom_field1 (optional)
    null,          // custom_field2 (optional)
    null,          // custom_field3 (optional)
    null,          // custom_field4 (optional)
    null           // custom_field5 (optional)
);

if ($response['ok']) {
    echo "Contact created successfully!\n";
} else {
    echo "Failed to create contact. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

#### Get Contacts
```php
$response = $api->contacts()->getContacts(
    '12345678',     // query (phone number or name)
    10,             // limit (optional)
    0,              // start (optional)
    'SUSCRIBED'     // status (optional)
);

if ($response['ok']) {
    foreach ($response['data'] as $contact) {
        echo "Phone Number: " . $contact['phone_number'] . ", Name: " . $contact['full_name'] . "\n";
    }
} else {
    echo "Failed to get contacts. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

#### Update Contact
```php
$response = $api->contacts()->updateContact(
    "50212345678",  // msisdn
    "12345678",     // phone number (optional)
    "502",          // country code (optional)
    "John",         // first name (optional)
    "Doe",          // last name (optional)
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
```

#### Delete Contact
```php
$response = $api->contacts()->deleteContact("50212345678");
if ($response['ok']) {
    echo "Contact deleted successfully!\n";
} else {
    echo "Failed to delete contact. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

### Message Management

#### Send Message to Contact
```php
$response = $api->messages()->sendToContact(
    "50212345678",     // msisdn
    "Your message",    // message
    "123"             // id (optional)
);

if ($response['ok']) {
    echo "Message sent successfully!\n";
} else {
    echo "Failed to send message. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

#### Send Message to Tag
```php
$response = $api->messages()->sendToTag(
    ["test"],          // groups array
    "Your message",    // message
    "123"             // id (optional)
);

if ($response['ok']) {
    echo "Message sent to tag successfully!\n";
} else {
    echo "Failed to send message to tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

#### Get Messages
```php
$response = $api->messages()->getMessages(
    "2024-01-01",     // start date
    "2024-01-31",     // end date
    10,               // limit (optional)
    0,                // start (optional)
    null,             // msisdn (optional)
    null              // groupShortName (optional)
);

if ($response['ok']) {
    foreach ($response['data'] as $message) {
        echo $message['message'] . "\n";
    }
} else {
    echo "Failed to get messages. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

### Tag Management

#### Get Tags
```php
$response = $api->tags()->getTags(
    null,           // query (optional)
    10,             // limit (optional)
    0,              // start (optional)
    false           // shortResults (optional)
);

if ($response['ok']) {
    foreach ($response['data'] as $tag) {
        echo $tag['name'] . "\n";
    }
} else {
    echo "Failed to get tags. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

#### Get Tag Contacts
```php
$response = $api->tags()->getTagContacts(
    "test",         // shortName
    10,             // limit (optional)
    0,              // offset (optional)
    null,           // status (optional)
    false           // shortResponse (optional)
);

if ($response['ok']) {
    foreach ($response['data'] as $contact) {
        echo $contact['msisdn'] . " - " . $contact['full_name'] . "\n";
    }
} else {
    echo "Failed to get tag contacts. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

#### Add Tag to Contact
```php
$response = $api->contacts()->addTagToContact(
    '50212345678',    // msisdn
    "test_tag"        // tag_name
);

if ($response['ok']) {
    echo "Tag added successfully!\n";
} else {
    echo "Failed to add tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

#### Remove Tag from Contact
```php
$response = $api->contacts()->removeTagToContact(
    '50212345678',    // msisdn
    "test_tag"        // tag_name
);

if ($response['ok']) {
    echo "Tag removed successfully!\n";
} else {
    echo "Failed to remove tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

#### Delete Tag
```php
$response = $api->tags()->deleteTag("test_tag");
if ($response['ok']) {
    echo "Tag deleted successfully!\n";
} else {
    echo "Failed to delete tag. Error: " . ($response['data']['error'] ?? 'Unknown error') . "\n";
}
```

## Response Format

All API responses follow this structure:
```php
$response = [
    'ok' => true,                    // boolean indicating success/failure
    'data' => [/* the response data */], // array containing the response data
    'data' => ['error' => '...']     // error message if ok is false
];
```

## Error Handling

Always check the response status before proceeding:
```php
if ($response['ok']) {
    // Success
    // Process $response['data']
} else {
    // Error
    echo "Error: " . ($response['data']['error'] ?? 'Unknown error');
}
```

## Examples

Check the `/examples` directory for complete working examples:
- `quickstart.php`: Basic operations (create contact, send message, get messages)
- `contacts.php`: Contact management examples
- `messages.php`: Message handling examples
- `tags.php`: Tag management examples

## Support

For any issues or questions, please contact the API provider's support team.

## License

This SDK is provided under the MIT License.
