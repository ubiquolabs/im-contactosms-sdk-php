<?php
include("../src/SmsApi.php");

define('API_KEY', 'Your api key');
define('API_SECRET', 'Your api secret');
define('API_URL', 'Your api url');


$api = new SmsApi(API_KEY, API_SECRET, API_URL, false /* Now I want objects... did you want array? change last parameter to true */ );

// Response format:
// $response = [
//     'status' => 'OK',
//     'code' => '200',
//     'headers' => [/* the headers */]
//     'data' => [/*the data*/]
// ];


print("Getting tags...\n");
  // $tags = $api->tags()->getTags($query=null, $limit=null,$start=null,$shortResults=null);
$tags = $api->tags()->getTags();
if ($tags->ok) {
    echo "My tags are:\n";
    foreach ($tags->data as $tag) echo $tag->name . "\n";
} else {
    echo "Something went wrong, status {$tag->status} here is the dump!\n";
    var_dump($tags);
}


print("Getting tag test...\n");
  // $api->tags()->getByShortName($short_name);
$tag = $api->tags()->getByShortName("test");
if ($tag->ok) echo "My tag: {$tag->data->name}\n";
else echo "No tag :\n";

// $api->tags()->getTagContacts($shortName,$limit,$offset,$status,$shortResponse);
echo "Getting tag contacts:\n";
$response = $api->tags()->getTagContacts("test");
if ($response->ok){foreach ($response->data as $contact) echo"{$contact->msisdn} {$contact->full_name} \n";
 }

//foreach ($response['data'] as $contact) {
//echo "{$contact["full_name"]} {$contact["msisdn"]}\n";
else {
    echo "Data not found";
}

print("Add Tag to contact...\n");
// $api->contacts()->addTagToContact($msisdn, $tag_name);
$response = $api->contacts()->addTagToContact('50212345678', "test_sdk_php");
if ($response->ok) echo "Tag added!\n";
else echo "Failed to add tag with status code $response->code\n";


print("Remove Tag to contact...\n");
// $api->contacts()->removeTagToContact($msisdn, $tag_name);
$response = $api->contacts()->removeTagToContact('50230593400', "test");
if ($response->ok) echo "Tag removed!\n";
else echo "Failed to remove tag with status code $response->code\n";

print("Deleting tag...\n");
// $api->groups()->deleteTag($shortname);
$response = $api->tags()->deleteTag("newgroup");
if ($response->ok) echo "Tag deleted\n";
else echo "Failed to delete group with status code $response->code\n";



