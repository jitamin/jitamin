<?= $this->render('task/details', [
    'task'     => $task,
    'tags'     => $tags,
    'project'  => $project,
    'editable' => false,
]) ?>

<div class="page-header">
    <h2><?= t('Activities') ?></h2>
</div>

<?= $this->render('event/events', ['events' => $events]) ?>
