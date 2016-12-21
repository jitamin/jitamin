<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core;

use PicoDb\Table;
use Pimple\Container;

/**
 * Paginator helper.
 */
class Paginator
{
    /**
     * Container instance.
     *
     * @var \Pimple\Container
     */
    private $container;

    /**
     * Total number of items.
     *
     * @var int
     */
    private $total = 0;

    /**
     * Page number.
     *
     * @var int
     */
    private $page = 1;

    /**
     * Offset.
     *
     * @var int
     */
    private $offset = 0;

    /**
     * Limit.
     *
     * @var int
     */
    private $limit = 0;

    /**
     * Sort by this column.
     *
     * @var string
     */
    private $order = '';

    /**
     * Sorting direction.
     *
     * @var string
     */
    private $direction = 'ASC';

    /**
     * Slice of items.
     *
     * @var array
     */
    private $items = [];

    /**
     * PicoDb Table instance.
     *
     * @var \Picodb\Table
     */
    private $query = null;

    /**
     * Controller name.
     *
     * @var string
     */
    private $controller = '';

    /**
     * Action name.
     *
     * @var string
     */
    private $action = '';

    /**
     * Url params.
     *
     * @var array
     */
    private $params = [];

    /**
     * Constructor.
     *
     * @param \Pimple\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Set a PicoDb query.
     *
     * @param  \PicoDb\Table
     *
     * @return Paginator
     */
    public function setQuery(Table $query)
    {
        $this->query = $query;
        $this->total = $this->query->count();

        return $this;
    }

    /**
     * Execute a PicoDb query.
     *
     * @return array
     */
    public function executeQuery()
    {
        if ($this->query !== null) {
            return $this->query
                        ->offset($this->offset)
                        ->limit($this->limit)
                        ->orderBy($this->order, $this->direction)
                        ->findAll();
        }

        return [];
    }

    /**
     * Set url parameters.
     *
     * @param string $controller
     * @param string $action
     * @param array  $params
     *
     * @return Paginator
     */
    public function setUrl($controller, $action, array $params = [])
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;

        return $this;
    }

    /**
     * Add manually items.
     *
     * @param array $items
     *
     * @return Paginator
     */
    public function setCollection(array $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Return the items.
     *
     * @return array
     */
    public function getCollection()
    {
        return $this->items ?: $this->executeQuery();
    }

    /**
     * Set the total number of items.
     *
     * @param int $total
     *
     * @return Paginator
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the total number of items.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the default page number.
     *
     * @param int $page
     *
     * @return Paginator
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the number of current page.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get the total number of pages.
     *
     * @return int
     */
    public function getPageTotal()
    {
        return ceil($this->getTotal() / $this->getMax());
    }

    /**
     * Set the default column order.
     *
     * @param string $order
     *
     * @return Paginator
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Set the default sorting direction.
     *
     * @param string $direction
     *
     * @return Paginator
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Set the maximum number of items per page.
     *
     * @param int $limit
     *
     * @return Paginator
     */
    public function setMax($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the maximum number of items per page.
     *
     * @return int
     */
    public function getMax()
    {
        return $this->limit;
    }

    /**
     * Return true if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->total === 0;
    }

    /**
     * Execute the offset calculation only if the $condition is true.
     *
     * @param bool $condition
     *
     * @return Paginator
     */
    public function calculateOnlyIf($condition)
    {
        if ($condition) {
            $this->calculate();
        }

        return $this;
    }

    /**
     * Calculate the offset value accoring to url params and the page number.
     *
     * @return Paginator
     */
    public function calculate()
    {
        $this->page = $this->container['request']->getIntegerParam('page', 1);
        $this->direction = $this->container['request']->getStringParam('direction', $this->direction);
        $this->order = $this->container['request']->getStringParam('order', $this->order);

        if ($this->page < 1) {
            $this->page = 1;
        }

        $this->offset = (int) (($this->page - 1) * $this->limit);

        return $this;
    }

    /**
     * Generation pagination links.
     *
     * @return string
     */
    public function toHtml()
    {
        $html = '';

        if (!$this->hasNothingtoShow()) {
            $html .= '<div class="pagination">';
            $html .= $this->generatPageShowing();
            $html .= $this->generatePreviousLink();
            $html .= $this->generateNextLink();
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Magic method to output pagination links.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * Column sorting.
     *
     * @param string $label  Column title
     * @param string $column SQL column name
     *
     * @return string
     */
    public function order($label, $column)
    {
        $prefix = '';
        $direction = 'ASC';

        if ($this->order === $column) {
            $prefix = $this->direction === 'DESC' ? '&#9660; ' : '&#9650; ';
            $direction = $this->direction === 'DESC' ? 'ASC' : 'DESC';
        }

        return $prefix.$this->container['helper']->url->link(
            $label,
            $this->controller,
            $this->action,
            $this->getUrlParams($this->page, $column, $direction)
        );
    }

    /**
     * Get url params for link generation.
     *
     * @param int    $page
     * @param string $order
     * @param string $direction
     *
     * @return string
     */
    protected function getUrlParams($page, $order, $direction)
    {
        $params = [
            'page'      => $page,
            'order'     => $order,
            'direction' => $direction,
        ];

        return array_merge($this->params, $params);
    }

    /**
     * Generate the previous link.
     *
     * @return string
     */
    protected function generatePreviousLink()
    {
        $html = '<span class="pagination-previous">';

        if ($this->offset > 0) {
            $html .= $this->container['helper']->url->link(
                '&laquo; '.t('Previous'),
                $this->controller,
                $this->action,
                $this->getUrlParams($this->page - 1, $this->order, $this->direction),
                false,
                'btn btn-info'
            );
        } else {
            $html .= '<span class="btn btn-default">&laquo; '.t('Previous').'</span>';
        }

        $html .= '</span>';

        return $html;
    }

    /**
     * Generate the next link.
     *
     * @return string
     */
    protected function generateNextLink()
    {
        $html = '<span class="pagination-next">';

        if (($this->total - $this->offset) > $this->limit) {
            $html .= $this->container['helper']->url->link(
                t('Next').' &raquo;',
                $this->controller,
                $this->action,
                $this->getUrlParams($this->page + 1, $this->order, $this->direction),
                false,
                'btn btn-info'
            );
        } else {
            $html .= '<span class="btn btn-default">'.t('Next').' &raquo;</span>';
        }

        $html .= '</span>';

        return $html;
    }

    /**
     * Generate the page showing.
     *
     * @return string
     */
    protected function generatPageShowing()
    {
        return '<span class="pagination-showing">'.t('Showing %d-%d of %d', (($this->getPage() - 1) * $this->getMax() + 1), min($this->getTotal(), $this->getPage() * $this->getMax()), $this->getTotal()).'</span>';
    }

    /**
     * Return true if there is no pagination to show.
     *
     * @return bool
     */
    protected function hasNothingtoShow()
    {
        return $this->offset === 0 && ($this->total - $this->offset) <= $this->limit;
    }
}
