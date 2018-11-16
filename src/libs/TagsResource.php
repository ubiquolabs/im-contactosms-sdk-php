<?php
require_once("ApiWrapper.php");

class TagsResource extends ApiWrapper {

    function __construct($apiKey, $apiSecret, $apiUrl,$assoc){
        parent::__construct($apiKey,$apiSecret,$apiUrl,$assoc);
    }

    
    public function getTags($query=null, $limit=null,$start=null,$shortResults=null){
        $this->checkInteger($start);
        $this->checkInteger($shortResults,true);
        return $this->get("tags", array(
            'query' => $query,
            'limit' => $limit,
            'start' => $start,
            'shortResults' => $shortResults,
        ));
    }

    public function getByShortName($shortName){
        return $this->get("tags/$shortName", array("tag_name" => $shortName));
    }


    public function getTagContacts($shortName,$limit=null,$start=null,$status=null,$shortResults=null){
        $this->checkContactStatus($status);
        $this->checkInteger($limit);
        $this->checkInteger($start);
        $this->checkInteger($shortResults,true);
        return $this->get("tags/$shortName/contacts", array(
            'tag_name' => $shortName,
            'limit' => $limit,
            'start' => $start,
            'status' => $status,
            'shortResults' => $shortResults,
        ));
    }

    
    public function deleteTag($tag_name){
        return $this->delete("tags/$tag_name", array("tag_name" => $tag_name));
    }

}
