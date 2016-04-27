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
        this.props.onClick();
    },
    render: function() {
        var voteString = this.state.voted ? "Voted!" : "Vote up!";
        var buttonClass = classNames({
            'upvote-button': true,
            'voted': this.state.voted
        });
        
        return (
            <a
                href="#"
                className={buttonClass}
                onClick={this.onClick}
            >
                <span className="glyphicon glyphicon-chevron-up" aria-hidden="true"/>
                <span style={{padding: '0em 0.4em'}}>{voteString}</span>
            </a>
        )
    }
});

module.exports = VoteButton;