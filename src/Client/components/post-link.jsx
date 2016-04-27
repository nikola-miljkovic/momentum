var React = require('react');

var PostLink = React.createClass({
    render: function() {
        var id = this.props.id;
        var postLink = '/post/' + id;
        
        return (
            <a className="pull-left post-link" href={postLink}><small>#{id}</small></a>
        )
    }
});

module.exports = PostLink;