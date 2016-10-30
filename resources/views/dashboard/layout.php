<section id="main">
    <div class="page-header">
        <span><?= $this->url->link(t('My dashboard'), 'DashboardController', 'show', array(), false, '', t('My dashboard')) ?> / 
            <span class="title">
                <?php if (! empty($project) && ! empty($task)): ?>
                    <?= $this->url->link($this->text->e($project['name']), 'BoardViewController', 'show', array('project_id' => $project['id'])) ?>
                <?php else: ?>
                    <?= $this->text->e($title) ?>
                <?php endif ?>
            </span>
            <?php if (! empty($description)): ?>
                <small class="tooltip" title="<?= $this->text->markdownAttribute($description) ?>">
                    <i class="fa fa-info-circle"></i>
                </small>
            <?php endif ?>
        </span>
    </div>
    <section class="sidebar-container" id="dashboard">
        <?= $this->render($sidebar_template, array('user' => $user)) ?>
        <div class="sidebar-content">
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
