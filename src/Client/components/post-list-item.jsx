var React = require('react');
var PostVoteButton = require('./post-vote-button.jsx');
var PostLink = require('./post-link.jsx');

var PostListItem = React.createClass({
    render: function() {
        console.log(this.props);
        return (
            <li className="list-group-item">
                <div className="well-card">
                    <div className="row">
                        <div>
                            <PostLink postId={this.props.postId}></PostLink>
                        </div>
                    </div> 
                    <div className="row">
                        <div>
                            <p className="lead-text">
                            {this.props.description}
                            </p>
                        </div>
                    </div>
                    <div className="row">
                        <div>							
                            <PostVoteButton voted={this.props.voted}></PostVoteButton>
                            <span className="vote-count">
                                <small>&nbsp;•&nbsp;</small>
                                <span>{this.props.voteCount}</span>
                            </span>
                            <span className="pull-right date">{this.props.postDate}</span>
                        </div>
                    </div>
                </div>
            </li>
        );
    }
});

module.exports = PostListItem;