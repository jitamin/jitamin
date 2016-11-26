<div class="page-header">
    <h2><?= t('Edit a comment') ?></h2>
</div>

<form class="popover-form" method="post" action="<?= $this->url->href('CommentController', 'update', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'comment_id' => $comment['id']]) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('id', $values) ?>
    <?= $this->form->hidden('task_id', $values) ?>
    <?= $this->form->hidden('user_id', $values) ?>

    <?= $this->form->textEditor('comment', $values, $errors, ['autofocus' => true, 'required' => true]) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
    </div>
</form>
