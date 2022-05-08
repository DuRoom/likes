import { extend } from 'duroom/common/extend';
import app from 'duroom/forum/app';
import Post from 'duroom/common/models/Post';
import Model from 'duroom/common/Model';
import NotificationGrid from 'duroom/forum/components/NotificationGrid';

import addLikeAction from './addLikeAction';
import addLikesList from './addLikesList';
import PostLikedNotification from './components/PostLikedNotification';

app.initializers.add('duroom-likes', () => {
  app.notificationComponents.postLiked = PostLikedNotification;

  Post.prototype.canLike = Model.attribute('canLike');
  Post.prototype.likes = Model.hasMany('likes');

  addLikeAction();
  addLikesList();

  extend(NotificationGrid.prototype, 'notificationTypes', function (items) {
    items.add('postLiked', {
      name: 'postLiked',
      icon: 'far fa-thumbs-up',
      label: app.translator.trans('duroom-likes.forum.settings.notify_post_liked_label'),
    });
  });
});
