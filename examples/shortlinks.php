<?php
require_once __DIR__ . '/../src/SmsApi.php';

$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $envValues = parse_ini_file($envPath, false, INI_SCANNER_RAW);
    if (is_array($envValues)) {
        foreach ($envValues as $key => $value) {
            if (!getenv($key)) {
                putenv($key . '=' . $value);
            }
        }
    }
}

$apiKey = getenv('API_KEY');
$apiSecret = getenv('API_SECRET');
$apiUrl = getenv('URL');

if (!$apiKey || !$apiSecret || !$apiUrl) {
    echo "Missing API credentials. Set API_KEY, API_SECRET, and URL environment variables.\n";
    exit(1);
}

$api = new SmsApi($apiKey, $apiSecret, $apiUrl, true);

echo "SHORTLINK API TEST (PHP)\n";
echo "==========================\n";

function getRandomLongUrl(): string {
    $urls = [
        'https://www.example.com/very-long-url-for-shortlink-tests',
        'https://www.example.com/articles/how-to-build-shortlinks?source=sdk',
        'https://www.example.com/campaigns/landing-page?ref=automation',
        'https://www.example.org/blog/feature-announcement-2025',
        'https://www.example.net/resources/download?utm=shortlink'
    ];
    return $urls[array_rand($urls)];
}

function getRandomName(): string {
    $names = [
        'Example Shortlink',
        'Marketing Campaign Link',
        'Automation Test Shortlink',
        'SDK Demo Shortlink',
        'Local Test Link'
    ];
    return $names[array_rand($names)];
}

function getRandomStatus(): string {
    return rand(0, 1) === 0 ? 'ACTIVE' : 'INACTIVE';
}

function printKeyedArray(array $data, int $indent = 0): void {
    $prefix = str_repeat(' ', $indent);
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            echo $prefix . ucfirst(str_replace('_', ' ', $key)) . ":\n";
            printKeyedArray($value, $indent + 2);
        } else {
            echo $prefix . ucfirst(str_replace('_', ' ', $key)) . ': ' . $value . "\n";
        }
    }
}

function testCreateShortlink(SmsApi $api, ?string $longUrl = null, ?string $name = null, ?string $status = null): ?array {
    echo "\nTesting create shortlink...\n";
    $longUrl = $longUrl ?: getRandomLongUrl();
    $name = $name ?: getRandomName();
    $status = $status ?: getRandomStatus();

    echo "Long URL: $longUrl\n";
    echo "Name: $name\n";
    echo "Status: $status\n";

    try {
        $response = $api->shortlinks()->createShortlink($longUrl, $name, $status);
        echo "Status: {$response['status']}\n";
        echo "Code: {$response['code']}\n";
        echo "OK: " . ($response['ok'] ? 'true' : 'false') . "\n";
        if (!empty($response['data'])) {
            echo "\nShortlink details:\n";
            printKeyedArray($response['data'], 2);
        }
        return $response;
    } catch (Exception $exception) {
        echo "Error creating shortlink: {$exception->getMessage()}\n";
        return null;
    }
}

function testListShortlinks(SmsApi $api, array $params = []): ?array {
    echo "\nTesting list shortlinks...\n";

    $requestParams = [];

    if (array_key_exists('start_date', $params) && $params['start_date'] !== null && $params['start_date'] !== '') {
        $requestParams['start_date'] = $params['start_date'];
    }

    if (array_key_exists('end_date', $params) && $params['end_date'] !== null && $params['end_date'] !== '') {
        $requestParams['end_date'] = $params['end_date'];
    }

    if (array_key_exists('limit', $params) && $params['limit'] !== null && $params['limit'] !== '') {
        $requestParams['limit'] = (int)$params['limit'];
    }

    if (array_key_exists('offset', $params) && $params['offset'] !== null && $params['offset'] !== '') {
        $requestParams['offset'] = (int)$params['offset'];
    }

    if (!empty($requestParams)) {
        echo "Parameters:\n";
        printKeyedArray($requestParams, 2);
    } else {
        echo "Parameters: none\n";
    }

    try {
        $response = $api->shortlinks()->listShortlinks($requestParams);
        echo "Status: {$response['status']}\n";
        echo "Code: {$response['code']}\n";
        echo "OK: " . ($response['ok'] ? 'true' : 'false') . "\n";

        $data = $response['data'] ?? null;
        if (isset($data['message'])) {
            echo "Message: {$data['message']}\n";
        }
        if (isset($data['account_id'])) {
            echo "Account ID: {$data['account_id']}\n";
        }

        $items = $data['data'] ?? [];
        echo 'Count: ' . count($items) . "\n";

        if (!empty($items)) {
            echo "\nShortlinks:\n";
            foreach (array_slice($items, 0, 10) as $index => $shortlink) {
                echo '  ' . ($index + 1) . '. ' . ($shortlink['name'] ?? ($shortlink['url_id'] ?? 'N/A')) . "\n";
                echo '     Short URL: ' . ($shortlink['short_url'] ?? 'N/A') . "\n";
                echo '     Long URL: ' . ($shortlink['long_url'] ?? 'N/A') . "\n";
                echo '     Status: ' . ($shortlink['status'] ?? 'UNKNOWN') . "\n";
                echo '     Created: ' . ($shortlink['created_on'] ?? 'N/A') . "\n";
            }
        }

        return $response;
    } catch (Exception $exception) {
        echo "Error listing shortlinks: {$exception->getMessage()}\n";
        return null;
    }
}

function testGetShortlinkById(SmsApi $api, string $id): ?array {
    echo "\nTesting get shortlink by ID...\n";
    try {
        $response = $api->shortlinks()->getShortlinkById($id);
        echo "Status: {$response['status']}\n";
        echo "Code: {$response['code']}\n";
        echo "OK: " . ($response['ok'] ? 'true' : 'false') . "\n";

        if (!empty($response['data'])) {
            echo "\nShortlink details:\n";
            printKeyedArray($response['data'], 2);
        }
        return $response;
    } catch (Exception $exception) {
        echo "Error getting shortlink: {$exception->getMessage()}\n";
        return null;
    }
}

function testUpdateShortlinkStatus(SmsApi $api, string $id, string $status): ?array {
    echo "\nTesting update shortlink status...\n";
    try {
        $response = $api->shortlinks()->updateShortlinkStatus($id, $status);
        echo "Status: {$response['status']}\n";
        echo "Code: {$response['code']}\n";
        echo "OK: " . ($response['ok'] ? 'true' : 'false') . "\n";

        if (!empty($response['data'])) {
            echo "\nUpdated shortlink:\n";
            printKeyedArray($response['data'], 2);
        }
        return $response;
    } catch (Exception $exception) {
        echo "Error updating shortlink: {$exception->getMessage()}\n";
        return null;
    }
}

function testListShortlinksByDate(SmsApi $api, ?string $startDate, ?string $endDate, int $limit, int $offset): ?array {
    echo "\nTesting list shortlinks by date...\n";
    return testListShortlinks($api, [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'limit' => $limit,
        'offset' => $offset,
    ]);
}

function testStatusValidation(SmsApi $api): void {
    echo "\nTesting status validation...\n";
    $invalidStatuses = ['PENDING', 'DRAFT', 'DELETED', 'active', 'inactive', 'ACTIVE ', ' INACTIVE'];

    foreach ($invalidStatuses as $status) {
        echo "\nTrying invalid status: '$status'";
        try {
            $api->shortlinks()->createShortlink(getRandomLongUrl(), getRandomName(), $status);
            echo " - Unexpected success\n";
        } catch (Exception $exception) {
            echo " - Expected error: {$exception->getMessage()}\n";
        }
    }

    echo "\nValid statuses\n";
    testCreateShortlink($api, null, 'Status Test ACTIVE', 'ACTIVE');
    testCreateShortlink($api, null, 'Status Test INACTIVE', 'INACTIVE');
}

function printUsage(): void {
    echo "Usage:\n";
    echo "  php examples/shortlinks.php                      Run basic tests\n";
    echo "  php examples/shortlinks.php create               Create a shortlink\n";
    echo "  php examples/shortlinks.php list                 List shortlinks\n";
    echo "  php examples/shortlinks.php date <start> <end> [limit] [offset]  List by date\n";
    echo "  php examples/shortlinks.php id <shortlink_id>    Get shortlink by ID\n";
    echo "  php examples/shortlinks.php update <id> <status> Update status\n";
    echo "  php examples/shortlinks.php status               Test status validation\n";
}

$args = $argv;
$command = $args[1] ?? null;

switch ($command) {
    case 'create':
        $longUrl = $args[2] ?? null;
        $name = $args[3] ?? null;
        $status = $args[4] ?? null;
        testCreateShortlink($api, $longUrl, $name, $status);
        break;

    case 'list':
        $params = [];
        if (isset($args[2]) && $args[2] !== '') {
            $params['limit'] = (int)$args[2];
        }
        if (isset($args[3]) && $args[3] !== '') {
            $params['offset'] = (int)$args[3];
        }
        testListShortlinks($api, $params);
        break;

    case 'date':
        $startDate = $args[2] ?? null;
        $endDate = $args[3] ?? null;
        $limit = isset($args[4]) ? (int)$args[4] : 10;
        $offset = isset($args[5]) ? (int)$args[5] : -6;
        testListShortlinksByDate($api, $startDate, $endDate, $limit, $offset);
        break;

    case 'id':
        if (!isset($args[2])) {
            echo "Shortlink ID is required\n";
            printUsage();
            exit(1);
        }
        testGetShortlinkById($api, $args[2]);
        break;

    case 'update':
        if (!isset($args[2], $args[3])) {
            echo "ID and status are required\n";
            printUsage();
            exit(1);
        }
        testUpdateShortlinkStatus($api, $args[2], strtoupper($args[3]));
        break;

    case 'status':
        testStatusValidation($api);
        break;

    case '--help':
    case '-h':
        printUsage();
        break;

    default:
        printUsage();
        testListShortlinks($api);
        testCreateShortlink($api);
        break;
}

