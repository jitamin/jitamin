<section class="accordion-section <?= empty($files) && empty($images) ? 'accordion-collapsed' : '' ?>">
    <div class="accordion-title">
        <h3><a href="#" class="fa accordion-toggle"></a> <?= t('Attachments') ?></h3>
    </div>
    <div class="accordion-content">
        <?php if ($this->user->hasProjectAccess('ProjectFileController', 'create', $project['id'])): ?>
            <?= $this->url->button('fa-plus', t('Upload a file'), 'ProjectFileController', 'create', ['project_id' => $project['id']], 'btn-header btn-default popover') ?>
        <?php endif ?>

        <?= $this->render('project_view/images', ['project' => $project, 'images' => $images]) ?>
        <?= $this->render('project_view/files', ['project' => $project, 'files' => $files]) ?>
    </div>
</section>
