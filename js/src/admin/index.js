import app from 'duroom/admin/app';

app.initializers.add('duroom-likes', () => {
  app.extensionData.for('duroom-likes').registerPermission(
    {
      icon: 'far fa-thumbs-up',
      label: app.translator.trans('duroom-likes.admin.permissions.like_posts_label'),
      permission: 'discussion.likePosts',
    },
    'reply'
  );
});
