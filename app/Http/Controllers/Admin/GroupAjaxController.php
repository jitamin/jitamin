<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Admin;

use Jitamin\Controller\BaseController;
use Jitamin\Formatter\GroupAutoCompleteFormatter;

/**
 * Group Ajax Controller.
 */
class GroupAjaxController extends BaseController
{
    /**
     * Group auto-completion (Ajax).
     */
    public function autocomplete()
    {
        $search = $this->request->getStringParam('term');
        $formatter = new GroupAutoCompleteFormatter($this->groupManager->find($search));
        $this->response->json($formatter->format());
    }
}
