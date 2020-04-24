<?php

namespace App\WebSocket;

use App\Logic\FdManager;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Redis;
use EasySwoole\Socket\AbstractInterface\Controller;

class Index extends Controller {

    public function hello(){
        $this->response()->setMessage('call hello with argaaa:' . json_encode($this->caller()->getArgs()));
    }

    public function who(){

        $server = ServerManager::getInstance()->getSwooleServer();
        $server->push(3, 'push in http at ' . date('H:i:s'));

        print_r($this->caller()->getArgs());
        $this->response()->setMessage('your fd is ' . $this->caller()->getClient()->getFd());
    }

    public function delay(){
        $this->response()->setMessage('this is delay action');
        $client = $this->caller()->getClient();

        // 异步推送, 这里直接 use fd也是可以的
        TaskManager::getInstance()->async(function() use ($client){
            $server = ServerManager::getInstance()->getSwooleServer();
            $i      = 0;
            while($i < 5){
                sleep(1);
                $server->push($client->getFd(), 'push in http at ' . date('H:i:s'));
                $i++;
            }
        });
    }

    public function test() {
        FdManager::getInstance()->setUserFd(156583,1);
        $uid = FdManager::getInstance()->getFdByUid(156583);
        $this->response()->setMessage('your fd is ' . $uid);
    }

    public function testToTool() {
        $args = $this->caller()->getArgs();
        print_r($args);
        $uid = $args['uid'];
        $msg = $args['msg'];
        $fdManager =  FdManager::getInstance();
        $fd = $this->caller()->getClient()->getFd();
        $fdManager->setUserFd($uid, $fd);
        $gfd = $fdManager->getGfdByUfd($fd);
        if (empty($gfd)) {
            $this->response()->setMessage('没找到有客服');
        }

        $server = ServerManager::getInstance()->getSwooleServer();
        $server->push($gfd, $msg);

        $this->response()->setMessage('发送成功');

    }

    public function testToWeb() {
        $args = $this->caller()->getArgs();
        print_r($args);
        $uid = $args['uid'];
        $gid = $args['gid'];
        $msg = $args['msg'];
        $fdManager =  FdManager::getInstance();
        $fd = $this->caller()->getClient()->getFd();
        $fdManager->getFdByUid($uid);
        $fdManager->setAdminFd($gid, $fd);
        $fdManager->setUfdGfdByUidGid($uid, $gid);
        $ufd = $fdManager->getFdByUid($uid);
        $gfd = $fdManager->getFdByGid($gid);

        $server = ServerManager::getInstance()->getSwooleServer();
        $server->push($ufd, $msg);


        $this->response()->setMessage('your fd is ');
    }
}