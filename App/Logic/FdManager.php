<?php


namespace App\Logic;


use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Redis;
use EasySwoole\Socket\Exception\Exception;

class FdManager extends BaseLogic
{
    use Singleton;

    const CLIENT_TYPE_USER = 1; //前台用户
    const CLIENT_TYPE_ADMIN = 2; //管理后台销售客服

    //redis键名，hash
    private $keyGidFd = 'esw_gid_fd'; //redis键名 hash类型，管理后台用户gid对应fd
    private $keyUidFd = 'esw_uid_fd'; //redis键名 hash类型，前台用户uid对应fd
    private $keyFdUid = 'esw_fd_uid'; //redis键名 hash类型，fd对应uid
    private $keyUidGid = 'esw_uid_gid'; //redis键名 hash类型，前台用户uid对应管理后台用户gid
    private $redis; //EasySwoole\Redis\Redis类

    public function __construct(){
        $config = Config::getInstance()->getConf('REDIS');
        $this->redis = new Redis(new RedisConfig($config));
    }


    /**
     * 设置客户端fd值
     * @param $clientType 客户端类型 1-前台用户 2-管理后台用户
     * @param $cid $clientType=1时为uid，$clientType=2时为gid
     * @param $fd fd值
     * @return bool
     */
    public function setClientFd($clientType, $cid, $fd) {
        $key = $this->getFdKeyByType($clientType);
        $this->redis->hSet($key, $cid, $fd);
    }

    /**
     * 根据客户id获取fd值
     * @param $clientType 客户端类型 1-前台用户 2-管理后台用户
     * @param $cid $clientType=1时为uid，$clientType=2时为gid
     */
    public function getFdByCid($clientType, $cid) {
        $key = $this->getFdKeyByType($clientType);
        return $this->redis->hGet($key, $cid);
    }

    public function setAdminFd($gid, $fd) {
        $this->setClientFd(self::CLIENT_TYPE_ADMIN, $gid, $fd);
    }

    public function setUserFd($uid, $fd) {
        $this->setClientFd(self::CLIENT_TYPE_USER, $uid, $fd);
        $this->redis->hSet($this->keyFdUid, $fd, $uid);
    }

    public function getFdByGid($gid) {
        return $this->getFdByCid(self::CLIENT_TYPE_ADMIN, $gid);
    }

    public function getFdByUid($uid) {
        return $this->getFdByCid(self::CLIENT_TYPE_USER, $uid);
    }

    public function getUidByFd($fd) {
        return $this->redis->hGet($this->keyFdUid, $fd);
    }

    /**
     * @param $clientType 客户端类型 1-前台用户 2-管理后台用户
     * @param $cid $clientType=1时为uid，$clientType=2时为gid
     */
    private function getFdKeyByType($clientType) {
        if (!in_array($clientType, [self::CLIENT_TYPE_USER, self::CLIENT_TYPE_ADMIN])) {
            return false;
        }

        if ($clientType == self::CLIENT_TYPE_USER) {
            $key = $this->keyUidFd;
        } else {
            $key = $this->keyGidFd;
        }
        return $key;
    }

    public function setUidGid($uid, $gid) {
        $this->redis->hSet($this->keyUidGid, $uid, $gid);
    }

    public function getGidByUid($uid) {
        return $this->redis->hGet($this->keyUidGid, $uid);
    }

    /**
     * 通过前台用户fd获取客服fd
     * @param $fd
     * @return bool|string
     */
    public function getGfdByUfd($ufd) {
        $uid = $this->getUidByFd($ufd);
        if (empty($uid)) {
            return '';
        }
        $gid = $this->getGidByUid($uid);
        if (empty($gid)) {
            return '';
        }

        return $this->getFdByGid($gid);
    }

}