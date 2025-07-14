<?php
require_once("ApiWrapper.php");

class MessagesResource extends ApiWrapper {

    function __construct($apiKey, $apiSecret, $apiUrl,$assoc){
        parent::__construct($apiKey,$apiSecret,$apiUrl,$assoc);
    }

    
    public function getMessages($startDate,
        $endDate,$limit=null,$start=null,$msisdn=null, $deliveryStatusEnable = false){
        $this->checkDate($startDate,1);
        $this->checkDate($endDate,1);
        $this->checkInteger($start);

        $params = array(
            'start_date' => $startDate,
            'end_date' => $endDate,
            'limit' => $limit,
            'start' => $start,
            'msisdn' => $msisdn,
        );

        if ($deliveryStatusEnable) {
            $params['delivery_status_enable'] = 'true';
        }

        return $this->get("messages", $params);
    }

    public function sendToContact($msisdn,$message,$id){
        return $this->post("messages/send_to_contact",null,array(
            'msisdn' => $msisdn,
            'message' => $message,
            'id' => $id,
        ));
    }

    public function sendToTag($tag,$message,$id){
        $this->checkArray($tag);
        return $this->post("messages/send", null, array(
            'tags' => $tag,
            'message' => $message,
            'id' => $id,
        ));
    }

}
