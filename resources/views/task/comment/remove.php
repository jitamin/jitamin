<div class="page-header">
    <h2><?= t('Remove a comment') ?></h2>
</div>

<form action="<?= $this->url->href('Task/CommentController', 'remove', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'comment_id' => $comment['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this comment?') ?>
        </p>

        <?= $this->render('task/comment/show', [
            'comment'      => $comment,
            'task'         => $task,
            'hide_actions' => true,
        ]) ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
