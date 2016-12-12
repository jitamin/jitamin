<?= $this->render('dashboard/_partials/projects', ['paginator' => $paginator, 'user' => $user]) ?>

<?= $this->hook->render('template:dashboard:show', ['user' => $user]) ?>
