<?= $this->projectHeader->render($project, 'Task/TaskController', 'index') ?>
<?= $this->render('analytic/_partials/subnav', ['project' => $project]) ?>
        <?= $content_for_sublayout ?>
