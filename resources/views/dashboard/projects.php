<div class="page-header">
    <h3><?= $this->url->link(t('My projects'), 'DashboardController', 'projects', ['user_id' => $user['id']]) ?> (<?= $paginator->getTotal() ?>)</h3>
</div>
<?php if ($paginator->isEmpty()): ?>
    <p class="alert"><?= t('Your are not member of any project.') ?></p>
<?php else: ?>
    <table class="table-striped table-small table-scrolling">
        <tr>
            <th class="column-5"><?= $paginator->order(t('Id'), \Hiject\Model\ProjectModel::TABLE.'.id') ?></th>
            <th class="column-30"><?= $paginator->order(t('Project'), \Hiject\Model\ProjectModel::TABLE.'.name') ?></th>
            <th class="column-5"><?= t('Tasks') ?></th>
            <th class="column-8"><?= $paginator->order(t('Status'), \Hiject\Model\ProjectModel::TABLE.'.is_active') ?></th>
            <th><?= t('Columns') ?></th>
        </tr>
        <?php foreach ($paginator->getCollection() as $project): ?>
        <tr>
            <td>
                <?= $this->render('project/dropdown', ['project' => $project]) ?>
            </td>
            <td>
                <?= $this->url->link($this->text->e($project['name']), 'ProjectViewController', 'show', ['project_id' => $project['id']]) ?>
                <?php if ($project['is_private']): ?>
                    <i class="fa fa-lock" title="<?= t('Private project') ?>"></i>
                <?php endif ?>
                <?php if (! empty($project['description'])): ?>
                    <span class="tooltip" title="<?= $this->text->markdownAttribute($project['description']) ?>">
                        <i class="fa fa-info-circle"></i>
                    </span>
                <?php endif ?>
            </td>
            <td>
                <?= $project['nb_active_tasks'] ?>
            </td>
            <td>
                 <?php if ($project['is_active']): ?>
                        <?= t('Active') ?>
                    <?php else: ?>
                        <?= t('Inactive') ?>
                    <?php endif ?>
            </td>
            <td class="dashboard-project-stats">
                <?php foreach ($project['columns'] as $column): ?>
                    <strong title="<?= t('Task count') ?>"><?= $column['nb_tasks'] ?></strong>
                    <small><?= $this->text->e($column['title']) ?></small>
                <?php endforeach ?>
            </td>

        </tr>
        <?php endforeach ?>
    </table>

    <?= $paginator ?>
<?php endif ?>
