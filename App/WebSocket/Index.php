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
        $fd = $this->caller()->getClient()->getFd();
        $server = ServerManager::getInstance()->getSwooleServer();
        $server->push($fd, 'push in http at ' . date('H:i:s').'good');

//        print_r($this->caller()->getArgs());
//        $this->response()->setMessage('your fd is ' . $this->caller()->getClient()->getFd());
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

//        $this->response()->setMessage('发送成功');

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


//        $this->response()->setMessage('your fd is ');
    }

    public function adminConnect() {
        echo 'admin connect---------';
        //后台连接，gid和fd关系
        $fd = $this->caller()->getClient()->getFd();
        $args = $this->caller()->getArgs();
        $gid = $args['gid'];
        print_r($args);
        echo  "\n";
        $this->response()->setMessage('your fd is ' . $fd);
        $fdManager =  FdManager::getInstance();
        $fdManager->setAdminFd($gid, $fd);
        $this->response()->setMessage('gid '. $gid . ', fd '. $fd);
    }

    public function userSend() {
        echo 'usersend-----------';
        //设置uid-fd,设置ufd-gfd,分配客服
        $fd = $this->caller()->getClient()->getFd();
        $args = $this->caller()->getArgs();
        $uid = $args['uid'];
        $gid = $args['gid'];
        $msg = $args['msg'];
        $fdManager =  FdManager::getInstance();
        print_r('uid:'.$uid.',gid:'.$gid.',userfd:'.$fd);
        echo  "\n";
        $fdManager->setUserFd($uid, $fd);
        //管理员fd
        $gfd = $fdManager->getFdByGid($gid);
        $fdManager->setUfdGfdByUidGid($uid, $gid);
        $server = ServerManager::getInstance()->getSwooleServer();
        $server->push($gfd, $msg);
    }

    public function adminSend() {
        echo 'admin send--------------';
        $fd = $this->caller()->getClient()->getFd();
        $args = $this->caller()->getArgs();
        $uid = $args['uid'];
        $gid = $args['gid'];
        $msg = $args['msg'];
        $fdManager =  FdManager::getInstance();
        $ufd = $fdManager->getFdByUid($uid);
        print_r('uid:'.$uid.',gid:'.$gid.',userfd:'.$ufd);
        echo  "\n";
        $server = ServerManager::getInstance()->getSwooleServer();
        $server->push($ufd, $msg);
    }
}