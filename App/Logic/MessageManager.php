<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2020/4/24
 * Time: 15:40
 */

namespace App\Logic;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\ServerManager;

class MessageManager {

    use Singleton;

    public function sendMsg($msgType, $msg) {
        switch($msgType) {

        }
    }
}