var React = require('react');
var classNames = require('classnames');

var PostField = React.createClass({
    getInitialState: function() {
       return {
           focused: true,
           inputDivClasses: classNames({
               'post-input': true,
               'post-input-active': true
           })
       };
    }, 
    onFocus: function() {
        /*this.setState({
           focused: true,
           inputDivClasses: classNames({
               'post-input': true,
               'post-input-active': true
           })
       });*/
    },
    onBlur: function() {
        //this.setState(this.getInitialState());
    },
    onSubmit: function(event) {
        event.preventDefault();

        var contentNode = this.refs['content'];
        var form = {
            'form[content]': contentNode.innerHTML,
            'form[submit]': ''
        };

        jQuery.post('/ajax/post', form, function(data, status) {
            console.log(status);
            console.log(data);
        }, 'json');
    },
    render: function() {
        var placeholder = 'Share your problem, suggestion or wish...';
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
            <form onBlur={this.onBlur}
                  onFocus={this.onFocus}
                  onSubmit={this.onSubmit}>
                <div ref="placeholder" className="post-placeholder">
                    { placeholder }
                </div>
                <div ref="content" contentEditable="true" className={this.state.inputDivClasses}>
                </div>
                <div className="row" style={buttonStyle}>
                    <button type="submit" className="post-button pull-right">
                        <span className="glyphicon glyphicon-pencil" aria-hidden="true"/>
                        Post!
                    </button>
                </div> 
            </form>
        );
    }
});

module.exports = PostField;