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
            $message = isset($json['message']) ?
                $json['message'] :
                (isset($json['errorMessage']) ?
                    $json['errorMessage'] :
                    json_encode($json)
                );
            $code = isset($json['code']) ?
                $json['code'] :
                (isset($json['errorCode']) ?
                    $json['errorCode'] :
                    0
                );
        } else {
            $message = $json;
            $code = 0;
        }
        parent::__construct($message, $code);
    }
}