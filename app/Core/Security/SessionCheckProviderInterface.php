<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Security;

/**
 * Session Check Provider Interface.
 */
interface SessionCheckProviderInterface
{
    /**
     * Check if the user session is valid.
     *
     * @return bool
     */
    public function isValidSession();
}
