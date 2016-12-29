<div class="page-header">
    <h2><?= t('Remove a comment') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove this comment?') ?>
    </p>

    <?= $this->render('comment/show', [
        'comment'      => $comment,
        'task'         => $task,
        'hide_actions' => true,
    ]) ?>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'CommentController', 'remove', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'comment_id' => $comment['id']], true, 'btn btn-danger') ?>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
    </div>
</div>
