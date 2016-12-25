<div class="page-header">
    <h2><?= t('Personal API token') ?></h2>
</div>

<p class="alert">
    <?php if (empty($user['api_token'])): ?>
        <?= t('No personal API token registered.') ?>
    <?php else: ?>
        <?= t('Your personal API token is "%s"', $user['api_token']) ?>
    <?php endif ?>
</p>

<?php if (! empty($user['api_token'])): ?>
    <?= $this->url->link(t('Remove your token'), 'ProfileController', 'removeApiToken', ['user_id' => $user['id']], true, 'btn btn-red') ?>
<?php endif ?>

<?= $this->url->link(t('Generate a new token'), 'ProfileController', 'generateApiToken', ['user_id' => $user['id']], true, 'btn btn-blue') ?>