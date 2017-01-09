<div class="page-header">
    <h2><?= t('Remove an automatic action') ?></h2>
</div>

<form action="<?= $this->url->href('Project/ActionController', 'remove', ['project_id' => $project['id'], 'action_id' => $action['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this action: "%s"?', $this->text->in($action['event_name'], $available_events).'/'.$this->text->in($action['action_name'], $available_actions)) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Project/ActionController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
