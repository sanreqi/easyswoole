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

    public function userSend($ufd, $msgType, $msg, $uid) {
        $fdManager = FdManager::getInstance();
        //查用户有没有对应fd
        $ufd = $fdManager->getUidByFd($ufd);
        if (empty($ufd)) {
            if (!empty($uid)) {

            } else {
                throw new LogicException(ErrorCode::ERRORCODE_USER_OFFLINE);
            }
        }

//        $gfd = $fdManager->getGfdByUfd($ufd);
//        if (empty($gfd)) {
//            logic_exception(ErrorCode::ERRORCODE_ADMIN_OFFLINE);
//        }
//
//        $server = ServerManager::getInstance()->getSwooleServer();
//        $server->push($gfd, $msg);
    }
}