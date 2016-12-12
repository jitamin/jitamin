<div class="page-header">
    <h3><?= $this->url->link(t('My projects'), 'DashboardController', 'projects', ['user_id' => $user['id']]) ?> (<?= $paginator->getTotal() ?>)</h3>
</div>

<?= $this->render('dashboard/_partials/projects', ['paginator' => $paginator, 'user' => $user]) ?>