<?= $this->render('dashboard/project/_projects', ['paginator' => $paginator, 'user' => $user]) ?>

<?= $this->hook->render('template:dashboard:show', ['user' => $user]) ?>