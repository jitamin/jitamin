<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Session;

/**
 * Session Storage.
 */
class SessionStorage
{
    /**
     * Pointer to external storage.
     *
     * @var array
     */
    private $storage = [];

    /**
     * Set external storage.
     *
     * @param array $storage External session storage (example: $_SESSION)
     */
    public function setStorage(array &$storage)
    {
        $this->storage = &$storage;

        // Load dynamically existing session variables into object properties
        foreach ($storage as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get all session variables.
     *
     * @return array
     */
    public function getAll()
    {
        $session = get_object_vars($this);
        unset($session['storage']);

        return $session;
    }

    /**
     * Flush session data.
     */
    public function flush()
    {
        $session = get_object_vars($this);
        unset($session['storage']);

        foreach (array_keys($session) as $property) {
            unset($this->$property);
        }
    }

    /**
     * Copy class properties to external storage.
     */
    public function __destruct()
    {
        $this->storage = $this->getAll();
    }
}
