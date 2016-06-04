var React = require('react');
var PostVoteButton = require('./post-vote-button.jsx');
var PostLink = require('./post-link.jsx');

var PostListItem = React.createClass({
    getInitialState: function() {
        return {
            voted: this.props.voted,
            voteCount: this.props.voteCount
        }
    },
    onVote: function(id) {
        $.post('/ajax/post_vote/' + id, function(data) {
            var newPostData = JSON.parse(data);
            this.setState({
                voted: newPostData.voted,
                voteCount: newPostData.voteCount
            })
        }.bind(this)
        );
    },
    render: function() {
        var button = null;
        if (window.isGovernment === true) {
            if (window.currentRoute === '_active') {
                button = <a href='#' onClick={this.props.onClickDone}>Set Done</a>;
            } else if (window.currentRoute === '_index' || window.currentRoute === '_popular') {
                button = <a href='#' onClick={this.props.onClickInProgress}>In progress</a>;
            }
        } if (this.props.posted === '1') {
            button = <a href='#' onClick={this.props.onDelete}>DELETE</a>;
        }

        var voteButton = null;
        if (this.props.loggedIn) {
            voteButton = (
              <span>
                <PostVoteButton voted={this.state.voted} onClick={this.onVote.bind(this, this.props.id)}></PostVoteButton>
                <span className="vote-count">
                    <small>•</small>
                    <span style={{padding: '0em 0.4em'}}>{this.state.voteCount}</span>
                </span>
              </span>
            );
        }

        return (
            <li className="list-group-item">
                <div className="well-card">
                    <div className="row">
                        <div>
                            <PostLink id={this.props.id}></PostLink>
                        </div>
                        {button}
                    </div>
                    <div className="row">
                        <div>
                            <p className="lead-text">
                            {this.props.content}
                            </p>
                        </div>
                    </div>
                    <div className="row">
                        <div>							
                            {voteButton}
                            <span className="pull-right date">{this.props.date}</span>
                        </div>
                    </div>
                </div>
            </li>
        );
    }
});

module.exports = PostListItem;