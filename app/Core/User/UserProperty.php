<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\User;

/**
 * User Property.
 */
class UserProperty
{
    /**
     * Get filtered user properties from user provider.
     *
     * @static
     *
     * @param UserProviderInterface $user
     *
     * @return array
     */
    public static function getProperties(UserProviderInterface $user)
    {
        $properties = [
            'username'                   => $user->getUsername(),
            'name'                       => $user->getName(),
            'email'                      => $user->getEmail(),
            'role'                       => $user->getRole(),
            $user->getExternalIdColumn() => $user->getExternalId(),
        ];

        $properties = array_merge($properties, $user->getExtraAttributes());

        return array_filter($properties, [__NAMESPACE__.'\UserProperty', 'isNotEmptyValue']);
    }

    /**
     * Filter user properties compared to existing user profile.
     *
     * @static
     *
     * @param array $profile
     * @param array $properties
     *
     * @return array
     */
    public static function filterProperties(array $profile, array $properties)
    {
        $excludedProperties = ['username'];
        $values = [];

        foreach ($properties as $property => $value) {
            if (self::isNotEmptyValue($value) &&
                !in_array($property, $excludedProperties) &&
                array_key_exists($property, $profile) &&
                $value !== $profile[$property]) {
                $values[$property] = $value;
            }
        }

        return $values;
    }

    /**
     * Check if a value is not empty.
     *
     * @static
     *
     * @param string $value
     *
     * @return bool
     */
    public static function isNotEmptyValue($value)
    {
        return $value !== null && $value !== '';
    }
}
