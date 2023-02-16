<?php

class AutoFriend{
    public $VK_API = null;
    public $clr = null;
    public $api = null;
    public function loaded($api){
        $this->VK_API = $api->VK_API;
        $this->clr = $api->clr;
        $this->api = $api;
    }

    public function loop(){
        $friends_reqs = $this->VK_API->getReqFriends();
        if($friends_reqs['response']['count'] > 0){
            for($i = 0; $i < $friends_reqs['response']['count']; $i++){
                $this->VK_API->method('friends.add', array(
                    "user_id" => $friends_reqs['response']['items'][$i]
                ));
                $this->api->print(
                    $this->clr->string('Принята заявка в друзья: ' , 'yellow' , null) .
                    $friends_reqs['response']['items'][$i]);
            }
        }
    }
}