<?php
/**
 * Created by PhpStorm.
 * User: srq
 * Date: 2020/4/27
 * Time: 15:15
 */

namespace App\Common;


use Throwable;

class LogicException extends \Exception {

    public function __construct($code = 0, $message = "", Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}