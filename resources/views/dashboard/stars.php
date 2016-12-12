<div class="page-header">
    <h3><?= $this->url->link(t('My stars'), 'DashboardController', 'stars', ['user_id' => $user['id']]) ?> (<?= $paginator->getTotal() ?>)</h3>
</div>

<?= $this->render('dashboard/_partials/projects', ['paginator' => $paginator, 'user' => $user]) ?>