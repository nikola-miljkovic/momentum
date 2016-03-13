var React = require('react');
var classNames = require('classnames');

var PostField = React.createClass({
    getInitialState: function() {
       return {
           focused: false,
           inputDivClasses: classNames({
               'post-input': true,
               'post-input-active': false
           })
       };
    }, 
    onFocus: function() {
        this.setState({
           focused: true,
           inputDivClasses: classNames({
               'post-input': true,
               'post-input-active': true
           })
       });
    },
    onBlur: function() {
        this.setState(this.getInitialState());
    },
    onKeyDown: function(event) {
        console.log(event);
    },
    render: function() {
        var buttonStyle = this.state.focused ? 
        {
            visibility:  'visible',
            display: 'block'
        } : 
        {
            visibility: 'hidden',
            display: 'none'
        };
        
        return (
            <div>
                <div className="post-placeholder">
                    Share your problem, suggestion or wish...
                </div>
                <div className={this.state.inputDivClasses} contentEditable={true} 
                    onBlur={this.onBlur} onFocus={this.onFocus} onKeyDown={this.onKeyDown}/>
                <div className="row" style={buttonStyle}>
                    <button type="submit" className="post-button pull-right">
                        <span className="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                        Post!
                    </button>
                </div> 
            </div>
        );
    }
});

module.exports = PostField;