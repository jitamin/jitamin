<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Core\Database\Model;

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
            'name'       => 'White',
            'background' => '#FFFFFF',
            'border'     => '#CCCCCC',
        ],
        'yellow' => [
            'name'       => 'Yellow',
            'background' => '#F5F7C4',
            'border'     => '#DFE32D',
        ],
        'blue' => [
            'name'       => 'Blue',
            'background' => '#DBEBFF',
            'border'     => '#A8CFFF',
        ],
        'green' => [
            'name'       => 'Green',
            'background' => '#BDF4CB',
            'border'     => '#4AE371',
        ],
        'purple' => [
            'name'       => 'Purple',
            'background' => '#DFB0FF',
            'border'     => '#CD85FE',
        ],
        'red' => [
            'name'       => 'Red',
            'background' => '#FFBBBB',
            'border'     => '#FF9797',
        ],
        'orange' => [
            'name'       => 'Orange',
            'background' => '#FFD7B3',
            'border'     => '#FFAC62',
        ],
        'grey' => [
            'name'       => 'Grey',
            'background' => '#EEEEEE',
            'border'     => '#BBBBBB',
        ],
        'brown' => [
            'name'       => 'Brown',
            'background' => '#D7CCC8',
            'border'     => '#4E342E',
        ],
        'deep_orange' => [
            'name'       => 'Deep Orange',
            'background' => '#FFAB91',
            'border'     => '#E64A19',
        ],
        'dark_grey' => [
            'name'       => 'Dark Grey',
            'background' => '#CFD8DC',
            'border'     => '#455A64',
        ],
        'pink' => [
            'name'       => 'Pink',
            'background' => '#F48FB1',
            'border'     => '#D81B60',
        ],
        'teal' => [
            'name'       => 'Teal',
            'background' => '#80CBC4',
            'border'     => '#00695C',
        ],
        'cyan' => [
            'name'       => 'Cyan',
            'background' => '#B2EBF2',
            'border'     => '#00BCD4',
        ],
        'lime' => [
            'name'       => 'Lime',
            'background' => '#E6EE9C',
            'border'     => '#AFB42B',
        ],
        'light_green' => [
            'name'       => 'Light Green',
            'background' => '#DCEDC8',
            'border'     => '#689F38',
        ],
        'amber' => [
            'name'       => 'Amber',
            'background' => '#FFE082',
            'border'     => '#FFA000',
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
            $buffer .= 'background-color: '.$values['background'].';';
            $buffer .= 'border-color: '.$values['border'];
            $buffer .= '}';
            $buffer .= 'td.color-'.$color.' { background-color: '.$values['background'].'}';
        }

        return $buffer;
    }
}
