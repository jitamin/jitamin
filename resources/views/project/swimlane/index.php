<div class="page-header">
    <h2><?= t('Swimlanes') ?></h2>
    <ul>
        <li>
            <i class="fa fa-plus fa-fw"></i>
            <?= $this->url->link(t('Add a new swimlane'), 'Project/SwimlaneController', 'create', ['project_id' => $project['id']], false, 'popover') ?>
        </li>
    </ul>
</div>

<?php if (!empty($active_swimlanes) || $default_swimlane['show_default_swimlane'] == 1): ?>
<h3><?= t('Active swimlanes') ?></h3>
    <?= $this->render('project/swimlane/table', [
        'swimlanes'        => $active_swimlanes,
        'project'          => $project,
        'default_swimlane' => $default_swimlane['show_default_swimlane'] == 1 ? $default_swimlane : [],
    ]) ?>
<?php endif ?>

<?php if (!empty($inactive_swimlanes) || $default_swimlane['show_default_swimlane'] == 0): ?>
    <h3><?= t('Inactive swimlanes') ?></h3>
    <?= $this->render('project/swimlane/table', [
        'swimlanes'        => $inactive_swimlanes,
        'project'          => $project,
        'default_swimlane' => $default_swimlane['show_default_swimlane'] == 0 ? $default_swimlane : [],
        'disable_handler'  => true,
    ]) ?>
<?php endif ?>
