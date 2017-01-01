<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core;

/**
 * Template.
 */
class Template
{
    /**
     * Helper object.
     *
     * @var Helper
     */
    private $helper;

    /**
     * List of template overrides.
     *
     * @var array
     */
    private $overrides = [];

    /**
     * Template constructor.
     *
     * @param Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Expose helpers with magic getter.
     *
     * @param string $helper
     *
     * @return mixed
     */
    public function __get($helper)
    {
        return $this->helper->getHelper($helper);
    }

    /**
     * Render a template.
     *
     * Example:
     *
     * $template->render('template_name', ['bla' => 'value']);
     *
     * @param string $__template_name Template name
     * @param array  $__template_args Key/Value map of template variables
     *
     * @return string
     */
    public function render($__template_name, array $__template_args = [])
    {
        extract($__template_args);
        ob_start();
        include $this->getTemplateFile($__template_name);

        return ob_get_clean();
    }

    /**
     * Define a new template override.
     *
     * @param string $original_template
     * @param string $new_template
     */
    public function setTemplateOverride($original_template, $new_template)
    {
        $this->overrides[$original_template] = $new_template;
    }

    /**
     * Find template filename.
     *
     * Core template: 'task/show' or 'jitamin:task/show'
     * Plugin template: 'myplugin:task/show'
     *
     * @param string $template
     *
     * @return string
     */
    public function getTemplateFile($template)
    {
        $plugin = '';
        $template = isset($this->overrides[$template]) ? $this->overrides[$template] : $template;

        if (strpos($template, ':') !== false) {
            list($plugin, $template) = explode(':', $template);
        }

        if ($plugin !== 'jitamin' && $plugin !== '') {
            return implode(DIRECTORY_SEPARATOR, [PLUGINS_DIR, ucfirst($plugin), 'resources', 'views', $template.'.php']);
        }

        return implode(DIRECTORY_SEPARATOR, [JITAMIN_DIR, 'resources', 'views', $template.'.php']);
    }
}
