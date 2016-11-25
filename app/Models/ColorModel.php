<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Model;

use Hiject\Core\Base;

/**
 * Color model
 */
class ColorModel extends Base
{
    /**
     * Default colors
     *
     * @access private
     * @var array
     */
    private $default_colors = [
        'yellow' => [
            'name' => 'Yellow',
            'background' => 'rgb(245, 247, 196)',
            'border' => 'rgb(223, 227, 45)',
        ],
        'blue' => [
            'name' => 'Blue',
            'background' => 'rgb(219, 235, 255)',
            'border' => 'rgb(168, 207, 255)',
        ],
        'green' => [
            'name' => 'Green',
            'background' => 'rgb(189, 244, 203)',
            'border' => 'rgb(74, 227, 113)',
        ],
        'purple' => [
            'name' => 'Purple',
            'background' => 'rgb(223, 176, 255)',
            'border' => 'rgb(205, 133, 254)',
        ],
        'red' => [
            'name' => 'Red',
            'background' => 'rgb(255, 187, 187)',
            'border' => 'rgb(255, 151, 151)',
        ],
        'orange' => [
            'name' => 'Orange',
            'background' => 'rgb(255, 215, 179)',
            'border' => 'rgb(255, 172, 98)',
        ],
        'grey' => [
            'name' => 'Grey',
            'background' => 'rgb(238, 238, 238)',
            'border' => 'rgb(204, 204, 204)',
        ],
        'brown' => [
            'name' => 'Brown',
            'background' => '#d7ccc8',
            'border' => '#4e342e',
        ],
        'deep_orange' => [
            'name' => 'Deep Orange',
            'background' => '#ffab91',
            'border' => '#e64a19',
        ],
        'dark_grey' => [
            'name' => 'Dark Grey',
            'background' => '#cfd8dc',
            'border' => '#455a64',
        ],
        'pink' => [
            'name' => 'Pink',
            'background' => '#f48fb1',
            'border' => '#d81b60',
        ],
        'teal' => [
            'name' => 'Teal',
            'background' => '#80cbc4',
            'border' => '#00695c',
        ],
        'cyan' => [
            'name' => 'Cyan',
            'background' => '#b2ebf2',
            'border' => '#00bcd4',
        ],
        'lime' => [
            'name' => 'Lime',
            'background' => '#e6ee9c',
            'border' => '#afb42b',
        ],
        'light_green' => [
            'name' => 'Light Green',
            'background' => '#dcedc8',
            'border' => '#689f38',
        ],
        'amber' => [
            'name' => 'Amber',
            'background' => '#ffe082',
            'border' => '#ffa000',
        ],
    ];

    /**
     * Find a color id from the name or the id
     *
     * @access public
     * @param  string  $color
     * @return string
     */
    public function find($color)
    {
        $color = strtolower($color);

        foreach ($this->default_colors as $color_id => $params) {
            if ($color_id === $color) {
                return $color_id;
            } elseif ($color === strtolower($params['name'])) {
                return $color_id;
            }
        }

        return '';
    }

    /**
     * Get color properties
     *
     * @access public
     * @param  string  $color_id
     * @return array
     */
    public function getColorProperties($color_id)
    {
        if (isset($this->default_colors[$color_id])) {
            return $this->default_colors[$color_id];
        }

        return $this->default_colors[$this->getDefaultColor()];
    }

    /**
     * Get available colors
     *
     * @access public
     * @param  bool $prepend
     * @return array
     */
    public function getList($prepend = false)
    {
        $listing = $prepend ? ['' => t('All colors')] : [];

        foreach ($this->default_colors as $color_id => $color) {
            $listing[$color_id] = t($color['name']);
        }

        $this->hook->reference('model:color:get-list', $listing);

        return $listing;
    }

    /**
     * Get the default color
     *
     * @access public
     * @return string
     */
    public function getDefaultColor()
    {
        return $this->configModel->get('default_color', 'yellow');
    }

    /**
     * Get the default colors
     *
     * @access public
     * @return array
     */
    public function getDefaultColors()
    {
        return $this->default_colors;
    }

    /**
     * Get border color from string
     *
     * @access public
     * @param  string   $color_id   Color id
     * @return string
     */
    public function getBorderColor($color_id)
    {
        $color = $this->getColorProperties($color_id);
        return $color['border'];
    }

    /**
     * Get background color from the color_id
     *
     * @access public
     * @param  string   $color_id   Color id
     * @return string
     */
    public function getBackgroundColor($color_id)
    {
        $color = $this->getColorProperties($color_id);
        return $color['background'];
    }

    /**
     * Get CSS stylesheet of all colors
     *
     * @access public
     * @return string
     */
    public function getCss()
    {
        $buffer = '';

        foreach ($this->default_colors as $color => $values) {
            $buffer .= 'div.color-'.$color.' {';
            $buffer .= 'background-color: '.$values['background'].';';
            $buffer .= 'border-color: '.$values['border'];
            $buffer .= '}';
            $buffer .= 'td.color-'.$color.' { background-color: '.$values['background'].'}';
        }

        return $buffer;
    }
}
