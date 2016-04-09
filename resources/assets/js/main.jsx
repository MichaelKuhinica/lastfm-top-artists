var React = require('react');
var ReactDOM = require('react-dom');
var ReactRouter = require('react-router');
var Router = ReactRouter.Router;
var Route = ReactRouter.Route;
var hashHistory = ReactRouter.hashHistory;

// console.log(countries.getNames());
var FilterContainer = React.createClass({
  render: function() {
    return (
      <div className="row">
        <CountryFilter />
      </div>
    );
  }
});

var CountryFilter = React.createClass({
  handleCountryChange: function(e) {
    this.setState({country: e.target.value});
  },
  handleSubmit: function(e) {
    e.preventDefault();
    hashHistory.push('/artists/'+this.state.country);
  },
  render: function() {
    return (
      <div className="CountryFilter">
        <div className="row">
          <div className="col-md-6 col-md-offset-3">
            <form onSubmit={this.handleSubmit}>
              <div className="form-group col-md-9">
                <label className="sr-only" for="country-filter">Search country by name</label>
                <input
                  id="country-filter"
                  className="form-control"
                  type="text"
                  placeholder="Search country by name"
                  onChange={this.handleCountryChange}
                />
              </div>
              <div className="col-md-3">
                <button type="submit" className="btn btn-default">Search</button>
              </div>
            </form>
          </div>
        </div>
        <div className="row">
          {this.props.children}
        </div>
      </div>
   );
  }
});

var ArtistsList = React.createClass({
  getInitialState: function() {
    return {data: []};
  },
  componentDidMount: function() {
    console.log(this.props.params);
    if(this.props.params.country) {
      //TODO use promise
      this.loadArtists(this.props.params.country);
    }
  },
  loadArtists: function(country) {
    $.ajax({
      url: '/api/v1/artists/top/'+country,
      dataType: 'json',
      cache: true,
      success: function(data) {
        console.log(data);
        this.setState({data: data.topartists.artist});
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  render: function() {
    var artists = this.state.data.map(function(artist) {
      return(
        <ArtistView key={artist.mbid} name={artist.name} thumbnail={artist.image[3]} />
      );
    });
    return(
      <div className="col-md-12 artists-list">
        {artists}
      </div>
    );
  }
});

var ArtistView = React.createClass({
  render: function() {
    return(
      <div className="row artist-row">
        <div className="col-md-3 artist-image">
          <img src={this.props.thumbnail} alt={this.props.name} />
        </div>
        <div className="col-md-9 artist-name">
          {this.props.name}
        </div>
      </div>
    );
  }
});

ReactDOM.render((
  <Router history={hashHistory}>
    <Route path="/" component={CountryFilter}>
      <Route path="/artists/:country" component={ArtistsList}/>
    </Route>
  </Router>
), document.getElementById('app'));
