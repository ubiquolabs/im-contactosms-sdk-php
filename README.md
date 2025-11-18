# SMS API PHP SDK

A PHP SDK for interacting with the SMS API service. This SDK provides easy-to-use methods for managing contacts, sending messages, handling tags, and creating shortlinks with perfect UTF-8 character support.

## Rate Limits

The API has rate limits to ensure fair usage:

- **Shortlinks**: Maximum of 10 shortlinks created per minute per account (default)
- When you exceed the limit, you'll receive a 403 error with code `42900`
- **For inquiries or requests to increase the limit**: Please contact Technical Support directly through their support channels

Example error response:
```json
{
  "code": 42900,
  "error": "Ha excedido el límite de solicitudes. Intente nuevamente más tarde"
}
```

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

Before using the SDK, configure your API credentials using environment variables:

```bash
export API_KEY='your_api_key'
export API_SECRET='your_api_secret'
export URL='your_api_url'
```

Or set them in your PHP code:

```php
$apiKey = getenv('API_KEY');
$apiSecret = getenv('API_SECRET');
$apiUrl = getenv('URL');

$api = new SmsApi($apiKey, $apiSecret, $apiUrl, true);
```

The last parameter `true` returns responses as associative arrays instead of objects.

## Features

### Contact Management

#### Create a Contact
```php
$response = $api->contacts()->createContact(
    "12345678",    // phone number
    "502",         // country code
    "John",        // first name
    "Doe",         // last name (optional)
    null           // custom fields (optional)
);

if ($response['ok']) {
    echo "Contact created successfully!\n";
}
```

#### Get Contacts
```php
$response = $api->contacts()->getContacts(
    '50212345678',     // query
    10,                // limit
    0,                 // start
    'SUSCRIBED'        // status
);

if ($response['ok']) {
    foreach ($response['data'] as $contact) {
        echo $contact['phone_number'] . "\n";
    }
}
```

#### Update Contact
```php
$response = $api->contacts()->updateContact(
    "50212345678",  // msisdn
    "12345678",     // phone number
    "502",          // country code
    "John",         // first name
    "Doe"           // last name
);

if ($response['ok']) {
    echo "Contact updated!\n";
}
```

#### Delete Contact
```php
$response = $api->contacts()->deleteContact("50212345678");
if ($response['ok']) {
    echo "Contact deleted!\n";
}
```

### Message Management

#### Send Message to Contact
```php
$response = $api->messages()->sendToContact(
    "50212345678",
    "Hello from PHP SDK!",
    "123"
);

if ($response['ok']) {
    echo "Message sent!\n";
}
```

#### Get Messages
```php
$response = $api->messages()->getMessages(
    "2024-01-01",  // start date
    "2024-01-31",  // end date
    10             // limit
);

if ($response['ok']) {
    foreach ($response['data'] as $message) {
        echo $message['message'] . "\n";
    }
}
```

### Tag Management

#### Get Tags
```php
$response = $api->tags()->getTags(null, 10, 0, false);

if ($response['ok']) {
    foreach ($response['data'] as $tag) {
        echo $tag['name'] . "\n";
    }
}
```

#### Get Tag Contacts
```php
$response = $api->tags()->getTagContacts("test", 10);

if ($response['ok']) {
    foreach ($response['data'] as $contact) {
        echo $contact['msisdn'] . "\n";
    }
}
```

#### Add Tag to Contact
```php
$response = $api->contacts()->addTagToContact('50212345678', "vip");

if ($response['ok']) {
    echo "Tag added!\n";
}
```

#### Remove Tag from Contact
```php
$response = $api->contacts()->removeTagToContact('50212345678', "vip");

if ($response['ok']) {
    echo "Tag removed!\n";
}
```

### Shortlink Management

#### Create Shortlink
```php
$response = $api->shortlinks()->createShortlink(
    "https://www.example.com/very-long-url",
    "My Shortlink",
    "ACTIVE",
    "promoAlias"
);

if ($response['ok']) {
    echo "Shortlink created: " . $response['data']['short_url'] . "\n";
}
```

> Pass `null` for the third parameter if you want to specify an alias while keeping the default `ACTIVE` status.
> **Alias rules:** 1–30 printable characters, no spaces. Provide a custom alias only when you need a predictable slug; otherwise omit it and the platform will auto-generate one. Re-using the same alias on the same domain returns `500 Bad Request` from the ShortURL API. Shortlinks can be deactivated but **not** reactivated.
> Names are trimmed and limited to 50 characters.

#### List Shortlinks
```php
$response = $api->shortlinks()->listShortlinks(array(
    'start_date' => '2024-01-01',
    'end_date' => '2024-12-31',
    'limit' => 10,
    'offset' => -6
));

if ($response['ok']) {
    foreach ($response['data']['data'] as $shortlink) {
        echo $shortlink['name'] . " - " . $shortlink['short_url'] . "\n";
    }
}
```

#### Get Shortlink by ID
```php
$response = $api->shortlinks()->getShortlinkById("abc123");

if ($response['ok']) {
    echo "Shortlink: " . $response['data']['short_url'] . "\n";
}
```

#### Update Shortlink Status
```php
$response = $api->shortlinks()->updateShortlinkStatus(
    "123ABC", // shortlink ID is required
    "INACTIVE"
);

if ($response['ok']) {
    echo "Shortlink deactivated!\n";
}
```

### API Response Examples

#### Create Shortlink - Success
```json
{
  "success": true,
  "message": "Shortlink created successfully",
  "account_id": 12345,
  "url_id": "123ABC",
  "short_url": "https://shorturl-pais.com/123ABC",
  "alias": "promoAlias",
  "long_url": "https://www.example.com/very-long-url-with-parameters"
}
```

#### List Shortlinks - Success
```json
{
  "success": true,
  "message": "Shortlinks retrieved successfully",
  "data": [
    {
      "_id": "123ABC",
      "account_uid": "abcde12345678kklm",
      "name": "Enlace corto de prueba",
      "status": "INACTIVE",
      "base_url": "https://shorturl-pais.com/",
      "short_url": "https://shorturl-pais.com/123ABC",
      "alias": "promoAlias",
      "long_url": "https://www.example.com/long-url-here",
      "visits": 0,
      "unique_visits": 0,
      "preview_visits": 0,
      "created_by": "SHORTLINK_API",
      "reference_type": "SHORT_LINK",
      "expiration": false,
      "expiration_date": null,
      "created_on": 1735689600000
    }
  ],
  "account_id": 12345
}
```

#### Get Shortlink by ID - Success
```json
{
  "success": true,
  "message": "Shortlink found",
  "account_id": 12345,
  "url_id": "123ABC",
  "short_url": "https://shorturl-pais.com/123ABC",
  "alias": "promoAlias",
  "long_url": "https://www.example.com/long-url-with-parameters",
  "name": "Example Shortlink",
  "status": "ACTIVE",
  "visits": 0,
  "unique_visits": 0,
  "preview_visits": 0,
  "created_by": "SHORTLINK_API",
  "created_on": 1735689600000
}
```

#### Get Shortlink by ID - Not Found
```json
{
  "success": false,
  "message": "Shortlink not found"
}
```

#### Rate Limit Exceeded
When you create too many shortlinks in a short time window (default: 10 per minute per account):
```json
{
  "code": 42900,
  "error": "Ha excedido el límite de solicitudes. Intente nuevamente más tarde"
}
```

## Response Format

All API responses follow this structure:
```php
$response = [
    'code' => 200,           // HTTP status code
    'status' => 'OK',        // HTTP status text
    'ok' => true,  // boolean indicating success
    'data' => [...],        // response data
];
```

## Error Handling

Check response status before proceeding:
```php
if ($response['ok']) {
    // Success
    // Process $response['data']
} else {
    // Error
    echo "Error code: " . $response['code'] . "\n";
}
```

## UTF-8 Support

This PHP SDK supports UTF-8 characters:
- Spanish characters: `¡¿`
- Accented letters: `áéíóú ñÑ`
- Currency symbols: `€¢£¥`
- Mathematical symbols: `∞±√`
- All Unicode characters

Technical implementation uses `JSON_UNESCAPED_UNICODE` for proper character encoding.

## Examples

Check the `/examples` directory for complete working examples:
- `quickstart.php`: Basic operations
- `contacts.php`: Contact management
- `messages.php`: Message handling
- `tags.php`: Tag management
- `shortlinks.php`: Shortlink operations

## Testing

Run examples from the command line:

### Shortlinks Testing

```bash
# Run default flow (list + create)
php examples/shortlinks.php

# Create a shortlink
php examples/shortlinks.php create

# List all shortlinks (no parameters)
php examples/shortlinks.php list

# List with limit only
php examples/shortlinks.php list 20

# List with limit and offset
php examples/shortlinks.php list 20 -6

# List by date range
php examples/shortlinks.php date 2025-01-01 2025-12-31

# List by date with limit and offset
php examples/shortlinks.php date 2025-01-01 2025-12-31 20 -5

# Get shortlink by ID
php examples/shortlinks.php id 123ABC

# Update shortlink status
php examples/shortlinks.php update 123ABC INACTIVE

# Test status validation
php examples/shortlinks.php status
```

### Other Resources

```bash
php examples/messages.php
php examples/contacts.php
php examples/tags.php
```

## Support

For issues or questions, contact the API provider's support team.

## License

This SDK is provided under the MIT License.
