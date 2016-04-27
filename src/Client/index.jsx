var React = require('react');
var ReactDOM = require('react-dom');
var PostList = require('./components/post-list.jsx');

// load server rendered javascript
var postSource = window.postSource;

ReactDOM.render(
    <PostList source={postSource} />,
    document.getElementById('post-list')
);
