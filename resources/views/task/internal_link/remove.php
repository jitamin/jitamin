<div class="page-header">
    <h2><?= t('Remove a link') ?></h2>
</div>

<form action="<?= $this->url->href('Task/TaskInternalLinkController', 'remove', ['link_id' => $link['id'], 'task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this link with task #%d?', $link['opposite_task_id']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
