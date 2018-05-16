<?php
/**
 * Created by PhpStorm.
 * User: art
 * Date: 15.05.18
 * Time: 12:34
 */

namespace app\components;


class Message
{

    public $text;
    public $subject;
    public $format;
    public $options = [];

    const PLAIN_TEXT_FORMAT = 'plain';
    const HTML_FORMAT = 'html';

    public static $message;

    private function __construct($format, $text, $subject, $options)
    {
        $this->format = $format;
        $this->text = $text;
        $this->subject = $subject;
        $this->options = $options;
    }

    public static function fill($format, $text = null, $subject = null, $options = []):Message
    {
        if (!self::$message) {
            self::$message = new self($format, $text, $subject, $options);
        }
        return self::$message;
    }

}