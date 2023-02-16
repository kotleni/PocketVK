<?php

class Daywill{ // account.ban
    public $VK_API = null;
    public $clr = null;
    public $api = null;
    public function loaded($api){
        $this->VK_API = $api->VK_API;
        $this->clr = $api->clr;
        $this->api = $api;
    }


    public function loop(){
        $base = array(
            "привет" => "Привет. Вас обслуживает Daywill. Отправте свое сообщение , через время Виктор вам ответит.",
            "ку" => "Ку. Вас обслуживает Daywill. Отправте свое сообщение , через время Виктор вам ответит.",
            "здравствуйте" => "Здравствуйте. Вас обслуживает Daywill. Отправте свое сообщение , через время Виктор вам ответит."
          
        );
        $messages = $this->VK_API->method('messages.getConversations' , array(
            "offset" => 0,
            "count" => 10,
            "time_offset" => 0,
            "filters" => 0,
            "preview_length" => 100,
            "last_message_id" => 0
        ));
        $i = 0;
        foreach($messages['response']['items'] as $item){
            $userid = $item['conversation']['peer']['id'];
            $message = $item['last_message']['text'];
            $out = $item['last_message']['out'];
            if($item['conversation']['peer']['type'] == "user" and $out == 0){
                foreach($base as $key => $value){
                    $flex = strpos($message , $key);
                    if($flex > -1){
                
                $j = $this->VK_API->method('messages.send', array(
                    "user_id" => $userid,
                    "random_id" => rand(100220010,818882281),
                    "message" => $value
                ));
                break;
            }
        }

              
            }
            $i++;
        }
    }

}