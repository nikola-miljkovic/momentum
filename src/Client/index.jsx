var React = require('react');
var ReactDOM = require('react-dom');
var PostList = require('./components/post-list.jsx');

// load server rendered javascript
var postSource = window.postSource;
var isGovernment = window.isGovernment;
var loginUser = window.loginUser;

ReactDOM.render(
    <PostList source={postSource} user={loginUser}/>,
    document.getElementById('post-list')
);
