<div class="tooltip-large">
    <?php foreach ($comments as $comment): ?>
        <?= $this->render('task/comment/show', [
            'comment'      => $comment,
            'task'         => $task,
            'hide_actions' => true,
        ]) ?>
    <?php endforeach ?>
</div>
