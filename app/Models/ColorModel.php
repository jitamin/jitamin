<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Foundation\Database\Model;

/**
 * Color model.
 */
class ColorModel extends Model
{
    /**
     * Default colors.
     *
     * @var array
     */
    private $default_colors = [
        'white' => [
            'name'              => 'White',
            'border-left-color' => '#cccccc',
            'border-width'      => '3px',
        ],
        'yellow' => [
            'name'              => 'Yellow',
            'border-left-color' => '#F5F7C4',
            'border-width'      => '3px',
        ],
        'blue' => [
            'name'              => 'Blue',
            'border-left-color' => '#DBEBFF',
            'border-width'      => '3px',
        ],
        'green' => [
            'name'              => 'Green',
            'border-left-color' => '#BDF4CB',
            'border-width'      => '3px',
        ],
        'purple' => [
            'name'              => 'Purple',
            'border-left-color' => '#DFB0FF',
            'border-width'      => '3px',
        ],
        'red' => [
            'name'              => 'Red',
            'border-left-color' => '#FFBBBB',
            'border-width'      => '3px',
        ],
        'orange' => [
            'name'              => 'Orange',
            'border-left-color' => '#FFD7B3',
            'border-width'      => '3px',
        ],
        'grey' => [
            'name'              => 'Grey',
            'border-left-color' => '#EEEEEE',
            'border-width'      => '3px',
        ],
        'brown' => [
            'name'              => 'Brown',
            'border-left-color' => '#D7CCC8',
            'border-width'      => '3px',
        ],
        'deep_orange' => [
            'name'              => 'Deep Orange',
            'border-left-color' => '#FFAB91',
            'border-width'      => '3px',
        ],
        'dark_grey' => [
            'name'              => 'Dark Grey',
            'border-left-color' => '#CFD8DC',
            'border-width'      => '3px',
        ],
        'pink' => [
            'name'              => 'Pink',
            'border-left-color' => '#F48FB1',
            'border-width'      => '3px',
        ],
        'teal' => [
            'name'              => 'Teal',
            'border-left-color' => '#80CBC4',
            'border-width'      => '3px',
        ],
        'cyan' => [
            'name'              => 'Cyan',
            'border-left-color' => '#B2EBF2',
            'border-width'      => '3px',
        ],
        'lime' => [
            'name'              => 'Lime',
            'border-left-color' => '#E6EE9C',
            'border-width'      => '3px',
        ],
        'light_green' => [
            'name'              => 'Light Green',
            'border-left-color' => '#DCEDC8',
            'border-width'      => '3px',
        ],
        'amber' => [
            'name'              => 'Amber',
            'border-left-color' => '#FFE082',
            'border-width'      => '3px',
        ],
    ];

    /**
     * Find a color id from the name or the id.
     *
     * @param string $color
     *
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
     * Get color properties.
     *
     * @param string $color_id
     *
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
     * Get available colors.
     *
     * @param bool $prepend
     *
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
     * Get the default color.
     *
     * @return string
     */
    public function getDefaultColor()
    {
        return $this->settingModel->get('default_color', 'yellow');
    }

    /**
     * Get the default colors.
     *
     * @return array
     */
    public function getDefaultColors()
    {
        return $this->default_colors;
    }

    /**
     * Get border color from string.
     *
     * @param string $color_id Color id
     *
     * @return string
     */
    public function getBorderColor($color_id)
    {
        $color = $this->getColorProperties($color_id);

        return $color['border'];
    }

    /**
     * Get background color from the color_id.
     *
     * @param string $color_id Color id
     *
     * @return string
     */
    public function getBackgroundColor($color_id)
    {
        $color = $this->getColorProperties($color_id);

        return $color['background'];
    }

    /**
     * Get CSS stylesheet of all colors.
     *
     * @return string
     */
    public function getCss()
    {
        $buffer = '';

        foreach ($this->default_colors as $color => $values) {
            $buffer .= 'div.color-'.$color.' {';
            $buffer .= 'border-left-width: '.$values['border-width'].';';
            $buffer .= 'border-left-color: '.$values['border-left-color'];
            $buffer .= '}';
            $buffer .= 'td.color-'.$color.' { background-color: '.$values['border-left-color'].'}';
        }

        return $buffer;
    }
}
