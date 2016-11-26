<section id="main" class="public-task">
    <?= $this->render('task/details', [
        'task' => $task,
        'tags' => $tags,
        'project' => $project,
        'editable' => false,
    ]) ?>

    <?= $this->render('task/description', [
        'task' => $task,
        'project' => $project,
        'is_public' => true,
    ]) ?>

    <?= $this->render('subtask/show', [
        'task' => $task,
        'subtasks' => $subtasks,
        'editable' => false
    ]) ?>

    <?= $this->render('task_internal_link/show', [
        'task' => $task,
        'links' => $links,
        'project' => $project,
        'editable' => false,
        'is_public' => true,
    ]) ?>

    <?= $this->render('comments/show', [
        'task' => $task,
        'comments' => $comments,
        'project' => $project,
        'editable' => false,
        'is_public' => true,
    ]) ?>
</section>
