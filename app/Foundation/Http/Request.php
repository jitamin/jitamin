<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Http;

use Jitamin\Core\Base;
use Jitamin\Core\Controller\AccessForbiddenException;
use Pimple\Container;

/**
 * Request class.
 */
class Request extends Base
{
    /**
     * Pointer to PHP environment variables.
     *
     * @var array
     */
    private $server;
    private $get;
    private $post;
    private $files;
    private $cookies;

    /**
     * Constructor.
     *
     * @param \Pimple\Container $container
     * @param array             $server
     * @param array             $get
     * @param array             $post
     * @param array             $files
     * @param array             $cookies
     */
    public function __construct(Container $container, array $server = [], array $get = [], array $post = [], array $files = [], array $cookies = [])
    {
        parent::__construct($container);
        $this->server = empty($server) ? $_SERVER : $server;
        $this->get = empty($get) ? $_GET : $get;
        $this->post = empty($post) ? $_POST : $post;
        $this->files = empty($files) ? $_FILES : $files;
        $this->cookies = empty($cookies) ? $_COOKIE : $cookies;
    }

    /**
     * Set GET parameters.
     *
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->get = array_merge($this->get, $params);
    }

    /**
     * Get query string string parameter.
     *
     * @param string $name          Parameter name
     * @param string $default_value Default value
     *
     * @return string
     */
    public function getStringParam($name, $default_value = '')
    {
        return isset($this->get[$name]) ? $this->get[$name] : $default_value;
    }

    /**
     * Get query string integer parameter.
     *
     * @param string $name          Parameter name
     * @param int    $default_value Default value
     *
     * @return int
     */
    public function getIntegerParam($name, $default_value = 0)
    {
        return isset($this->get[$name]) && ctype_digit($this->get[$name]) ? (int) $this->get[$name] : $default_value;
    }

    /**
     * Get a form value.
     *
     * @param string $name Form field name
     *
     * @return string|null
     */
    public function getValue($name)
    {
        $values = $this->getValues();

        return isset($values[$name]) ? $values[$name] : null;
    }

    /**
     * Get form values and check for CSRF token.
     *
     * @return array
     */
    public function getValues()
    {
        if ($this->checkCSRFParam()) {
            return $this->post;
        }

        return [];
    }

    /**
     * Check for CSRF token.
     *
     * @return void
     */
    public function checkCSRFToken()
    {
        if (!$this->checkCSRFParam()) {
            throw new AccessForbiddenException();
        }
    }

    /**
     * Get the raw body of the HTTP request.
     *
     * @return string
     */
    public function getBody()
    {
        return file_get_contents('php://input');
    }

    /**
     * Get the Json request body.
     *
     * @return array
     */
    public function getJson()
    {
        return json_decode($this->getBody(), true) ?: [];
    }

    /**
     * Get the content of an uploaded file.
     *
     * @param string $name Form file name
     *
     * @return string
     */
    public function getFileContent($name)
    {
        if (isset($this->files[$name]['tmp_name'])) {
            return file_get_contents($this->files[$name]['tmp_name']);
        }

        return '';
    }

    /**
     * Get the path of an uploaded file.
     *
     * @param string $name Form file name
     *
     * @return string
     */
    public function getFilePath($name)
    {
        return isset($this->files[$name]['tmp_name']) ? $this->files[$name]['tmp_name'] : '';
    }

    /**
     * Get info of an uploaded file.
     *
     * @param string $name Form file name
     *
     * @return array
     */
    public function getFileInfo($name)
    {
        return isset($this->files[$name]) ? $this->files[$name] : [];
    }

    /**
     * Return HTTP method.
     *
     * @return bool
     */
    public function getMethod()
    {
        return $this->getServerVariable('REQUEST_METHOD');
    }

    /**
     * Return true if the HTTP request is sent with the POST method.
     *
     * @return bool
     */
    public function isPost()
    {
        return $this->getServerVariable('REQUEST_METHOD') === 'POST';
    }

    /**
     * Return true if the HTTP request is an Ajax request.
     *
     * @return bool
     */
    public function isAjax()
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * Check if the page is requested through HTTPS.
     *
     * Note: IIS return the value 'off' and other web servers an empty value when it's not HTTPS
     *
     * @return bool
     */
    public function isHTTPS()
    {
        if ($this->getServerVariable('HTTP_X_FORWARDED_PROTO') === 'https') {
            return true;
        }

        return $this->getServerVariable('HTTPS') !== '' && $this->server['HTTPS'] !== 'off';
    }

    /**
     * Get cookie value.
     *
     * @param string $name
     *
     * @return string
     */
    public function getCookie($name)
    {
        return isset($this->cookies[$name]) ? $this->cookies[$name] : '';
    }

    /**
     * Return a HTTP header value.
     *
     * @param string $name Header name
     *
     * @return string
     */
    public function getHeader($name)
    {
        $name = 'HTTP_'.str_replace('-', '_', strtoupper($name));

        return $this->getServerVariable($name);
    }

    /**
     * Get remote user.
     *
     * @return string
     */
    public function getRemoteUser()
    {
        return $this->getServerVariable(REVERSE_PROXY_USER_HEADER);
    }

    /**
     * Returns query string.
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->getServerVariable('QUERY_STRING');
    }

    /**
     * Return URI.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->getServerVariable('REQUEST_URI');
    }

    /**
     * Get the user agent.
     *
     * @return string
     */
    public function getUserAgent()
    {
        return empty($this->server['HTTP_USER_AGENT']) ? t('Unknown') : $this->server['HTTP_USER_AGENT'];
    }

    /**
     * Get the IP address of the user.
     *
     * @return string
     */
    public function getIpAddress()
    {
        $keys = [
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($keys as $key) {
            if ($this->getServerVariable($key) !== '') {
                foreach (explode(',', $this->server[$key]) as $ipAddress) {
                    return trim($ipAddress);
                }
            }
        }

        return t('Unknown');
    }

    /**
     * Get start time.
     *
     * @return float
     */
    public function getStartTime()
    {
        return $this->getServerVariable('REQUEST_TIME_FLOAT') ?: 0;
    }

    /**
     * Get server variable.
     *
     * @param string $variable
     *
     * @return string
     */
    public function getServerVariable($variable)
    {
        return isset($this->server[$variable]) ? $this->server[$variable] : '';
    }

    /**
     * Check if the CSRF token from the URL is correct.
     */
    protected function checkCSRFParam()
    {
        if (!empty($this->post) && !empty($this->post['csrf_token']) && $this->token->validateCSRFToken($this->post['csrf_token'])) {
            unset($this->post['csrf_token']);

            return true;
        }

        return false;
    }
}
