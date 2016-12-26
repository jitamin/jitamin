<div class="breadcrumb">
<div class="container2 page">
<a href="/"><?= t('Home') ?></a> &raquo; 
    <?php if (!empty($project) && !empty($task)): ?>
        <?= $this->url->link($this->text->e($project['name']), 'BoardController', 'show', ['project_id' => $project['id']]) ?>
    <?php else: ?>
        <?= $this->text->e($title) ?>
    <?php endif ?>
    <?php if (!empty($description)): ?>
    <span class="tooltip" title="<?= $this->text->markdownAttribute($description) ?>">
        <i class="fa fa-info-circle"></i>
    </span>
    <?php endif ?>

    <?php if (!empty($project)): ?>
        <?php if ($this->user->isStargazer($project['id'], $this->user->getId())): ?>
            <?= $this->url->link('<i class="fa fa-star-o"></i> '.t('Unstar'), 'ProjectController', 'confirmUnstar', ['project_id' => $project['id']], true, 'popover btn-warning btn-xs') ?>

        <?php else: ?>
            <?= $this->url->link('<i class="fa fa-star"></i> '.t('Star'), 'ProjectController', 'confirmStar', ['project_id' => $project['id']], true, 'popover btn-primary btn-xs') ?>
        <?php endif ?>
    <?php endif ?>
</div>
</div>