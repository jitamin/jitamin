<?= $this->projectHeader->render($project) ?>
<?= $this->hook->render('template:task:layout:top', ['task' => $task]) ?>
<section
    class="page-container" id="task-view"
    data-edit-url="<?= $this->url->href('Task/TaskController', 'edit', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>"
    data-subtask-url="<?= $this->url->href('Task/Subtask/SubtaskController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>"
    data-internal-link-url="<?= $this->url->href('Task/TaskInternalLinkController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>"
    data-comment-url="<?= $this->url->href('Task/CommentController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>">

    <div class="page-content">
        <?= $this->render('task/_partials/subnav', ['task' => $task]) ?>
        <?= $content_for_sublayout ?>
    </div>
    <?= $this->render($subside_template, ['task' => $task]) ?>
</section>
