<section id="main">
    <?= $this->projectHeader->render($project, 'Project/ProjectController', 'show') ?>
    <?= $this->render('project/_partials/columns', ['project' => $project]) ?>
    <?= $this->render('project/_partials/description', ['project' => $project]) ?>
    <?= $this->render('project/_partials/information', ['project' => $project, 'users' => $users, 'roles' => $roles]) ?>
    <?= $this->render('project/_partials/attachments', ['project' => $project, 'images' => $images, 'files' => $files]) ?>
    <?= $this->render('project/_partials/activity', ['project' => $project, 'events' => $events]) ?>
</section>
