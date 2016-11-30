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

use Hiject\Core\Base;
use Hiject\Core\Csv;

/**
 * Response class.
 */
class Response extends Base
{
    private $httpStatusCode = 200;
    private $httpHeaders = [];
    private $httpBody = '';
    private $responseSent = false;

    /**
     * Return true if the response have been sent to the user agent.
     *
     * @return bool
     */
    public function isResponseAlreadySent()
    {
        return $this->responseSent;
    }

    /**
     * Set HTTP status code.
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function withStatusCode($statusCode)
    {
        $this->httpStatusCode = $statusCode;

        return $this;
    }

    /**
     * Set HTTP header.
     *
     * @param string $header
     * @param string $value
     *
     * @return $this
     */
    public function withHeader($header, $value)
    {
        $this->httpHeaders[$header] = $value;

        return $this;
    }

    /**
     * Set content type header.
     *
     * @param string $value
     *
     * @return $this
     */
    public function withContentType($value)
    {
        $this->httpHeaders['Content-Type'] = $value;

        return $this;
    }

    /**
     * Set default security headers.
     *
     * @return $this
     */
    public function withSecurityHeaders()
    {
        $this->httpHeaders['X-Content-Type-Options'] = 'nosniff';
        $this->httpHeaders['X-XSS-Protection'] = '1; mode=block';

        return $this;
    }

    /**
     * Set header Content-Security-Policy.
     *
     * @param array $policies
     *
     * @return $this
     */
    public function withContentSecurityPolicy(array $policies = [])
    {
        $values = '';

        foreach ($policies as $policy => $acl) {
            $values .= $policy.' '.trim($acl).'; ';
        }

        $this->withHeader('Content-Security-Policy', $values);

        return $this;
    }

    /**
     * Set header X-Frame-Options.
     *
     * @return $this
     */
    public function withXframe()
    {
        $this->withHeader('X-Frame-Options', 'DENY');

        return $this;
    }

    /**
     * Set header Strict-Transport-Security (only if we use HTTPS).
     *
     * @return $this
     */
    public function withStrictTransportSecurity()
    {
        if ($this->request->isHTTPS()) {
            $this->withHeader('Strict-Transport-Security', 'max-age=31536000');
        }

        return $this;
    }

    /**
     * Set HTTP response body.
     *
     * @param string $body
     *
     * @return $this
     */
    public function withBody($body)
    {
        $this->httpBody = $body;

        return $this;
    }

    /**
     * Send headers to cache a resource.
     *
     * @param int    $duration
     * @param string $etag
     *
     * @return $this
     */
    public function withCache($duration, $etag = '')
    {
        $this
            ->withHeader('Pragma', 'cache')
            ->withHeader('Expires', gmdate('D, d M Y H:i:s', time() + $duration).' GMT')
            ->withHeader('Cache-Control', 'public, max-age='.$duration);

        if ($etag) {
            $this->withHeader('ETag', '"'.$etag.'"');
        }

        return $this;
    }

    /**
     * Send no cache headers.
     *
     * @return $this
     */
    public function withoutCache()
    {
        $this->withHeader('Pragma', 'no-cache');
        $this->withHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');

        return $this;
    }

    /**
     * Force the browser to download an attachment.
     *
     * @param string $filename
     *
     * @return $this
     */
    public function withFileDownload($filename)
    {
        $this->withHeader('Content-Disposition', 'attachment; filename="'.$filename.'"');
        $this->withHeader('Content-Transfer-Encoding', 'binary');
        $this->withHeader('Content-Type', 'application/octet-stream');

        return $this;
    }

    /**
     * Send headers and body.
     */
    public function send()
    {
        $this->responseSent = true;

        if ($this->httpStatusCode !== 200) {
            header('Status: '.$this->httpStatusCode);
            header($this->request->getServerVariable('SERVER_PROTOCOL').' '.$this->httpStatusCode);
        }

        foreach ($this->httpHeaders as $header => $value) {
            header($header.': '.$value);
        }

        if (!empty($this->httpBody)) {
            echo $this->httpBody;
        }
    }

    /**
     * Send a custom HTTP status code.
     *
     * @param int $statusCode
     */
    public function status($statusCode)
    {
        $this->withStatusCode($statusCode);
        $this->send();
    }

    /**
     * Redirect to another URL.
     *
     * @param string $url  Redirection URL
     * @param bool   $self If Ajax request and true: refresh the current page
     */
    public function redirect($url, $self = false)
    {
        if ($this->request->isAjax()) {
            $this->withHeader('X-Ajax-Redirect', $self ? 'self' : $url);
        } else {
            $this->withHeader('Location', $url);
        }

        $this->send();
    }

    /**
     * Send a HTML response.
     *
     * @param string $data
     * @param int    $statusCode
     */
    public function html($data, $statusCode = 200)
    {
        $this->withStatusCode($statusCode);
        $this->withContentType('text/html; charset=utf-8');
        $this->withBody($data);
        $this->send();
    }

    /**
     * Send a text response.
     *
     * @param string $data
     * @param int    $statusCode
     */
    public function text($data, $statusCode = 200)
    {
        $this->withStatusCode($statusCode);
        $this->withContentType('text/plain; charset=utf-8');
        $this->withBody($data);
        $this->send();
    }

    /**
     * Send a CSV response.
     *
     * @param array $data Data to serialize in csv
     */
    public function csv(array $data)
    {
        $this->withoutCache();
        $this->withContentType('text/csv; charset=utf-8');
        $this->send();
        Csv::output($data);
    }

    /**
     * Send a Json response.
     *
     * @param array $data       Data to serialize in json
     * @param int   $statusCode HTTP status code
     */
    public function json(array $data, $statusCode = 200)
    {
        $this->withStatusCode($statusCode);
        $this->withContentType('application/json');
        $this->withoutCache();
        $this->withBody(json_encode($data));
        $this->send();
    }

    /**
     * Send a XML response.
     *
     * @param string $data
     * @param int    $statusCode
     */
    public function xml($data, $statusCode = 200)
    {
        $this->withStatusCode($statusCode);
        $this->withContentType('text/xml; charset=utf-8');
        $this->withoutCache();
        $this->withBody($data);
        $this->send();
    }

    /**
     * Send a javascript response.
     *
     * @param string $data
     * @param int    $statusCode
     */
    public function js($data, $statusCode = 200)
    {
        $this->withStatusCode($statusCode);
        $this->withContentType('text/javascript; charset=utf-8');
        $this->withBody($data);
        $this->send();
    }

    /**
     * Send a css response.
     *
     * @param string $data
     * @param int    $statusCode
     */
    public function css($data, $statusCode = 200)
    {
        $this->withStatusCode($statusCode);
        $this->withContentType('text/css; charset=utf-8');
        $this->withBody($data);
        $this->send();
    }

    /**
     * Send a binary response.
     *
     * @param string $data
     * @param int    $statusCode
     */
    public function binary($data, $statusCode = 200)
    {
        $this->withStatusCode($statusCode);
        $this->withoutCache();
        $this->withHeader('Content-Transfer-Encoding', 'binary');
        $this->withContentType('application/octet-stream');
        $this->withBody($data);
        $this->send();
    }

    /**
     * Send a iCal response.
     *
     * @param string $data
     * @param int    $statusCode
     */
    public function ical($data, $statusCode = 200)
    {
        $this->withStatusCode($statusCode);
        $this->withContentType('text/calendar; charset=utf-8');
        $this->withBody($data);
        $this->send();
    }
}
