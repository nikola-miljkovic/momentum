var React = require('react');
var classNames = require('classnames');

var PostField = React.createClass({
    getInitialState: function() {
       return {
           isFocused: false,
           isEmpty: true
       };
    }, 
    onFocus: function() {
        this.setState({
            isFocused: true
        });
    },
    onBlur: function() {
        this.setState({
            isFocused: false
        });
    },
    onInput: function() {
        console.log(this.getContent());
        this.setState({
            isEmpty: this.getContent().length === 0
        });
    },
    onSubmit: function(event) {
        event.preventDefault();

        var form = {
            'form[content]': this.getContent(),
            'form[submit]': ''
        };

        jQuery.post('/ajax/post', form, function(data, status) {
            console.log(status);
            console.log(data);
        }, 'json');
    },
    onClickPlaceholder: function() {
        this.refs['content'].focus();
    },
    getContent: function() {
        if (this.refs['content'] === undefined) return '';
        return this.refs['content'].innerHTML;
    },
    render: function() {
        console.log('pzzz');
        var placeholder = this.state.isEmpty ? 'Share your problem, suggestion or wish...' : '';
        var isFocusedOrNotEmpty = this.state.isFocused || !this.state.isEmpty;
        var buttonStyle = {
            visibility:  isFocusedOrNotEmpty ? 'visible' : 'hidden',
            display: isFocusedOrNotEmpty ? 'block' : 'none'
        };
        var inputClasses = classNames({
            'post-input': true,
            'post-input-active': isFocusedOrNotEmpty
        });

        return (
            <form
                onSubmit={this.onSubmit}
                onBlur={this.onBlur}
                onFocus={this.onFocus}
            >
                <div
                    className="post-placeholder"
                    unselectable="on"
                    onClick={this.onClickPlaceholder}
                >
                    {placeholder}
                </div>
                <div
                    ref="content"
                    contentEditable="true"
                    onInput={this.onInput}
                    className={inputClasses}
                >
                </div>
                <div className="row" style={buttonStyle}>
                    <button type="submit" className="post-button pull-right disabled">
                        <span className="glyphicon glyphicon-pencil" aria-hidden="true"/>
                        Post!
                    </button>
                </div>
            </form>
        );
    }
});

module.exports = PostField;