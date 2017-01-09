<div class="page-header">
    <h2><?= t('Do you really want to close all tasks of this column?') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('Project/Board/BoardPopoverController', 'closeColumnTasks', ['project_id' => $project['id'], 'column_id' => $column_id, 'swimlane_id' => $swimlane_id]) ?>">
    <?= $this->form->csrf() ?>

    <p class="alert"><?= t('%d task(s) in the column "%s" and the swimlane "%s" will be closed.', $nb_tasks, $column, $swimlane) ?></p>

    <div class="form-actions">
        <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Project/Board/BoardController', 'show', ['project_id' => $project['id']], false, 'close-popover') ?>
    </div>
</form>
