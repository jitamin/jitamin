<div class="page-header">
    <h2><?= t('Edit external link') ?></h2>
</div>

<form class="popover-form" action="<?= $this->url->href('TaskExternalLinkController', 'update', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>" method="post" autocomplete="off">
    <?= $this->render('task_external_link/form', ['task' => $task, 'dependencies' => $dependencies, 'values' => $values, 'errors' => $errors]) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'TaskExternalLinkController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
    </div>
</form>
