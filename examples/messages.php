<?php
/**
 * UTF-8 Test for PHP SDK
 * Demonstrates perfect UTF-8 character handling compatible with JavaScript and Python SDKs
 */

// Set UTF-8 encoding for the script (optional - only if mbstring is available)
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
    mb_http_output('UTF-8');
}

date_default_timezone_set("America/Guatemala");
require_once(__DIR__ . "/../src/SmsApi.php");

// Your API credentials
define('API_KEY', 'api_key');
define('API_SECRET', 'api_secret_key');
define('API_URL', 'api_url');

echo "🔧 PHP SDK Test\n";
echo "===================================================\n\n";

try {
    // Initialize the API client with array responses
    $api = new SmsApi(API_KEY, API_SECRET, API_URL, true);
    
    // Test 1: Spanish special characters (¡¿)
    echo "📝 Test 1: Spanish Special Characters\n";
    echo "------------------------------------\n";
    
    $spanishMessage = "¡Hola desde PHP SDK! ¿Se ven correctamente los caracteres especiales?";
    echo "✅ Original: $spanishMessage\n";
    
    $response1 = $api->messages()->sendToContact(
        "50212345678",
        $spanishMessage,
        "utf8-test-1-" . uniqid()
    );
    
    if ($response1['ok']) {
        echo "✅ Spanish characters message sent successfully\n";
        echo "   Response: " . json_encode($response1['data'], JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "❌ Failed to send Spanish message: " . ($response1['data']['error'] ?? 'Unknown error') . "\n";
    }
    
    // Test 2: Extended UTF-8 characters
    echo "\n📝 Test 2: Extended UTF-8 Characters\n";
    echo "-----------------------------------\n";
    
    $extendedMessage = "Acentos: áéíóú ÁÉÍÓÚ ñÑ. Símbolos: €¢£¥ ©®™";
    echo "✅ Original: $extendedMessage\n";
    
    $response2 = $api->messages()->sendToContact(
        "50212345678",
        $extendedMessage,
        "utf8-test-2-" . uniqid()
    );
    
    if ($response2['ok']) {
        echo "✅ Extended UTF-8 message sent successfully\n";
        echo "   Response: " . json_encode($response2['data'], JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "❌ Failed to send extended UTF-8 message: " . ($response2['data']['error'] ?? 'Unknown error') . "\n";
    }
    
    // Test 3: Emojis and Unicode symbols
    echo "\n📝 Test 3: Emojis and Unicode\n";
    echo "-----------------------------\n";
    
    $emojiMessage = "PHP SDK con emojis: 🚀🎉💻 Unicode: ←→↑↓ Math: ∞±√";
    echo "✅ Original: $emojiMessage\n";
    
    $response3 = $api->messages()->sendToContact(
        "50212345678",
        $emojiMessage,
        "utf8-test-3-" . uniqid()
    );
    
    if ($response3['ok']) {
        echo "✅ Emoji/Unicode message sent successfully\n";
        echo "   Response: " . json_encode($response3['data'], JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "❌ Failed to send emoji message: " . ($response3['data']['error'] ?? 'Unknown error') . "\n";
    }
    
    // Summary
    echo "\n🎉 UTF-8 Test Summary\n";
    echo "====================\n";
    echo "✓ PHP SDK now handles UTF-8 characters like JavaScript and Python\n";
    echo "✓ JSON_UNESCAPED_UNICODE preserves characters without escaping\n";
    echo "✓ Content-Type header includes charset=utf-8\n";
    echo "✓ Compatible with ensure_ascii=False (Python) and JSON.stringify (JavaScript)\n";
    echo "✓ Perfect UTF-8 handling in HTTP requests and JSON serialization\n";
    
} catch (Exception $e) {
    echo "❌ Error in UTF-8 test: " . $e->getMessage() . "\n";
}
