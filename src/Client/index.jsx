var React = require('react');
var ReactDOM = require('react-dom');

var PostList = require('./components/post-list.jsx');

ReactDOM.render(<PostList items={[{
    voted: true, 
    voteCount: 321,
    postDate: 'Mar 3',
    description: 'Ovo je post na koji jos korisnik nije glsao. Pise broj glasova i ima mogucnost da se glasa pritoskom na vote up. Ne znam da li da sve objave imaju istu velicinu prozora ili da se one podesavaju u zavisnosti od teksta, mada je svakako broj karaktera ogranicen. najvjerovatnije cu postaviti da je velicina posta ista za svaku objavu.',
    postId: 15
},
{
    voted: false, 
    voteCount: 412,
    postDate: 'Mar 5',
    description: 'Ovo je post na koji jos korisnik nije glsao. Pise broj glasova i ima mogucnost da se glasa pritoskom na vote up. Ne znam da li da sve objave imaju istu velicinu prozora ili da se one podesavaju u zavisnosti od teksta, mada je svakako broj karaktera ogranicen. najvjerovatnije cu postaviti da je velicina posta ista za svaku objavu.',
    postId: 9
}]}></PostList>, document.getElementById('post-list'));