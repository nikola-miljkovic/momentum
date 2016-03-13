var React = require('react');
var classNames = require('classnames');

var VoteButton = React.createClass({
    getInitialState: function() {
        return {
            voted: this.props.voted
        }
    },
    onClick: function(event) {
        event.preventDefault();
        var newState = {
            voted: !this.state.voted
        }
        
        this.setState(newState);
    },
    render: function() {
        var voteString = this.state.voted ? "Voted!" : "Vote up!";
        var aClass = classNames({
            'upvote-button': true,
            'voted': this.state.voted
        });
        
        return (
            <a href="#" className={aClass} onClick={this.onClick}>
                <span className="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                <span>&nbsp;{voteString}</span>
            </a>
        )
    }
});

module.exports = VoteButton;