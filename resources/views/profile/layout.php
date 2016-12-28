<section id="main">

    <section class="page-container" id="user-section">
        <div class="page-content">
             <?= $this->render('profile/_partials/subnav', ['user' => $user]) ?>
            <?= $content_for_sublayout ?>
        </div>
        <?= $this->render('profile/subside', ['user' => $user]) ?>
    </section>
</section>
