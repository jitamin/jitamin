<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\ExternalLink;

/**
 * External Link Provider Interface
 */
interface ExternalLinkProviderInterface
{
    /**
     * Get provider name (label)
     *
     * @access public
     * @return string
     */
    public function getName();

    /**
     * Get link type (will be saved in the database)
     *
     * @access public
     * @return string
     */
    public function getType();

    /**
     * Get a dictionary of supported dependency types by the provider
     *
     * Example:
     *
     * [
     *     'related' => t('Related'),
     *     'child' => t('Child'),
     *     'parent' => t('Parent'),
     *     'self' => t('Self'),
     * ]
     *
     * The dictionary key is saved in the database.
     *
     * @access public
     * @return array
     */
    public function getDependencies();

    /**
     * Set text entered by the user
     *
     * @access public
     * @param  string $input
     */
    public function setUserTextInput($input);

    /**
     * Return true if the provider can parse correctly the user input
     *
     * @access public
     * @return boolean
     */
    public function match();

    /**
     * Get the link found with the properties
     *
     * @access public
     * @return ExternalLinkInterface
     */
    public function getLink();
}
