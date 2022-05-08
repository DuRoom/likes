<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Likes\Listener;

use DuRoom\Likes\Event\PostWasUnliked;
use DuRoom\Likes\Notification\PostLikedBlueprint;
use DuRoom\Notification\NotificationSyncer;

class SendNotificationWhenPostIsUnliked
{
    /**
     * @var NotificationSyncer
     */
    protected $notifications;

    /**
     * @param NotificationSyncer $notifications
     */
    public function __construct(NotificationSyncer $notifications)
    {
        $this->notifications = $notifications;
    }

    public function handle(PostWasUnliked $event)
    {
        if ($event->post->user && $event->post->user->id != $event->user->id) {
            $this->notifications->sync(
                new PostLikedBlueprint($event->post, $event->user),
                []
            );
        }
    }
}
