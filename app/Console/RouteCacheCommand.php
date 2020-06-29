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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create a cache file for faster route loading.
 */
class RouteCacheCommand extends BaseCommand
{
    protected $routes = [];

    /**
     * Configure the console command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('route:cache')
            ->setDescription('Create a route cache file for faster route registration');
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFreshRoutes();

        foreach ($this->routes as $path => $entry) {
            list($controller, $action) = explode('@', $entry);
            $this->container['route']->addRoute($path, $controller, $action);
        }

        file_put_contents(
            $this->getCachedRoutesPath(),
            '<?php return '.var_export($this->container['route']->getRouteData(), true).';'.PHP_EOL
        );

        $output->writeln('Routes cached successfully!');
    }

    /**
     * Boot a fresh copy of the application configuration.
     *
     * @return array
     */
    protected function getFreshRoutes()
    {
        foreach (glob(JITAMIN_DIR.'/routes/*.php') as $file) {
            $this->routes = array_merge($this->routes, require $file);
        }
    }

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedRoutesPath()
    {
        return __DIR__.'/../../bootstrap/cache/routes.php';
    }
}
