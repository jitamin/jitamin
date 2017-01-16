<p class="activity-title">
    <?= l('%s removed an internal link for the task %s',
        $this->url->link($author, 'Profile/ProfileController', 'show', ['user_id' => $author_username]),
        $this->url->link(t('#%d', $task['id']), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']])
    ) ?>
    <small class="activity-date"><?= $this->dt->datetime($date_creation) ?></small>
</p>
<div class="activity-description">
    <p class="activity-task-title">
        <?= l(
            'The link with the relation "%s" to the task %s have been removed',
            $this->text->e($task_link['label']),
            $this->url->link(t('#%d', $task_link['opposite_task_id']), 'Task/TaskController', 'show', ['task_id' => $task_link['opposite_task_id']])
        ) ?>
    </p>
</div>
