<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Identity;

use Jitamin\Core\Base;

/**
 * User Synchronization.
 */
class UserSync extends Base
{
    /**
     * Synchronize user profile.
     *
     * @param UserProviderInterface $user
     *
     * @return array
     */
    public function synchronize(UserProviderInterface $user)
    {
        $profile = $this->userModel->getByExternalId($user->getExternalIdColumn(), $user->getExternalId());
        $properties = UserProperty::getProperties($user);

        if (!empty($profile)) {
            $profile = $this->updateUser($profile, $properties);
        } elseif ($user->isUserCreationAllowed()) {
            $profile = $this->createUser($user, $properties);
        }

        return $profile;
    }

    /**
     * Update user profile.
     *
     * @param array $profile
     * @param array $properties
     *
     * @return array
     */
    private function updateUser(array $profile, array $properties)
    {
        $values = UserProperty::filterProperties($profile, $properties);

        if (!empty($values)) {
            $values['id'] = $profile['id'];
            $result = $this->userModel->update($values);

            return $result ? array_merge($profile, $properties) : $profile;
        }

        return $profile;
    }

    /**
     * Create user.
     *
     * @param UserProviderInterface $user
     * @param array                 $properties
     *
     * @return array
     */
    private function createUser(UserProviderInterface $user, array $properties)
    {
        $userId = $this->userModel->create($properties);

        if ($userId === false) {
            $this->logger->error('Unable to create user profile: '.$user->getExternalId());

            return [];
        }

        return $this->userModel->getById($userId);
    }
}
