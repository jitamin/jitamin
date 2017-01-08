<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create a cache file for faster configuration loading.
 */
class ConfigCacheCommand extends BaseCommand
{
    /**
     * Configure the console command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('config:cache')
            ->setDescription('Create a cache file for faster configuration loading');
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface  $output
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getFreshConfiguration();

        file_put_contents(
            $this->getCachedConfigPath(), '<?php return '.var_export($config, true).';'.PHP_EOL
        );

        $output->writeln('Configuration cached successfully!');
    }

    /**
     * Boot a fresh copy of the application configuration.
     *
     * @return array
     */
    protected function getFreshConfiguration()
    {
        $config = [];
        foreach (glob(JITAMIN_DIR.'/config/*.php') as $file) {
            if(strrpos($file, '.default.php') !== false) {
                continue;
            }
            $section = str_replace('.php', '', basename($file));
            $config[$section] = require $file;
        }
        
        return $config;
    }

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedConfigPath()
    {
        return __DIR__.'/../../bootstrap/cache/config.php';
    }
}
