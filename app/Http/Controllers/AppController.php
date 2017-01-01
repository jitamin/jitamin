<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller;

use Jitamin\Core\Base;

/**
 * Class AppController.
 */
class AppController extends Base
{
    /**
     * Forbidden page.
     *
     * @param bool   $withoutLayout
     * @param string $message
     */
    public function accessForbidden($withoutLayout = false, $message = '')
    {
        if ($this->request->isAjax()) {
            $this->response->json(['message' => $message ?: t('Access Forbidden')], 403);
        } else {
            $this->response->html($this->helper->layout->app('errors/403', [
                'title'     => t('Access Forbidden'),
                'no_layout' => $withoutLayout,
            ]));
        }
    }

    /**
     * Page not found.
     *
     * @param bool $withoutLayout
     */
    public function notFound($withoutLayout = false)
    {
        $this->response->html($this->helper->layout->error('errors/404', [
            'title'     => t('Page not found'),
            'no_layout' => $withoutLayout,
        ]));
    }
}
