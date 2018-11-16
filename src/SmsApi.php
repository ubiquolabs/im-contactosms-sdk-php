<?php

require_once("libs/ContactsResource.php");
require_once("libs/TagsResource.php");
require_once("libs/MessagesResource.php");

class SmsApi {

    var $contactsResource;
    var $groupsResource;
    var $messagesResource;

    function __construct($apiKey, $apiSecret, $apiUrl,$assoc=false){
        $this->contactsResource = new ContactsResource($apiKey,$apiSecret, $apiUrl,$assoc);
        $this->tagsResource = new TagsResource($apiKey,$apiSecret, $apiUrl,$assoc);
        $this->messagesResource = new MessagesResource($apiKey,$apiSecret, $apiUrl,$assoc);
    }

    function contacts(){
        return $this->contactsResource;
    }

    function tags(){
        return $this->tagsResource;
    }

    function messages(){
        return $this->messagesResource;
    }

}