<div class="page-header">
    <h2><?= $this->text->e($project['name']) ?> &gt; <?= t('New task') ?></h2>
</div>

<form class="popover-form" method="post" action="<?= $this->url->href('Task/TaskController', 'store', ['project_id' => $values['project_id']]) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <div class="form-columns">
        <div class="form-column">
            <?= $this->task->selectTitle($values, $errors) ?>
            <?= $this->task->selectDescription($values, $errors) ?>
            <?= $this->task->selectTags($project) ?>
            
            <?= $this->hook->render('template:task:form:first-column', ['values' => $values, 'errors' => $errors]) ?>
            
            <?php if (!isset($duplicate)): ?>
                <?= $this->form->checkbox('another_task', t('Create another task'), 1, isset($values['another_task']) && $values['another_task'] == 1) ?>
                <?= $this->form->checkbox('duplicate_multiple_projects', t('Duplicate to multiple projects'), 1) ?>
            <?php endif ?>

        </div>

        <div class="form-column">
            <?= $this->form->hidden('project_id', $values) ?>
            <?= $this->task->selectColor($values) ?>
            <?= $this->task->selectAssignee($users_list, $values, $errors) ?>
            <?= $this->task->selectCategory($categories_list, $values, $errors) ?>
            <?= $this->task->selectSwimlane($swimlanes_list, $values, $errors) ?>
            <?= $this->task->selectColumn($columns_list, $values, $errors) ?>
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
        <button type="submit" class="btn btn-info" tabindex="15"><?= t('Save') ?></button>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Project/Board/BoardController', 'show', ['project_id' => $values['project_id']], false, 'close-popover') ?>
    </div>
</form>
