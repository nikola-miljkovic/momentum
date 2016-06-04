var React = require('react');
var PostInput = require('./post-input.jsx');
var PostListItem = require('./post-list-item.jsx');
var Spinner = require('react-spinkit');

var loader =
    <li className="list-group-item">
        <div className="text-center" style={{marginTop: '1.5em', marginBottom: '1.5em'}}>
            <Spinner spinnerName='wordpress' noFadeIn/>
        </div>
    </li>;

var PostList = React.createClass({
    getInitialState: function() {
        return {
            loaded: false,
            loggedIn: this.props.user >= 0,
            posts: []
        };
    },
    componentDidMount: function() {
        this.serverRequest = $.get(this.props.source, function (result) {
            this.setState({
                loaded: true,
                posts: JSON.parse(result)
            })
        }.bind(this));
    },
    componentWillUnmount: function() {
        this.serverRequest.abort();
    },
    onDeletePost: function(id, e) {
      e.preventDefault();
      $.post('/ajax/post_delete/' + id, function(data) {
          var deleted = JSON.parse(data);
          if (deleted.deleted === true) {
              this.setState({
                  posts: this.state.posts.filter(function(post) {
                    return post.id !== id;
                  })
              });
          }
        }.bind(this)
      );
    },
    onClickDone: function(id, e) {
      e.preventDefault();
      $.post('/government/set_is_done/' + id, function(data) {
        var promoted = JSON.parse(data);
        if (promoted.promoted === true) {
          this.setState({
            posts: this.state.posts.filter(function(post) {
              return post.id !== id;
            })
          });
        }
        }.bind(this)
      );
    },
    onClickInProgress: function(id, e) {
      e.preventDefault();
      $.post('/government/set_in_progress/' + id, function(data) {
        var promoted = JSON.parse(data);
        if (promoted.promoted === true) {
          this.setState({
            posts: this.state.posts.filter(function(post) {
              return post.id !== id;
            })
          });
        }
        }.bind(this)
      );
    },
    onSubmitPost: function(data) {
      var newPost = JSON.parse(data);
      if (newPost.message !== undefined) {
        // TODO: process error message
      } else {
        console.log(newPost);
        newPost.posted = '1';

        this.state.posts.unshift(newPost);
        this.setState({
          posts: this.state.posts
        });
      }
    },
    render: function() {
        var input = null;
        if (this.state.loggedIn === true) {
          input = <li className="list-group-item">
            <PostInput onSubmitPost={this.onSubmitPost}></PostInput>
          </li>;
        }

        var list = loader;
        if (this.state.loaded === true) {
          list = this.state.posts.map(function(post) {
            return <PostListItem
              key={post.id}
              onDelete={this.onDeletePost.bind(this, post.id)}
              onClickDone={this.onClickDone.bind(this, post.id)}
              onClickInProgress={this.onClickInProgress.bind(this, post.id)}
              {...post}
            />;
          }.bind(this));
        }

        return (
            <ul className="list-group">
                {input}
                {list}
            </ul>
        );
    }
});

module.exports = PostList;