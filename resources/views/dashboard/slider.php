<div class="slider">
    <div class="page-header">
    <h2><?= t('Projects') ?></h2>
    </div>
    <h3><i class="fa fa-star-o fa-fw"></i><?= t('My stars') ?></h3>
    <?php if ($paginator->isEmpty()): ?>
    <p class="alert"><?= t('No projects to show.') ?></p>
    <?php else: ?>
    <ul>
        <?php foreach ($paginator->getCollection() as $project): ?>
        <li>
            <?= $this->url->link($this->text->e($project['name']), 'Project/ProjectController', 'show', ['project_id' => $project['id']]) ?>
                <?php if ($project['is_private']): ?>
                    <i class="fa fa-lock" title="<?= t('Private project') ?>"></i>
                <?php endif ?>
        </li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>
    <h3><i class="fa fa-history fa-fw"></i><?= t('Recent projects') ?></h3>
    <?php if (empty($recent_projects)): ?>
    <p class="alert"><?= t('No projects to show.') ?></p>
    <?php else: ?>
    <ul>
        <?php foreach ($recent_projects as $project): ?>
        <li>
            <?= $this->url->link($this->text->e($project['name']), 'Project/ProjectController', 'show', ['project_id' => $project['id']]) ?>
                <?php if ($project['is_private']): ?>
                    <i class="fa fa-lock" title="<?= t('Private project') ?>"></i>
                <?php endif ?>
        </li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>
</div>