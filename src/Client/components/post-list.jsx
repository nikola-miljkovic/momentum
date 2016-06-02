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
    render: function() {
        var input = null;
        if (this.state.loggedIn === true) {
          input = <li className="list-group-item">
            <PostInput></PostInput>
          </li>;
        }

        var list = loader;
        if (this.state.loaded === true) {
          list = this.state.posts.map(function(post) {
            return <PostListItem key={post.id} onDelete={this.onDeletePost.bind(this, post.id)} {...post} />
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