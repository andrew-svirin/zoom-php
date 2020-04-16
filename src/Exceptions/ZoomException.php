<?php

namespace AndrewSvirin\Zoom\Exceptions;

/**
 * Common Zoom Exception.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class ZoomException extends \Exception
{

    public function __construct($json)
    {
        if (is_array($json)) {
            $message = $json['message'];
            $code = $json['code'];
        } else {
            $message = $json;
            $code = 0;
        }
        parent::__construct($message, $code);
    }
}