<?php

namespace AndrewSvirin\Zoom\Contracts;

/**
 * ZOOM Account client interface.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
interface ZoomEmailClientInterface
{

    /**
     * Create email by adding suffix to imap account email.
     * @param int|string $id
     * @param string $connector
     * @return string
     */
    public function create($id, $connector = '+'): string;

    /**
     * Visit email box, visit activation form for activate new email.
     * Call activation after create user in Zoom.
     * @param string $email
     * @param string $password
     * @param null|string $firstName
     * @param null|string $lastName
     * @return bool
     */
    public function activate($email, $password, $firstName = null, $lastName = null): bool;
}