<div class="page-header">
    <h2><?= $this->text->e($project['name']) ?> &gt; <?= $this->text->e($task['title']) ?></h2>
</div>

<?php if (empty($projects_list)): ?>
    <p class="alert"><?= t('There is no destination project available.') ?></p>
    <div class="form-actions">
        <?= $this->url->link(t('cancel'), 'BoardViewController', 'show', ['project_id' => $task['project_id']], false, 'close-popover btn') ?>
    </div>
<?php else: ?>
    <form class="popover-form" method="post" action="<?= $this->url->href('TaskCreationController', 'duplicateProjects', ['project_id' => $task['project_id']]) ?>" autocomplete="off">
        <?= $this->form->csrf() ?>
        <?= $this->form->hidden('task_id', $values) ?>

        <?= $this->form->select(
            'project_ids[]',
            $projects_list,
            $values,
            [],
            ['multiple']
        ) ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-info"><?= t('Duplicate') ?></button>
            <?= t('or') ?> <?= $this->url->link(t('cancel'), 'BoardViewController', 'show', ['project_id' => $task['project_id']], false, 'close-popover') ?>
        </div>
    </form>
<?php endif ?>
