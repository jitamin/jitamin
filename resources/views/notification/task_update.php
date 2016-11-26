<h2><?= $this->text->e($task['title']) ?> (#<?= $task['id'] ?>)</h2>

<?= $this->render('task/changes', ['changes' => $changes, 'task' => $task]) ?>
<?= $this->render('notification/footer', ['task' => $task, 'application_url' => $application_url]) ?>