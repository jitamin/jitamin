<section id="main">
    <?= $this->projectHeader->render($project, 'TaskListController', 'show') ?>
    <?= $this->hook->render('template:task:layout:top', ['task' => $task]) ?>
    <section
        class="sidebar-container" id="task-view"
        data-edit-url="<?= $this->url->href('TaskModificationController', 'edit', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>"
        data-subtask-url="<?= $this->url->href('SubtaskController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>"
        data-internal-link-url="<?= $this->url->href('TaskInternalLinkController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>"
        data-comment-url="<?= $this->url->href('CommentController', 'create', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>">

        <?= $this->render($sidebar_template, ['task' => $task]) ?>

        <div class="sidebar-content">
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
