<?= $this->projectHeader->render($project) ?>
<?= $this->render('analytic/_partials/subnav', ['project' => $project]) ?>
        <?= $content_for_sublayout ?>
