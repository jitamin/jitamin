<section id="main">
    <?= $this->projectHeader->render($project, 'TaskController', 'index') ?>
    <?= $this->render('analytic/_partials/subnav', ['project' => $project]) ?>
            <?= $content_for_sublayout ?>
</section>
