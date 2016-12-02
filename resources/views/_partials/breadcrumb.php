<div class="breadcrumb"><a href="/"><?= t('Home') ?></a> &raquo; 
    <?php if (!empty($project) && !empty($task)): ?>
        <?= $this->url->link($this->text->e($project['name']), 'BoardViewController', 'show', ['project_id' => $project['id']]) ?>
    <?php else: ?>
        <?= $this->text->e($title) ?>
    <?php endif ?>
    <?php if (!empty($description)): ?>
    <span class="tooltip" title="<?= $this->text->markdownAttribute($description) ?>">
        <i class="fa fa-info-circle"></i>
    </span>
    <?php endif ?>
</div>