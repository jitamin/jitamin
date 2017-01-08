<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Api;

use Jitamin\Policy\CategoryPolicy;
use Jitamin\Policy\ProjectPolicy;

/**
 * Category API controller.
 */
class CategoryController extends Controller
{
    /**
     * Get a category by the category id.
     *
     * @param int $category_id Category id
     *
     * @return array
     */
    public function getCategory($category_id)
    {
        CategoryPolicy::getInstance($this->container)->check($this->getClassName(), 'getCategory', $category_id);

        return $this->categoryModel->getById($category_id);
    }

    /**
     * Return all categories for a given project.
     *
     * @param int $project_id Project id
     *
     * @return array
     */
    public function getAllCategories($project_id)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'getAllCategories', $project_id);

        return $this->categoryModel->getAll($project_id);
    }

    /**
     * Remove a category.
     *
     * @param int $category_id Category id
     *
     * @return bool
     */
    public function removeCategory($category_id)
    {
        CategoryPolicy::getInstance($this->container)->check($this->getClassName(), 'removeCategory', $category_id);

        return $this->categoryModel->remove($category_id);
    }

    /**
     * Create a category (run inside a transaction).
     *
     * @param int    $project_id
     * @param string $name
     *
     * @return bool|int
     */
    public function createCategory($project_id, $name)
    {
        ProjectPolicy::getInstance($this->container)->check($this->getClassName(), 'createCategory', $project_id);

        $values = [
            'project_id' => $project_id,
            'name'       => $name,
        ];

        list($valid) = $this->categoryValidator->validateCreation($values);

        return $valid ? $this->categoryModel->create($values) : false;
    }

    /**
     * Update a category.
     *
     * @param int    $id
     * @param string $name
     *
     * @return bool
     */
    public function updateCategory($id, $name)
    {
        CategoryPolicy::getInstance($this->container)->check($this->getClassName(), 'updateCategory', $id);

        $values = [
            'id'   => $id,
            'name' => $name,
        ];

        list($valid) = $this->categoryValidator->validateModification($values);

        return $valid && $this->categoryModel->update($values);
    }
}
