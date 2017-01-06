<section class="accordion-section <?= empty($files) && empty($images) ? 'accordion-collapsed' : '' ?>">
    <div class="accordion-title">
        <h3><a href="#" class="fa accordion-toggle"></a> <?= t('Attachments') ?></h3>
    </div>
    <div class="accordion-content">
        <?= $this->render('task/attachment/images', ['task' => $task, 'images' => $images]) ?>
        <?= $this->render('task/attachment/files', ['task' => $task, 'files' => $files]) ?>
    </div>
</section>
