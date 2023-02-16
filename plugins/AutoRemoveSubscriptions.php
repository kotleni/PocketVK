<?php

class AutoRemoveSubscriptions{
    public $VK_API = null;
    public $clr = null;
    public $api = null;
    public function loaded($api){
        $this->VK_API = $api->VK_API;
        $this->clr = $api->clr;
        $this->api = $api;
    }

    public function loop(){
        $a = $this->VK_API->method('users.getSubscriptions' , array(
            "extended" => 1,
            "count" => 1
        ));

        foreach($a['response']['items'] as $item){
            if($item['type'] == 'page'){// page
                /*$name = $item['name'];
                $groupid = $item['id'];
                $this->VK_API->method('groups.leave', array(
                    "group_id" => $groupid
                ));
                $this->api->print("Leave: " . $groupid . " | " . $name);*/
            }else{// user
                $name = $item['first_name'] . ' ' . $item['last_name'];
                $userid = $item['id'];
                $this->VK_API->method('account.ban', array(
                    "owner_id" => $userid
                ));
                $this->api->print("Бан отписоты: " . $userid . " | " . $name);
            }
        }
        
    }
}