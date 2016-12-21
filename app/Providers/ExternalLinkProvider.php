<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Core\ExternalLink\ExternalLinkManager;
use Jitamin\ExternalLink\AttachmentLinkProvider;
use Jitamin\ExternalLink\FileLinkProvider;
use Jitamin\ExternalLink\WebLinkProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * External Link Provider.
 */
class ExternalLinkProvider implements ServiceProviderInterface
{
    /**
     * Register providers.
     *
     * @param \Pimple\Container $container
     *
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['externalLinkManager'] = new ExternalLinkManager($container);
        $container['externalLinkManager']->register(new WebLinkProvider($container));
        $container['externalLinkManager']->register(new AttachmentLinkProvider($container));
        $container['externalLinkManager']->register(new FileLinkProvider($container));

        return $container;
    }
}
