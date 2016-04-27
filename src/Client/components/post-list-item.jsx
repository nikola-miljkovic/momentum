var React = require('react');
var PostVoteButton = require('./post-vote-button.jsx');
var PostLink = require('./post-link.jsx');

var PostListItem = React.createClass({
    onVote: function(id) {
        $.post('/ajax/post_vote/' + id, function(data) {
            if(data.done === true)
            {
            }
        });
    },
    render: function() {
        return (
            <li className="list-group-item">
                <div className="well-card">
                    <div className="row">
                        <div>
                            <PostLink id={this.props.id}></PostLink>
                        </div>
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
                            <PostVoteButton voted={this.props.voted} onClick={this.onVote.bind(this, this.props.id)}></PostVoteButton>
                            <span className="vote-count">
                                <small>•</small>
                                <span style={{padding: '0em 0.4em'}}>{this.props.voteCount}</span>
                            </span>
                            <span className="pull-right date">{this.props.date}</span>
                        </div>
                    </div>
                </div>
            </li>
        );
    }
});

module.exports = PostListItem;