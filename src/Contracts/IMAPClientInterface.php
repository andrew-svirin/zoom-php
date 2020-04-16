<?php

namespace AndrewSvirin\Zoom\Contracts;

/**
 * ZOOM Account client interface.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
interface IMAPClientInterface
{

    /**
     * Connect to IMAP server.
     * @param string $mailbox
     * @param string $email
     * @param string $password
     * @return false|resource
     */
    public function connectionOpen($mailbox, $email, $password);

    /**
     * Search Emails having the specified filter.
     * @param resource $connection
     * @param string $filter
     * @return array|false
     */
    public function messagesSearch($connection, $filter);

    /**
     * Get message body by it ID.
     * @param resource $connection
     * @param int $messageId
     * @return string
     */
    public function messageBody($connection, $messageId);
}