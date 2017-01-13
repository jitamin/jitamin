<p class="activity-title">
    <?= e('%s created the task %s',
            $this->url->link($author, 'Profile/ProfileController', 'show', ['user_id' => $author_username]),
            $this->url->link(t('#%d', $task['id']), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']])
        ) ?>
    <small class="activity-date"><?= $this->dt->datetime($date_creation) ?></small>
</p>
<div class="activity-description">
    <p class="activity-task-title"><?= $this->text->e($task['title']) ?></p>
</div>
