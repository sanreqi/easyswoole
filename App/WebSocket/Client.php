<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2020/4/27
 * Time: 11:38
 */

namespace App\WebSocket;
use App\Common\Constants;
use App\Common\LogicException;
use App\Logic\ClientLogic;
use EasySwoole\Socket\AbstractInterface\Controller;


/**
 * 客服聊天控制器
 * Class CustomerService
 * @package App\WebSocket
 */
class Client extends WebSocketController{

    /**
     * 后台管理员连接swoole
     */
    public function adminBind() {
        //获取请求参数
        $args = $this->caller()->getArgs();
        if (false === $this->checkParams($args, ['gid'])) {
            return;
        }

        $gid = $args['gid']; //后台管理员gid
        $fd = $this->caller()->getClient()->getFd();
        $clientLogic = new ClientLogic();
        $clientLogic->adminBind($gid, $fd);

        $this->successSetMessage('');
    }

    /**
     * 前台用户发送消息
     */
    public function userSend() {
        $args = $this->caller()->getArgs();
        if (false === $this->checkParams($args, ['msg','msgType'])) {
            return;
        }
        //uid只有第一次建立连接需要传
        $uid = isset($args['uid']) ? $args['uid'] : 0;
        $ufd = $this->caller()->getClient()->getFd();

        $clientLogic = new ClientLogic();
        try {
            $clientLogic->userSend($ufd, $args['msgType'], $args['msg'], $uid);
        } catch(LogicException $e) {
            $this->exceptionSetMessage($e);
        }
    }

    /**
     * 后台管理员发送消息
     */
    public function adminSend() {

    }
















    //后台管理员连
    public function adminConnect1() {
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

    public function test() {
//        ['errorcode'=>0,'msg_type'=>'','msg'=>'hhh','content'=>['']];

        $this->response()->setMessage('call hello with argaaa:' . json_encode($this->caller()->getArgs()));


//        $fd = $this->caller()->getClient()->getFd();
//        $server = ServerManager::getInstance()->getSwooleServer();
//        $server->push('call hello with argaaa:' . json_encode($this->caller()->getArgs()));
//        $this->response()->setMessage('call hello with argaaa:' . json_encode($this->caller()->getArgs()));
//        print_r('haha');
    }
}