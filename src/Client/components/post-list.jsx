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
    render: function() {
        var list = this.state.loaded === true ?
            this.state.posts.map(function(post) {
                return <PostListItem key={post.id} {...post} />
            }) : loader;
        
        return (
            <ul className="list-group">
                <li className="list-group-item">
                    <PostInput></PostInput>
                </li>
                {list}
            </ul>
        );
    }
});

module.exports = PostList;