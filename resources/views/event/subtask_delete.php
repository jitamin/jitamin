<p class="activity-title">
    <?= l('%s removed a subtask for the task %s',
        $this->url->link($author, 'Profile/ProfileController', 'show', ['user_id' => $author_username]),
        $this->url->link(t('#%d', $task['id']), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']])
    ) ?>
    <small class="activity-date"><?= $this->dt->datetime($date_creation) ?></small>
</p>
<div class="activity-description">
    <p class="activity-task-title"><?= $this->text->e($task['title']) ?></p>
    <ul>
        <li>
            <?= $this->text->e($subtask['title']) ?> (<strong><?= $this->text->e($subtask['status_name']) ?></strong>)
        </li>
    </ul>
</div>
