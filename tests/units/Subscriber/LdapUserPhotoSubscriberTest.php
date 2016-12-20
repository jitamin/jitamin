<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Bus\Event\UserProfileSyncEvent;
use Hiject\Bus\Subscriber\LdapUserPhotoSubscriber;
use Hiject\Core\Security\Role;
use Hiject\Model\UserModel;
use Hiject\Services\Identity\DatabaseUserProvider;
use Hiject\Services\Identity\LdapUserProvider;

require_once __DIR__.'/../Base.php';

class LdapUserPhotoSubscriberTest extends Base
{
    public function testWhenTheProviderIsNotLdap()
    {
        $userProvider = new DatabaseUserProvider([]);
        $subscriber = new LdapUserPhotoSubscriber($this->container);
        $userModel = new UserModel($this->container);

        $userModel->update(['id' => 1, 'avatar_path' => 'my avatar']);
        $user = $userModel->getById(1);

        $subscriber->syncUserPhoto(new UserProfileSyncEvent($user, $userProvider));

        $user = $userModel->getById(1);
        $this->assertEquals('my avatar', $user['avatar_path']);
    }

    public function testWhenTheUserHaveLdapPhoto()
    {
        $userProvider = new LdapUserProvider('dn', 'admin', 'Admin', 'admin@localhost', Role::APP_ADMIN, [], 'my photo');
        $subscriber = new LdapUserPhotoSubscriber($this->container);
        $userModel = new UserModel($this->container);
        $user = $userModel->getById(1);

        $this->container['objectStorage']
            ->expects($this->once())
            ->method('put')
            ->with($this->anything(), 'my photo');

        $subscriber->syncUserPhoto(new UserProfileSyncEvent($user, $userProvider));

        $user = $userModel->getById(1);
        $this->assertStringStartsWith('avatars', $user['avatar_path']);
    }

    public function testWhenTheUserDoNotHaveLdapPhoto()
    {
        $userProvider = new LdapUserProvider('dn', 'admin', 'Admin', 'admin@localhost', Role::APP_ADMIN, []);
        $subscriber = new LdapUserPhotoSubscriber($this->container);
        $userModel = new UserModel($this->container);
        $user = $userModel->getById(1);

        $this->container['objectStorage']
            ->expects($this->never())
            ->method('put');

        $subscriber->syncUserPhoto(new UserProfileSyncEvent($user, $userProvider));

        $user = $userModel->getById(1);
        $this->assertEmpty($user['avatar_path']);
    }

    public function testWhenTheUserAlreadyHaveAvatar()
    {
        $userProvider = new LdapUserProvider('dn', 'admin', 'Admin', 'admin@localhost', Role::APP_ADMIN, [], 'my photo');
        $subscriber = new LdapUserPhotoSubscriber($this->container);
        $userModel = new UserModel($this->container);

        $userModel->update(['id' => 1, 'avatar_path' => 'my avatar']);
        $user = $userModel->getById(1);

        $this->container['objectStorage']
            ->expects($this->never())
            ->method('put');

        $subscriber->syncUserPhoto(new UserProfileSyncEvent($user, $userProvider));

        $user = $userModel->getById(1);
        $this->assertEquals('my avatar', $user['avatar_path']);
    }
}
