<?php

/*
 * This file is part of DuRoom.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

use DuRoom\Api\Controller;
use DuRoom\Api\Serializer\BasicUserSerializer;
use DuRoom\Api\Serializer\PostSerializer;
use DuRoom\Extend;
use DuRoom\Likes\Event\PostWasLiked;
use DuRoom\Likes\Event\PostWasUnliked;
use DuRoom\Likes\Listener;
use DuRoom\Likes\Notification\PostLikedBlueprint;
use DuRoom\Post\Event\Deleted;
use DuRoom\Post\Event\Saving;
use DuRoom\Post\Post;
use DuRoom\User\User;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    (new Extend\Model(Post::class))
        ->belongsToMany('likes', User::class, 'post_likes', 'post_id', 'user_id'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Notification())
        ->type(PostLikedBlueprint::class, PostSerializer::class, ['alert']),

    (new Extend\ApiSerializer(PostSerializer::class))
        ->hasMany('likes', BasicUserSerializer::class)
        ->attribute('canLike', function (PostSerializer $serializer, $model) {
            return (bool) $serializer->getActor()->can('like', $model);
        }),

    (new Extend\ApiController(Controller\ShowDiscussionController::class))
        ->addInclude('posts.likes'),

    (new Extend\ApiController(Controller\ListPostsController::class))
        ->addInclude('likes'),
    (new Extend\ApiController(Controller\ShowPostController::class))
        ->addInclude('likes'),
    (new Extend\ApiController(Controller\CreatePostController::class))
        ->addInclude('likes'),
    (new Extend\ApiController(Controller\UpdatePostController::class))
        ->addInclude('likes'),

    (new Extend\Event())
        ->listen(PostWasLiked::class, Listener\SendNotificationWhenPostIsLiked::class)
        ->listen(PostWasUnliked::class, Listener\SendNotificationWhenPostIsUnliked::class)
        ->listen(Deleted::class, [Listener\SaveLikesToDatabase::class, 'whenPostIsDeleted'])
        ->listen(Saving::class, [Listener\SaveLikesToDatabase::class, 'whenPostIsSaving']),
];
