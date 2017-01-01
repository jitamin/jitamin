<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Helper;

use Jitamin\Core\Base;

/**
 * Asset Helper.
 */
class AssetHelper extends Base
{
    /**
     * Add a Javascript asset.
     *
     * @param string $filename Filename
     * @param bool   $async
     *
     * @return string
     */
    public function js($filename, $async = false)
    {
        return '<script '.($async ? 'async' : '').' type="text/javascript" src="'.$this->helper->url->dir().$filename.'?'.filemtime($filename).'"></script>';
    }

    /**
     * Add a stylesheet asset.
     *
     * @param string $filename Filename
     * @param bool   $is_file  Add file timestamp
     * @param string $media    Media
     *
     * @return string
     */
    public function css($filename, $is_file = true, $media = 'screen')
    {
        return '<link rel="stylesheet" href="'.$this->helper->url->dir().$filename.($is_file ? '?'.filemtime($filename) : '').'" media="'.$media.'">';
    }

    /**
     * Get custom css.
     *
     * @return string
     */
    public function customCss()
    {
        if ($this->settingModel->get('application_stylesheet')) {
            return '<style>'.$this->settingModel->get('application_stylesheet').'</style>';
        }

        return '';
    }

    /**
     * Get CSS for task colors.
     *
     * @return string
     */
    public function colorCss()
    {
        return '<style>'.$this->colorModel->getCss().'</style>';
    }
}
