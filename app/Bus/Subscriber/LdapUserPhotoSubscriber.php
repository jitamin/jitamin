<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Subscriber;

use Jitamin\Bus\Event\UserProfileSyncEvent;
use Jitamin\Foundation\Identity\UserProfile;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LdapUserPhotoSubscriber.
 */
class LdapUserPhotoSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    /**
     * Get event listeners.
     *
     * @static
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserProfile::EVENT_USER_PROFILE_AFTER_SYNC => 'syncUserPhoto',
        ];
    }

    /**
     * Save the user profile photo from LDAP to the object storage.
     *
     * @param UserProfileSyncEvent $event
     */
    public function syncUserPhoto(UserProfileSyncEvent $event)
    {
        if (is_a($event->getUser(), 'Jitamin\Services\Identity\LdapUserProvider')) {
            $profile = $event->getProfile();
            $photo = $event->getUser()->getPhoto();

            if (empty($profile['avatar_path']) && !empty($photo)) {
                $this->logger->info('Saving user photo from LDAP profile');
                $this->avatarModel->uploadImageContent($profile['id'], $photo);
            }
        }
    }
}
