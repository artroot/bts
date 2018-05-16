<?php

namespace app\modules\messengers;

use app\components\Message;
use app\models\Users;

/**
 * Created by PhpStorm.
 * User: art
 * Date: 15.05.18
 * Time: 12:45
 */
interface iMessenger
{
    /**
     * @param Users $userModel
     * @param Message $message
     */
    public function __construct(Users $userModel, Message $message);

    public function sendMessage();

}