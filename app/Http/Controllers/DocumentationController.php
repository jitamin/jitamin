<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

use Parsedown;

/**
 * Documentation Viewer.
 */
class DocumentationController extends BaseController
{
    public function show()
    {
        $page = $this->request->getStringParam('file', 'index');

        if (!preg_match('/^[a-z0-9\-]+/', $page)) {
            $page = 'index';
        }

        $filename = $this->getPageFilename($page);
        $this->response->html($this->helper->layout->app('doc/show', $this->render($filename)));
    }

    /**
     * Display keyboard shortcut.
     */
    public function shortcuts()
    {
        $this->response->html($this->template->render('config/keyboard_shortcuts'));
    }

    /**
     * Prepare Markdown file.
     *
     * @param string $filename
     *
     * @return array
     */
    private function render($filename)
    {
        $data = file_get_contents($filename);
        $content = preg_replace_callback('/\((.*.markdown)\)/', [$this, 'replaceMarkdownUrl'], $data);
        $content = preg_replace_callback('/\((screenshots.*\.png)\)/', [$this, 'replaceImageUrl'], $content);

        list($title) = explode("\n", $data, 2);

        return [
            'content' => Parsedown::instance()->text($content),
            'title'   => $title !== 'Documentation' ? t('Documentation: %s', $title) : $title,
        ];
    }

    /**
     * Regex callback to replace Markdown links.
     *
     * @param array $matches
     *
     * @return string
     */
    public function replaceMarkdownUrl(array $matches)
    {
        return '('.$this->helper->url->to('DocumentationController', 'show', ['file' => str_replace('.markdown', '', $matches[1])]).')';
    }

    /**
     * Regex callback to replace image links.
     *
     * @param array $matches
     *
     * @return string
     */
    public function replaceImageUrl(array $matches)
    {
        return '('.$this->getFileBaseUrl($matches[1]).')';
    }

    /**
     * Get Markdown file according to the current language.
     *
     * @param string $page
     *
     * @return string
     */
    private function getPageFilename($page)
    {
        return $this->getFileLocation($page.'.markdown') ?:
            implode(DIRECTORY_SEPARATOR, [ROOT_DIR, 'doc', 'index.markdown']);
    }

    /**
     * Get base URL for Markdown links.
     *
     * @param string $filename
     *
     * @return string
     */
    private function getFileBaseUrl($filename)
    {
        $language = $this->languageModel->getCurrentLanguage();
        $path = $this->getFileLocation($filename);

        if (strpos($path, $language) !== false) {
            $url = implode('/', ['doc', $language, $filename]);
        } else {
            $url = implode('/', ['doc', $filename]);
        }

        return $this->helper->url->base().$url;
    }

    /**
     * Get file location according to the current language.
     *
     * @param string $filename
     *
     * @return string
     */
    private function getFileLocation($filename)
    {
        $files = [
            implode(DIRECTORY_SEPARATOR, [ROOT_DIR, 'doc', $this->languageModel->getCurrentLanguage(), $filename]),
            implode(DIRECTORY_SEPARATOR, [ROOT_DIR, 'doc', $filename]),
        ];

        foreach ($files as $filename) {
            if (file_exists($filename)) {
                return $filename;
            }
        }

        return '';
    }
}
