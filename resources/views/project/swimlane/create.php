<div class="page-header">
    <h2><?= t('Add a new swimlane') ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('Project/SwimlaneController', 'store', ['project_id' => $project['id']]) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('project_id', $values) ?>

    <?= $this->form->label(t('Name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, ['autofocus', 'required', 'maxlength="50"']) ?>

    <?= $this->form->label(t('Description'), 'description') ?>
    <?= $this->form->textEditor('description', $values, $errors) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Project/SwimlaneController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
    </div>
</form>
