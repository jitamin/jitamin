<div class="page-header">
    <h2><?= t('Link modification') ?></h2>
</div>

<form action="<?= $this->url->href('Admin/LinkController', 'update', ['link_id' => $link['id']]) ?>" method="post" autocomplete="off">

    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('id', $values) ?>

    <?= $this->form->label(t('Label'), 'label') ?>
    <?= $this->form->text('label', $values, $errors, ['required']) ?>

    <?= $this->form->label(t('Opposite label'), 'opposite_id') ?>
    <?= $this->form->select('opposite_id', $labels, $values, $errors) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Admin/LinkController', 'index') ?>
    </div>
</form>
