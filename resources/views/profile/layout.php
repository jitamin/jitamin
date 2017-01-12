<?php if ($subside_template): ?>
    <?= $this->render($subside_template, ['user' => $user]) ?>
<?php endif ?>
    <?= $content_for_sublayout ?>