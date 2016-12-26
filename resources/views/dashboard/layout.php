<section id="main">
    <section class="page-container" id="dashboard">
        <?= $this->render($subside_template, ['user' => $user]) ?>
        <div class="page-content">
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
