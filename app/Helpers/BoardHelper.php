<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Helper;

use Hiject\Core\Base;
use Hiject\Model\UserMetadataModel;

/**
 * Board Helper.
 */
class BoardHelper extends Base
{
    /**
     * Return true if tasks are collapsed.
     *
     * @param int $project_id
     *
     * @return bool
     */
    public function isCollapsed($project_id)
    {
        return $this->userMetadataCacheDecorator->get(UserMetadataModel::KEY_BOARD_COLLAPSED.$project_id, 0) == 1;
    }
}
