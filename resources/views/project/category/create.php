<div class="page-header">
    <h2><?= t('Add a new category') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('Project/CategoryController', 'store', ['project_id' => $project['id']]) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('project_id', $values) ?>

    <?= $this->form->label(t('Category Name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, ['autofocus', 'required', 'maxlength="50"']) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Project/CategoryController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
    </div>
</form>
