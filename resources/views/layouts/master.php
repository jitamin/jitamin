<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="robots" content="noindex,nofollow">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="referrer" content="no-referrer">

        <?php if (isset($board_public_refresh_interval)): ?>
            <meta http-equiv="refresh" content="<?= $board_public_refresh_interval ?>">
        <?php endif ?>

        <?= $this->asset->colorCss() ?>
        <?= $this->asset->css('assets/css/vendor.min.css') ?>
        <?= $this->asset->css('assets/css/app.min.css') ?>
        <?= $this->asset->customCss() ?>

        <?php if (!isset($not_editable)): ?>
            <?= $this->asset->js('assets/js/bootstrap.min.js') ?>
            <?= $this->asset->js('assets/js/base.min.js') ?>
            <?= $this->asset->js('assets/js/extra.min.js') ?>
            <?= $this->asset->js('assets/js/app.min.js') ?>
        <?php endif ?>

        <?= $this->hook->asset('css', 'template:layout:css') ?>
        <?= $this->hook->asset('js', 'template:layout:js') ?>

        <link rel="icon" type="image/png" href="<?= $this->url->dir() ?>assets/img/favicon.ico">

        <title>
            <?php if (isset($page_title)): ?>
                <?= $this->text->e($page_title) ?>
            <?php elseif (isset($title)): ?>
                <?= $this->text->e($title) ?>
            <?php else: ?>
                Jitamin
            <?php endif ?>
            - <?= $this->app->setting('application_name') ?: 'Jitamin' ?>
        </title>

        <?= $this->hook->render('template:layout:head') ?>
    </head>
    <body class="skin-<?= $this->app->getSkin() ?> <?= $this->app->getLayout() ?>"
          data-status-url="<?= $this->url->href('UserAjaxController', 'status') ?>"
          data-login-url="<?= $this->url->href('AuthController', 'login') ?>"
          data-keyboard-shortcut-url="<?= $this->url->href('DocumentationController', 'shortcuts') ?>"
          data-timezone="<?= $this->app->getTimezone() ?>"
          data-js-lang="<?= $this->app->jsLang() ?>"
          data-js-date-format="<?= $this->app->getJsDateFormat() ?>"
          data-js-time-format="<?= $this->app->getJsTimeFormat() ?>"
    >

    <?php if (isset($no_layout) && $no_layout): ?>
        <?= $content_for_layout ?>
    <?php else: ?>
        <div class="wrapper">
        <?= $this->render('_partials/sidebar', [
        ]) ?>
        <div class="content-panel">
        <?= $this->hook->render('template:layout:top') ?>
        <?= $this->render('_partials/nav', [
            'title' => $title,
            'page_title' => isset($page_title) ? $page_title : null,
            'project' => isset($project) ? $project : null,
            'task'        => isset($task) ? $task : null,
            'description' => isset($description) ? $description : null,
        ]) ?>
        <section class="page">
            <?= $this->app->flashMessage() ?>
            <?= $content_for_layout ?>
        </section>
        <?= $this->render('_partials/footer', [
        ]) ?>
        <?= $this->hook->render('template:layout:bottom') ?>
        </div>
        </div>
    <?php endif ?>
    </body>
</html>
