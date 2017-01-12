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
                <?php if (!empty($project['description'])): ?>
                    <span class="tooltip" title="<?= $this->text->markdownAttribute($project['description']) ?>">
                        <i class="fa fa-info-circle"></i>
                    </span>
                <?php endif ?>
        </li>
        <?php endforeach ?>
    </ul>
    <?php endif ?>
</div>