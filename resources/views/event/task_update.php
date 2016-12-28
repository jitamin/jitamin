<p class="activity-title">
    <?= e('%s updated the task %s',
            $this->url->link($author, 'ProfileController', 'profile', ['user_id' => $author_username]),
            $this->url->link(t('#%d', $task['id']), 'TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']])
        ) ?>
    <small class="activity-date"><?= $this->dt->datetime($date_creation) ?></small>
</p>
<div class="activity-description">
    <p class="activity-task-title"><?= $this->text->e($task['title']) ?></p>
    <?php if (isset($changes)): ?>
        <div class="activity-changes">
            <?= $this->render('task/changes', ['changes' => $changes, 'task' => $task]) ?>
        </div>
    <?php endif ?>
</div>
