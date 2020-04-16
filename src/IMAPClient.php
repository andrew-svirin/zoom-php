<?php

namespace AndrewSvirin\Zoom;

use AndrewSvirin\Zoom\Contracts\IMAPClientInterface;

/**
 * IMAP interface client.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
final class IMAPClient implements IMAPClientInterface
{

    /**
     * @inheritDoc
     */
    public function connectionOpen($mailbox, $email, $password)
    {
        $connection = imap_open(
            $mailbox,
            $email,
            $password
        ) or die('Cannot connect to IMAP: ' . imap_last_error());
        return $connection;
    }

    /**
     * @inheritDoc
     */
    public function messagesSearch($connection, $filter)
    {
        $messages = imap_search($connection, $filter);
        if (is_array($messages)) {
            rsort($messages);
        }
        return $messages;
    }

    /**
     * @inheritDoc
     */
    public function messageBody($connection, $messageId)
    {
        $body = imap_body($connection, $messageId, 2);
        return $body;
    }
}
