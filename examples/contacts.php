<?php

include("../src/SmsApi.php");


define('API_KEY', 'Your api key');
define('API_SECRET', 'Your api secret');
define('API_URL', 'Your api url');

$api = new SmsApi(API_KEY, API_SECRET, API_URL, true); // hey you'll have your responses in arrays, did you want StdObject? change last parameter to false

// Response format:
// $response = [
//     'status' => 'OK',
//     'ok' => true,
//     'code' => '200',
//     'headers' => [/* the headers */]
//     'data' => [/*the data*/]
// ];


/// Creating a contact......
echo ("Creating contact...");
$response = $api->contacts()->createContact("59824","502",'prueba' /*, $firstName, $lastName,$custom_field1,$custom_field2, $custom_field3, $custom_field4, $custom_field5*/);
if ($response-> ok) echo "I successfully created a contact!\n";
 else echo "Failed to create contact with status code {$response->code} {$response->data->error} \n";


/// Retrieving a contact...

echo ("Getting contacts...\n");
// $api->contacts()->getContacts($query=null, $limit=null,$start=null,$status=null,$shortResults=null);
$response = $api->contacts()->getContacts('12345678' /* Or a name, it works too */,10,0,'SUSCRIBED');

// check if my answer was OK
if ($response-> ok){ /* alternative: $contacts['code']==200 */
    echo "All my contacts are: \n";
    foreach ($response['data'] as $response){
        echo "Phone Number: {$response['phone_number']}, Name: {$response['full_name']}\n";
    }
} 
else echo "Failed to getting contacts with status code {$response->code} {$response->data->error} \n";


/// Updating........
echo ("Updating contact...");
// $api->conacts()->updateContact($msisdn, $phoneNumber=null, $countryCode=null, $firstName=null, $lastName = null,$custom_field1=null,$custom_field2=null, $custom_field3=null, $custom_field4=null, $custom_field5=null);
$response = $api->contacts()->updateContact("50212345678","12345678","502","Alberto");
if ($response-> ok) echo "Updated my contact\n";
else echo "Failed to updated my contact with status code {$response->code} {$response->data->error} \n";

/// Getting contact tags
echo ("Getting contact tags...\n");
$response = $api->contacts()->getContactGroups("50212345678");
echo "My contact belongs to:\n";
if ($response-> ok) foreach ($response['data'] as $group){
    echo $group['name']."\n";}
    else echo "Failed to Getting contact tags with status code {$response->code} {$response->data->error} \n";
    
/// Deleting a contact....
echo ("Deleting contact...");
$response = $api->contacts()->deleteContact("50253919824");
if ($response-> ok) echo "Contact deleted\n";
else {
    echo "Something went wrong here is the data";
    var_dump($response);
}
