<div class="page-header">
    <h2><?= $this->text->e($project['name']) ?> &gt; <?= $this->text->e($task['title']) ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('Task/TaskController', 'update', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('id', $values) ?>
    <?= $this->form->hidden('project_id', $values) ?>

    <div class="form-columns">
        <div class="form-column">
            <?= $this->task->selectTitle($values, $errors) ?>
            <?= $this->task->selectDescription($values, $errors) ?>
            <?= $this->task->selectTags($project, $tags) ?>

            <?= $this->hook->render('template:task:form:first-column', ['values' => $values, 'errors' => $errors]) ?>
        </div>

        <div class="form-column">
            <?= $this->task->selectColor($values) ?>
            <?= $this->task->selectAssignee($users_list, $values, $errors) ?>
            <?= $this->task->selectCategory($categories_list, $values, $errors) ?>
            <?= $this->task->selectPriority($project, $values) ?>
            <?= $this->task->selectScore($values, $errors) ?>
            <?= $this->task->selectReference($values, $errors) ?>

            <?= $this->hook->render('template:task:form:second-column', ['values' => $values, 'errors' => $errors]) ?>
        </div>

        <div class="form-column">
            <?= $this->task->selectTimeEstimated($values, $errors) ?>
            <?= $this->task->selectTimeSpent($values, $errors) ?>
            <?= $this->task->selectStartDate($values, $errors) ?>
            <?= $this->task->selectDueDate($values, $errors) ?>
            <?= $this->task->selectProgress($values, $errors) ?>

            <?= $this->hook->render('template:task:form:third-column', ['values' => $values, 'errors' => $errors]) ?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-success" tabindex="15"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], false, 'close-popover') ?>
    </div>
</form>
