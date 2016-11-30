<?php if (!empty($categories)): ?>
<div class="page-header">
    <h2><?= t('Categories') ?></h2>
</div>
<table  class="categories-table table-striped"
        data-save-position-url="<?= $this->url->href('CategoryController', 'move', ['project_id' => $project['id']]) ?>">
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
                    <?= $this->url->link(t('Edit'), 'CategoryController', 'edit', ['project_id' => $project['id'], 'category_id' => $category_id], false, 'popover') ?>
                </li>
                <li>
                    <?= $this->url->link(t('Remove'), 'CategoryController', 'confirm', ['project_id' => $project['id'], 'category_id' => $category_id], false, 'popover') ?>
                </li>
            </ul>
            </div>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
<?php endif ?>

<div class="page-header">
    <h2><?= t('Add a new category') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('CategoryController', 'save', ['project_id' => $project['id']]) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('project_id', $values) ?>

    <?= $this->form->label(t('Category Name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, ['autofocus', 'required', 'maxlength="50"']) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
    </div>
</form>
