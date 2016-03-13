var React = require('react');

var PostLink = React.createClass({
    render: function() {
        var postId = this.props.postId;
        var postLink = '/post/' + postId;
        
        return (
            <a className="pull-left post-link" href={postLink}><small>#{postId}</small></a>
        )
    }
});

module.exports = PostLink;