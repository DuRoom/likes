import app from 'duroom/forum/app';
import Modal from 'duroom/common/components/Modal';
import Link from 'duroom/common/components/Link';
import avatar from 'duroom/common/helpers/avatar';
import username from 'duroom/common/helpers/username';

export default class PostLikesModal extends Modal {
  className() {
    return 'PostLikesModal Modal--small';
  }

  title() {
    return app.translator.trans('duroom-likes.forum.post_likes.title');
  }

  content() {
    return (
      <div className="Modal-body">
        <ul className="PostLikesModal-list">
          {this.attrs.post.likes().map((user) => (
            <li>
              <Link href={app.route.user(user)}>
                {avatar(user)} {username(user)}
              </Link>
            </li>
          ))}
        </ul>
      </div>
    );
  }
}
