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

use Gregwar\Captcha\CaptchaBuilder;

/**
 * Captcha Controller.
 */
class CaptchaController extends BaseController
{
    /**
     * Display captcha image.
     */
    public function image()
    {
        $this->response->withContentType('image/jpeg')->send();

        $builder = new CaptchaBuilder();
        $builder->build();
        $this->sessionStorage->captcha = $builder->getPhrase();
        $builder->output();
    }
}
