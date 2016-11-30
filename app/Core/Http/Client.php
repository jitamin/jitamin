<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Http;

use Hiject\Bus\Job\HttpAsyncJob;
use Hiject\Core\Base;

/**
 * HTTP client.
 */
class Client extends Base
{
    /**
     * HTTP connection timeout in seconds.
     *
     * @var int
     */
    const HTTP_TIMEOUT = 5;

    /**
     * Number of maximum redirections for the HTTP client.
     *
     * @var int
     */
    const HTTP_MAX_REDIRECTS = 2;

    /**
     * HTTP client user agent.
     *
     * @var string
     */
    const HTTP_USER_AGENT = 'Hiject';

    /**
     * Send a GET HTTP request.
     *
     * @param string   $url
     * @param string[] $headers
     *
     * @return string
     */
    public function get($url, array $headers = [])
    {
        return $this->doRequest('GET', $url, '', $headers);
    }

    /**
     * Send a GET HTTP request and parse JSON response.
     *
     * @param string   $url
     * @param string[] $headers
     *
     * @return array
     */
    public function getJson($url, array $headers = [])
    {
        $response = $this->doRequest('GET', $url, '', array_merge(['Accept: application/json'], $headers));

        return json_decode($response, true) ?: [];
    }

    /**
     * Send a POST HTTP request encoded in JSON.
     *
     * @param string   $url
     * @param array    $data
     * @param string[] $headers
     *
     * @return string
     */
    public function postJson($url, array $data, array $headers = [])
    {
        return $this->doRequest(
            'POST',
            $url,
            json_encode($data),
            array_merge(['Content-type: application/json'], $headers)
        );
    }

    /**
     * Send a POST HTTP request encoded in JSON (Fire and forget).
     *
     * @param string   $url
     * @param array    $data
     * @param string[] $headers
     */
    public function postJsonAsync($url, array $data, array $headers = [])
    {
        $this->queueManager->push(HttpAsyncJob::getInstance($this->container)->withParams(
            'POST',
            $url,
            json_encode($data),
            array_merge(['Content-type: application/json'], $headers)
        ));
    }

    /**
     * Send a POST HTTP request encoded in www-form-urlencoded.
     *
     * @param string   $url
     * @param array    $data
     * @param string[] $headers
     *
     * @return string
     */
    public function postForm($url, array $data, array $headers = [])
    {
        return $this->doRequest(
            'POST',
            $url,
            http_build_query($data),
            array_merge(['Content-type: application/x-www-form-urlencoded'], $headers)
        );
    }

    /**
     * Send a POST HTTP request encoded in www-form-urlencoded (fire and forget).
     *
     * @param string   $url
     * @param array    $data
     * @param string[] $headers
     */
    public function postFormAsync($url, array $data, array $headers = [])
    {
        $this->queueManager->push(HttpAsyncJob::getInstance($this->container)->withParams(
            'POST',
            $url,
            http_build_query($data),
            array_merge(['Content-type: application/x-www-form-urlencoded'], $headers)
        ));
    }

    /**
     * Make the HTTP request.
     *
     * @param string   $method
     * @param string   $url
     * @param string   $content
     * @param string[] $headers
     *
     * @return string
     */
    public function doRequest($method, $url, $content, array $headers)
    {
        if (empty($url)) {
            return '';
        }

        $startTime = microtime(true);
        $stream = @fopen(trim($url), 'r', false, stream_context_create($this->getContext($method, $content, $headers)));
        $response = '';

        if (is_resource($stream)) {
            $response = stream_get_contents($stream);
        } else {
            $this->logger->error('HttpClient: request failed');
        }

        if (DEBUG) {
            $this->logger->debug('HttpClient: url='.$url);
            $this->logger->debug('HttpClient: headers='.var_export($headers, true));
            $this->logger->debug('HttpClient: payload='.$content);
            $this->logger->debug('HttpClient: metadata='.var_export(@stream_get_meta_data($stream), true));
            $this->logger->debug('HttpClient: response='.$response);
            $this->logger->debug('HttpClient: executionTime='.(microtime(true) - $startTime));
        }

        return $response;
    }

    /**
     * Get stream context.
     *
     * @param string   $method
     * @param string   $content
     * @param string[] $headers
     *
     * @return array
     */
    private function getContext($method, $content, array $headers)
    {
        $default_headers = [
            'User-Agent: '.self::HTTP_USER_AGENT,
            'Connection: close',
        ];

        if (HTTP_PROXY_USERNAME) {
            $default_headers[] = 'Proxy-Authorization: Basic '.base64_encode(HTTP_PROXY_USERNAME.':'.HTTP_PROXY_PASSWORD);
        }

        $headers = array_merge($default_headers, $headers);

        $context = [
            'http' => [
                'method'           => $method,
                'protocol_version' => 1.1,
                'timeout'          => self::HTTP_TIMEOUT,
                'max_redirects'    => self::HTTP_MAX_REDIRECTS,
                'header'           => implode("\r\n", $headers),
                'content'          => $content,
            ],
        ];

        if (HTTP_PROXY_HOSTNAME) {
            $context['http']['proxy'] = 'tcp://'.HTTP_PROXY_HOSTNAME.':'.HTTP_PROXY_PORT;
            $context['http']['request_fulluri'] = true;
        }

        if (HTTP_VERIFY_SSL_CERTIFICATE === false) {
            $context['ssl'] = [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ];
        }

        return $context;
    }
}
