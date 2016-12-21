<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Helper;

use Jitamin\Core\Base;

/**
 * Url Helper.
 */
class UrlHelper extends Base
{
    private $base = '';
    private $directory = '';

    /**
     * Helper to generate a link to the documentation.
     *
     * @param string $label
     * @param string $file
     *
     * @return string
     */
    public function doc($label, $file)
    {
        return $this->link($label, 'DocumentationController', 'show', ['file' => $file], false, '', '', true);
    }

    /**
     * Button Link Element.
     *
     * @param string $icon       Font-Awesome icon
     * @param string $label      Link label
     * @param string $controller Controller name
     * @param string $action     Action name
     * @param array  $params     Url parameters
     * @param string $class      CSS class attribute
     *
     * @return string
     */
    public function button($icon, $label, $controller, $action, array $params = [], $class = '')
    {
        $icon = '<i class="fa '.$icon.' fa-fw"></i> ';
        $class = 'btn '.$class;

        return $this->link($icon.$label, $controller, $action, $params, false, $class);
    }

    /**
     * Link element.
     *
     * @param string $label      Link label
     * @param string $controller Controller name
     * @param string $action     Action name
     * @param array  $params     Url parameters
     * @param bool   $csrf       Add a CSRF token
     * @param string $class      CSS class attribute
     * @param string $title      Link title
     * @param bool   $new_tab    Open the link in a new tab
     * @param string $anchor     Link Anchor
     * @param bool   $absolute
     *
     * @return string
     */
    public function link($label, $controller, $action, array $params = [], $csrf = false, $class = '', $title = '', $new_tab = false, $anchor = '', $absolute = false)
    {
        return '<a href="'.$this->href($controller, $action, $params, $csrf, $anchor, $absolute).'" class="'.$class.'" title=\''.$title.'\' '.($new_tab ? 'target="_blank"' : '').'>'.$label.'</a>';
    }

    /**
     * Absolute link.
     *
     * @param string $label
     * @param string $controller
     * @param string $action
     * @param array  $params
     *
     * @return string
     */
    public function absoluteLink($label, $controller, $action, array $params = [])
    {
        return $this->link($label, $controller, $action, $params, false, '', '', true, '', true);
    }

    /**
     * HTML Hyperlink.
     *
     * @param string $controller Controller name
     * @param string $action     Action name
     * @param array  $params     Url parameters
     * @param bool   $csrf       Add a CSRF token
     * @param string $anchor     Link Anchor
     * @param bool   $absolute   Absolute or relative link
     *
     * @return string
     */
    public function href($controller, $action, array $params = [], $csrf = false, $anchor = '', $absolute = false)
    {
        if (isset($params['q']) && $params['q'] === 'status:open') {
            unset($params['q']);
        }

        return $this->build('&amp;', $controller, $action, $params, $csrf, $anchor, $absolute);
    }

    /**
     * Generate controller/action url.
     *
     * @param string $controller Controller name
     * @param string $action     Action name
     * @param array  $params     Url parameters
     * @param string $anchor     Link Anchor
     * @param bool   $absolute   Absolute or relative link
     *
     * @return string
     */
    public function to($controller, $action, array $params = [], $anchor = '', $absolute = false)
    {
        return $this->build('&', $controller, $action, $params, false, $anchor, $absolute);
    }

    /**
     * Get application base url.
     *
     * @return string
     */
    public function base()
    {
        if (empty($this->base)) {
            $this->base = $this->settingModel->get('application_url') ?: $this->server();
        }

        return $this->base;
    }

    /**
     * Get application base directory.
     *
     * @return string
     */
    public function dir()
    {
        if ($this->directory === '' && $this->request->getMethod() !== '') {
            $this->directory = str_replace('\\', '/', dirname($this->request->getServerVariable('PHP_SELF')));
            $this->directory = $this->directory !== '/' ? $this->directory.'/' : '/';
            $this->directory = str_replace('//', '/', $this->directory);
        }

        return $this->directory;
    }

    /**
     * Get current server base url.
     *
     * @return string
     */
    public function server()
    {
        if ($this->request->getServerVariable('SERVER_NAME') === '') {
            return 'http://localhost/';
        }

        $url = $this->request->isHTTPS() ? 'https://' : 'http://';
        $url .= $this->request->getServerVariable('SERVER_NAME');
        $url .= $this->request->getServerVariable('SERVER_PORT') == 80 || $this->request->getServerVariable('SERVER_PORT') == 443 ? '' : ':'.$this->request->getServerVariable('SERVER_PORT');
        $url .= $this->dir() ?: '/';

        return $url;
    }

    /**
     * Build relative url.
     *
     * @param string $separator  Querystring argument separator
     * @param string $controller Controller name
     * @param string $action     Action name
     * @param array  $params     Url parameters
     * @param bool   $csrf       Add a CSRF token
     * @param string $anchor     Link Anchor
     * @param bool   $absolute   Absolute or relative link
     *
     * @return string
     */
    protected function build($separator, $controller, $action, array $params = [], $csrf = false, $anchor = '', $absolute = false)
    {
        $path = $this->route->findUrl($controller, $action, $params);
        $qs = [];

        if (empty($path)) {
            $qs['controller'] = $controller;
            $qs['action'] = $action;
            $qs += $params;
        } else {
            unset($params['plugin']);
        }

        if ($csrf) {
            $qs['csrf_token'] = $this->token->getCSRFToken();
        }

        if (!empty($qs)) {
            $path .= '?'.http_build_query($qs, '', $separator);
        }

        return ($absolute ? $this->base() : $this->dir()).$path.(empty($anchor) ? '' : '#'.$anchor);
    }
}
