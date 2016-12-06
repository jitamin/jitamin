<div class="page-header">
    <h2><?= t('My activities') ?></h2>
</div>
<?= $this->render('event/events', ['events' => $events]) ?>