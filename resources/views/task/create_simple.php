<div class="page-header">
    <h2><?= t('New task') ?></h2>
</div>

<form class="popover-form" method="post" action="<?= $this->url->href('Task/TaskSimpleController', 'store') ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <?= $this->task->selectTitle($values, $errors) ?>
    <?php if(isset($values['project_id']) && $values['project_id']): ?>
        <?= $this->form->hidden('project_id', $values) ?>
    <?php else: ?>
        <?= $this->task->selectProject($projects, $values, $errors) ?>
    <?php endif ?>
    <?php if(isset($values['column_id'])): ?>
        <?= $this->form->hidden('column_id', $values) ?>
    <?php endif ?>
    <?php if(isset($values['swimlane_id'])): ?>
        <?= $this->form->hidden('swimlane_id', $values) ?>
    <?php endif ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Dashboard/Dashboard', 'index', [], false, 'close-popover') ?>
    </div>
</form>

