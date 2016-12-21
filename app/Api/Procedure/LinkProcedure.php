<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

/**
 * Link API controller.
 */
class LinkProcedure extends BaseProcedure
{
    /**
     * Get a link by id.
     *
     * @param int $link_id Link id
     *
     * @return array
     */
    public function getLinkById($link_id)
    {
        return $this->linkModel->getById($link_id);
    }

    /**
     * Get a link by name.
     *
     * @param string $label
     *
     * @return array
     */
    public function getLinkByLabel($label)
    {
        return $this->linkModel->getByLabel($label);
    }

    /**
     * Get the opposite link id.
     *
     * @param int $link_id Link id
     *
     * @return int
     */
    public function getOppositeLinkId($link_id)
    {
        return $this->linkModel->getOppositeLinkId($link_id);
    }

    /**
     * Get all links.
     *
     * @return array
     */
    public function getAllLinks()
    {
        return $this->linkModel->getAll();
    }

    /**
     * Create a new link label.
     *
     * @param string $label
     * @param string $opposite_label
     *
     * @return bool|int
     */
    public function createLink($label, $opposite_label = '')
    {
        $values = [
            'label'          => $label,
            'opposite_label' => $opposite_label,
        ];

        list($valid) = $this->linkValidator->validateCreation($values);

        return $valid ? $this->linkModel->create($label, $opposite_label) : false;
    }

    /**
     * Update a link.
     *
     * @param int    $link_id
     * @param int    $opposite_link_id
     * @param string $label
     *
     * @return bool
     */
    public function updateLink($link_id, $opposite_link_id, $label)
    {
        $values = [
            'id'          => $link_id,
            'opposite_id' => $opposite_link_id,
            'label'       => $label,
        ];

        list($valid) = $this->linkValidator->validateModification($values);

        return $valid && $this->linkModel->update($values);
    }

    /**
     * Remove a link a the relation to its opposite.
     *
     * @param int $link_id
     *
     * @return bool
     */
    public function removeLink($link_id)
    {
        return $this->linkModel->remove($link_id);
    }
}
