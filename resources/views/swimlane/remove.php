<section id="main">
    <div class="page-header">
        <h2><?= t('Remove a swimlane') ?></h2>
    </div>

    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this swimlane: "%s"?', $swimlane['name']) ?>
        </p>

        <div class="form-actions">
            <?= $this->url->link(t('Confirm'), 'SwimlaneController', 'remove', ['project_id' => $project['id'], 'swimlane_id' => $swimlane['id']], true, 'btn btn-danger') ?>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'SwimlaneController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</section>
