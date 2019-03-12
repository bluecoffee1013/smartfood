<?php
defined('IN_IA') or exit('Access Denied');

class zh_diancModule extends WeModule
{
    

    public function welcomeDisplay()
    {   

        $url = $this->createWebUrl('store');
        Header("Location: " . $url);
    }
}