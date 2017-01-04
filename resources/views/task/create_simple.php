<div class="page-header">
    <h2><?= t('New task') ?></h2>
</div>

<form class="popover-form" method="post" action="<?= $this->url->href('Task/TaskSimpleController', 'store') ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <?= $this->task->selectTitle($values, $errors) ?>
    <?= $this->task->selectProject($projects, $values, $errors) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Dashboard/Dashboard', 'index', [], false, 'close-popover') ?>
    </div>
</form>

