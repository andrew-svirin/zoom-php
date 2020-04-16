<?php

namespace AndrewSvirin\Zoom;

use AndrewSvirin\Zoom\Contracts\ZoomAPIClientInterface;
use AndrewSvirin\Zoom\Exceptions\ZoomException;
use AndrewSvirin\Zoom\Models\JsonResponse;
use AndrewSvirin\Zoom\Requests\Request;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * ZOOM API client representation.
 * Interact with Zoom through REST API.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
final class ZoomAPIClient implements ZoomAPIClientInterface
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var array [
     *  'url' => 'https://api.zoom.us',
     *  'jwt' => [
     *      'api_key' => '',
     *      'api_secret' => '',
     *   ],
     *  ]
     */
    protected $config;

    public function __construct($httpClient, $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     * @throws Exceptions\ZoomException
     */
    public function call(Request $request)
    {
        $url = $this->config['url'] . '/' . $request->getURI();
        if (($query = http_build_query($request->getParameters()))) {
            $url .= '?' . $query;
        }
        $options = [
            'http_errors' => true,
            'decode_content' => true,
            'verify' => false,
            'cookies' => false,
            'headers' => [
                'User-Agent' => 'Zoom-Jwt-Request',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->generateJWTKey(),
            ],
        ];
        if ($request->hasJson()) {
            $options['json'] = $request->getJson();
        }
        // Add Json handler
        /** @var \GuzzleHttp\HandlerStack $handler */
        $handler = $this->httpClient->getConfig('handler');
        $handler->push(\GuzzleHttp\Middleware::mapResponse(
            function (ResponseInterface $response) use ($handler) {
                $response = new JsonResponse(
                    $response->getStatusCode(),
                    $response->getHeaders(),
                    $response->getBody(),
                    $response->getProtocolVersion(),
                    $response->getReasonPhrase()
                );
                $handler->remove('json_decode_chain_middleware');
                return $response;
            }
        ), 'json_decode_chain_middleware');
        try {
            $response = $this->httpClient->request($request->getMethod(), $url, $options);
        } catch (ClientException $exception) {
            $json = $exception->getResponse()->getJson();
            throw new ZoomException($json);
        }
        return $response;
    }

    /**
     * Generate authorization JWT access token.
     * @return string
     */
    protected function generateJWTKey()
    {
        // Create the token header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256',
        ]);

        // Create the token payload
        $payload = json_encode([
            'iss' => $this->config['jwt']['api_key'],
            'exp' => time() + 3600 // 60 seconds as suggested
        ]);

        // Encode Header
        $base64UrlHeader = $this->base64UrlEncode($header);

        // Encode Payload
        $base64UrlPayload = $this->base64UrlEncode($payload);

        // Create Signature Hash
        $signature = hash_hmac(
            'sha256',
            $base64UrlHeader . '.' . $base64UrlPayload, $this->config['jwt']['api_secret'],
            true
        );

        // Encode Signature to Base64Url String
        $base64UrlSignature = $this->base64UrlEncode($signature);

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }

    private function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }
}
