<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 15.05.18
 * Time: 12:54
 */

namespace app\modules\messengers;

use app\components\Message;
use app\models\Users;

/**
 * Class Messenger
 * @package app\modules\messengers
 * @property Users $userModel
 * @property Message $message
 */
abstract class Messenger implements iMessenger
{
    protected $userModel;
    protected $message;

    /**
     * @param Users $userModel
     * @param Message $message
     */
    public function __construct(Users $userModel, Message $message)
    {
        $this->$userModel = $userModel;
        $this->$message = $message;
    }

}