<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2020/4/27
 * Time: 11:40
 */

namespace App\Logic;


use App\Common\Constants;
use App\Common\ErrorCode;
use App\Common\LogicException;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Spl\Exception\Exception;

class ClientLogic{

    /**
     * 后台连接swoole
     * @param $gid
     * @param $fd
     */
    public function adminBind($gid, $fd) {
        FdManager::getInstance()->setAdminFd($gid, $fd);
    }

    public function userSend($uid, $ufd, $msgType, $msg) {
        $fdManager = FdManager::getInstance();
        //@todo srq 改成 hsetnx
        //设置用户fd
        $fdManager->setUserFd($uid, $ufd);
        //查找客服fd
        $gid = $fdManager->getGidByUid($uid);
        $gfd = $fdManager->getFdByGid($gid);
        if (empty($gid) || empty($gfd)) {
            throw new LogicException(ErrorCode::ERRORCODE_ADMIN_OFFLINE);
        }

        //@todo srq
        $server = ServerManager::getInstance()->getSwooleServer();
        $server->push($gfd, $msg);
    }

    public function adminSend($uid, $msgType, $msg) {
        $fdManager = FdManager::getInstance();
        $ufd = $fdManager->getFdByUid($uid);
        if (empty($ufd)) {
            throw new LogicException(ErrorCode::ERRORCODE_USER_OFFLINE);
        }

        //@todo srq
        $server = ServerManager::getInstance()->getSwooleServer();
        $server->push($ufd, $msg);
    }

}