<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2020/4/24
 * Time: 15:40
 */

namespace App\Logic;

use EasySwoole\EasySwoole\ServerManager;

class MessageSendLogic extends BaseLogic{

    private $server;
    private $fdManager;

    public function __construct(){
        parent::__construct();
        $this->server = ServerManager::getInstance()->getSwooleServer();
    }

//    public function sned

//    public function send($fd, $content) {
//        $this->server->push($fd, $content);
//    }

    public function sendToClinet($clientType, $cid, $content) {
//        $this->server->push($fd,'message');
    }

    //gid!=0直接发送 gid=0 redis里查
    public function sendToAdmin($gid = 0) {
        if ($gid) {

        } else {
            FdManager::getInstance()->getFdByUid();
        }
    }

    public function sendToUser($uid) {

    }
}