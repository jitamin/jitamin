<section id="main">
    <?= $this->projectHeader->render($project, 'TaskController', 'index') ?>
    <section class="sidebar-container">
        <?= $this->render($sidebar_template, ['project' => $project]) ?>

        <div class="sidebar-content">
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
