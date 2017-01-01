<div class="page-header">
    <ul>
        <li>
            <i class="fa fa-life-ring fa-fw"></i>
            <?= $this->url->link(t('Table of contents'), 'DocumentationController', 'show', ['file' => 'index']) ?>
        </li>
    </ul>
</div>
<div class="markdown documentation">
    <?= $content ?>
</div>
