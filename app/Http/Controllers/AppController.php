<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

use Hiject\Core\Base;

/**
 * Class AppController
 */
class AppController extends Base
{
    /**
     * Forbidden page
     *
     * @access public
     * @param  bool   $withoutLayout
     * @param  string $message
     */
    public function accessForbidden($withoutLayout = false, $message = '')
    {
        if ($this->request->isAjax()) {
            $this->response->json(array('message' => $message ?: t('Access Forbidden')), 403);
        } else {
            $this->response->html($this->helper->layout->app('app/forbidden', array(
                'title' => t('Access Forbidden'),
                'no_layout' => $withoutLayout,
            )));
        }
    }

    /**
     * Page not found
     *
     * @access public
     * @param  boolean $withoutLayout
     */
    public function notFound($withoutLayout = false)
    {
        $this->response->html($this->helper->layout->app('app/notfound', array(
            'title' => t('Page not found'),
            'no_layout' => $withoutLayout,
        )));
    }
}
