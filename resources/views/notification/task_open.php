<h2><?= $this->text->e($task['title']) ?> (#<?= $task['id'] ?>)</h2>

<p><?= t('The task #%d have been opened.', $task['id']) ?></p>

<?= $this->render('notification/footer', ['task' => $task, 'application_url' => $application_url]) ?>
