var React = require('react');
var PostInput = require('./post-input.jsx');
var PostListItem = require('./post-list-item.jsx');

var PostList = React.createClass({
    render: function() {
        return (
            <ul className="list-group">
                <li className="list-group-item">      
                    <PostInput></PostInput>
                </li>
                {this.props.items.map(function(val) { 
                        return <PostListItem {...val}></PostListItem>
                })}                
            </ul>
        );
    }
});

module.exports = PostList;