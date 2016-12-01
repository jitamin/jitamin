<?php if ($is_outdated): ?>
<div class="alert alert-success alert-dismissible" id="update-available">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<h4><i class="fa fa-checkmark"></i> <?= t('An update is available!') ?></h4>
<?= e('You are running an out of date release %s, there is an updated release <a href="%s" rel="noreferer">%s</a> available!', $current_version, 'https://github.com/hiject/hiject/releases/latest', $latest_version) ?>
</div>
<?php endif ?>