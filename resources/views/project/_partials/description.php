<section class="accordion-section <?= empty($project['description']) ? 'accordion-collapsed' : '' ?>">
    <div class="accordion-title">
        <h3><a href="#" class="fa accordion-toggle"></a> <?= t('Description') ?></h3>
    </div>
    <div class="accordion-content">
        <?php if ($this->user->hasProjectAccess('Project/ProjectController', 'edit_description', $project['id'])): ?>
            <?= $this->url->button('fa-edit', t('Edit description'), 'Project/ProjectController', 'edit_description', ['project_id' => $project['id']], 'btn-header btn-default popover small') ?>
        <?php endif ?>
        <article class="markdown">
            <?= $this->text->markdown($project['description']) ?>
        </article>
    </div>
</section>
