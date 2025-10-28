<?php

require_once("libs/ContactsResource.php");
require_once("libs/TagsResource.php");
require_once("libs/MessagesResource.php");
require_once("libs/ShortlinksResource.php");

class SmsApi {

    var $contactsResource;
    var $tagsResource;
    var $messagesResource;
    var $shortlinksResource;

    function __construct($apiKey, $apiSecret, $apiUrl,$assoc=false){
        $this->contactsResource = new ContactsResource($apiKey,$apiSecret, $apiUrl,$assoc);
        $this->tagsResource = new TagsResource($apiKey,$apiSecret, $apiUrl,$assoc);
        $this->messagesResource = new MessagesResource($apiKey,$apiSecret, $apiUrl,$assoc);
        $this->shortlinksResource = new ShortlinksResource($apiKey,$apiSecret, $apiUrl,$assoc);
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

    function shortlinks(){
        return $this->shortlinksResource;
    }

}