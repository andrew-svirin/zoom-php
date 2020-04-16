<?php

namespace AndrewSvirin\Zoom;

use AndrewSvirin\Zoom\Contracts\ZoomEmailClientInterface;
use AndrewSvirin\Zoom\Exceptions\ZoomException;

/**
 * ZOOM account client representation.
 * Manage Zoom Account Emails.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class ZoomEmailClient implements ZoomEmailClientInterface
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var IMAPClient
     */
    protected $imapClient;

    /**
     * @var array [
     *      'mailbox' => '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX',
     *      'account' => [
     *          'email' => 'some@email',
     *          'password' => 'pass',
     *      ],
     *  ]
     */
    protected $config;

    public function __construct($httpClient, $imapClient, $config)
    {
        $this->httpClient = $httpClient;
        $this->imapClient = $imapClient;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function create($id, $connector = '+'): string
    {
        $parts = explode('@', $this->config['account']['email'], 2);
        $email = $parts[0] . $connector . $id . '@' . $parts[1];
        return $email;
    }

    /**
     * @inheritDoc
     * @throws ZoomException
     */
    public function activate($email, $password, $firstName = null, $lastName = null): bool
    {
        // No email found.
        if (!($data = $this->getEmailData('Zoom account invitation', $email))) {
            return false;
        }

        // Parse activation url.
        preg_match("/\"(?<url>http.*hostinvite)\"/", $data, $matches);
        // No matched found.
        if (empty($matches['url'])) {
            return false;
        }
        // Prepare activation url.
        $url = str_replace('/activate_help?', '/activate?', $matches['url']);
        $activationUrl = $matches['url'];

        // Parse zoom url.
        preg_match("/(?<site>http.*)\/.*hostinvite/", $activationUrl, $matches);
        // No matched found.
        if (empty($matches['site'])) {
            return false;
        }
        $activationSite = $matches['site'];

        // Parse code.
        preg_match("/code=(?<code>.*)\&/", $activationUrl, $matches);
        // No matched found.
        if (empty($matches['code'])) {
            return false;
        }
        $activationCode = $matches['code'];

        $jar = new \GuzzleHttp\Cookie\CookieJar;
        // Get content from activation form. And setup cookies.
        $get = $this->httpClient->get($url, [
            'cookies' => $jar,
        ]);
        $content = $get->getBody()->getContents();

        // Post form for activation.
        $body = http_build_query([
            'code' => $activationCode,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'password' => $password,
            'fr' => 'hostinvite',
        ]);
        $post = $this->httpClient->post($activationSite . '/set_password', [
            'headers' => [
                'content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'origin' => 'https://api.zoom.us',
                'referer' => $url,
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',
            ],
            'cookies' => $jar,
            'body' => $body,
        ]);

        $setPassword = json_decode($post->getBody()->getContents(), true);

        if (isset($setPassword['status']) && !$setPassword['status']) {
            throw new ZoomException($setPassword);
        }

        return true;
    }

    /**
     * Visit email box and filter email by subject and to and response last message body.
     * @param string $subject
     * @param string $to
     * @return string|null
     */
    protected function getEmailData($subject, $to): ?string
    {
        /* Connecting server with IMAP */
        $connection = $this->imapClient->connectionOpen(
            $this->config['mailbox'],
            $this->config['account']['email'],
            $this->config['account']['password']
        );

        /* Search Emails having the specified keyword in the email subject */
        $messages = $this->imapClient->messagesSearch($connection, sprintf('SUBJECT "%s" TO "%s"', $subject, $to));
        if (empty($messages)) {
            return null;
        }

        // Get last message.
        $messageId = end($messages);

        // Retrieve message body.
        $messageBody = $this->imapClient->messageBody($connection, $messageId);

        return $messageBody;
    }
}
