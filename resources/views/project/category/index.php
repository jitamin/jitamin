
<div class="page-header">
    <h2><?= t('Categories') ?></h2>
    <ul>
        <li>
            <i class="fa fa-plus fa-fw"></i>
            <?= $this->url->link(t('Add a new category'), 'Project/CategoryController', 'create', ['project_id' => $project['id']], false, 'popover') ?>
        </li>
    </ul>
</div>

<?php if (!empty($categories)): ?>
<table  class="categories-table table-striped"
        data-save-position-url="<?= $this->url->href('Project/CategoryController', 'move', ['project_id' => $project['id']]) ?>">
    <thead>
    <tr>
        <th><?= t('Category Name') ?></th>
        <th class="column-8"><?= t('Actions') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $category_id => $category_name): ?>
    <tr data-category-id="<?= $category_id ?>">
        <td>
            <i class="fa fa-arrows-alt draggable-row-handle" title="<?= t('Change category position') ?>"></i>
            <?= $this->text->e($category_name) ?>
        </td>
        <td>
            <div class="dropdown">
            <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog fa-fw"></i><i class="fa fa-caret-down"></i></a>
            <ul>
                <li>
                    <?= $this->url->link(t('Edit'), 'Project/CategoryController', 'edit', ['project_id' => $project['id'], 'category_id' => $category_id], false, 'popover') ?>
                </li>
                <li>
                    <?= $this->url->link(t('Remove'), 'Project/CategoryController', 'remove', ['project_id' => $project['id'], 'category_id' => $category_id], false, 'popover') ?>
                </li>
            </ul>
            </div>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
<?php endif ?>