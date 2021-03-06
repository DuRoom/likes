<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace DuRoom\Likes\Listener;

use DuRoom\Likes\Event\PostWasLiked;
use DuRoom\Likes\Event\PostWasUnliked;
use DuRoom\Post\Event\Deleted;
use DuRoom\Post\Event\Saving;

class SaveLikesToDatabase
{
    /**
     * @param Saving $event
     */
    public static function whenPostIsSaving(Saving $event)
    {
        $post = $event->post;
        $data = $event->data;

        if ($post->exists && isset($data['attributes']['isLiked'])) {
            $actor = $event->actor;
            $liked = (bool) $data['attributes']['isLiked'];

            $actor->assertCan('like', $post);

            $currentlyLiked = $post->likes()->where('user_id', $actor->id)->exists();

            if ($liked && ! $currentlyLiked) {
                $post->likes()->attach($actor->id);

                $post->raise(new PostWasLiked($post, $actor));
            } elseif ($currentlyLiked) {
                $post->likes()->detach($actor->id);

                $post->raise(new PostWasUnliked($post, $actor));
            }
        }
    }

    /**
     * @param Deleted $event
     */
    public static function whenPostIsDeleted(Deleted $event)
    {
        $event->post->likes()->detach();
    }
}
