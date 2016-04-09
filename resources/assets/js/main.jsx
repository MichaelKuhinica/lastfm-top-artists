var React = require('react');
var ReactDOM = require('react-dom');
var ReactRouter = require('react-router');
var Router = ReactRouter.Router;
var Route = ReactRouter.Route;
var browserHistory = ReactRouter.browserHistory;
var Link = ReactRouter.Link;
var Loader = require('react-loader');

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
    browserHistory.push('/artists/'+this.state.country);
  },
  render: function() {
    return (
      <div className="CountryFilter">
        <div className="row">
          <div className="col-md-6 col-md-offset-3 col-xs-6">
            <form onSubmit={this.handleSubmit}>
              <div className="form-group col-md-9">
                <label className="sr-only" for="country-filter">Search country by name</label>
                <input
                  id="country-filter"
                  className="form-control"
                  type="text"
                  placeholder="Search country by name"
                  defaultValue={this.props.params.country}
                  onChange={this.handleCountryChange}
                />
              </div>
              <div className="col-md-3 col-xs-3">
                <button type="submit" className="btn btn-default">Search</button>
              </div>
            </form>
          </div>
        </div>
        {this.props.children}
      </div>
   );
  }
});

var ArtistsList = React.createClass({
  getInitialState: function() {
    return {
      data: [],
      current: this.props.params.page || 1,
      total: 0,
      loaded: false
    };
  },
  componentDidMount: function() {
    //TODO use promise
    this.loadArtists(this.props);
  },
  componentWillReceiveProps: function(nextProps) {
    //TODO use promise
    this.loadArtists(nextProps);
    this.setState({current: nextProps.params.page});
  },
  loadArtists: function(props) {
    if(props.params.country) {
      let endpointUrl = '/api/v1/artists/top/'+props.params.country;
      if(props.params.page) {
        endpointUrl += '/?page='+props.params.page;
      }
      $.ajax({
        url: endpointUrl,
        dataType: 'json',
        cache: true,
        success: function(data) {
          this.setState({
            data: data.topartists.artist,
            total: data.topartists['@attributes'].total,
            loaded: true
          });
        }.bind(this),
        error: function(xhr, status, err) {
          let errorText = err.toString();
          if(xhr.responseJSON && xhr.responseJSON.error) {
            errorText = xhr.responseJSON.error;
          }
          this.setState({loaded: true});
          alert('Error loading artists: '+errorText);
        }.bind(this)
      });
    }
  },
  render: function() {
    var artists = this.state.data.map(function(artist) {
      return(
        <ArtistView key={artist.mbid} name={artist.name} mbid={artist.mbid} thumbnail={artist.image[3]} />
      );
    });
    return(
      <div className="artistsList">
        <Loader loaded={this.state.loaded}>
          <div className="row">
            <div className="col-md-12 artists-list">
              {artists}
            </div>
          </div>
          <div className="row paginate">
            <div className="col-md-6 col-md-offset-4">
              <Pagination baseUrl={`/artists/${this.props.params.country}`} currentPage={this.state.current} perPage="5" totalRecords={this.state.total} />
            </div>
          </div>
        </Loader>
      </div>
    );
  }
});

var Pagination = React.createClass({
  range: function(start, stop) {
    if (arguments.length <= 1) {
      stop = start || 0;
      start = 0;
    }

    var length = Math.max(stop - start, 0);
    var idx = 0;
    var arr = new Array(length);

    while(idx < length) {
      arr[idx++] = start;
      start += 1;
    }

    return arr;
  },
  getPageRange: function() {
		var displayCount = 5;
    var page = this.props.currentPage;

    // Check position of cursor, zero based
    var idx = (page - 1) % displayCount;

    // list should not move if cursor isn't passed this part of the range
    var start = page - idx;

    let numPages = Math.floor(parseInt(this.props.totalRecords)/parseInt(this.props.perPage));
    // remaining pages
    var remaining = numPages - page;

    // Don't move cursor right if the range will exceed the number of pages
    // in other words, we've reached the home stretch
    if (page > displayCount && remaining < displayCount) {
      // add 1 due to the implementation of `range`
      start = numPages - displayCount + 1;
    }

    return this.range(start, start + displayCount);
  },
  renderPage: function(n, i) {
    var cls = this.props.currentPage == n ? 'active' : '';
    return (
      <li key={i} className={cls}>
        <Link to={`${this.props.baseUrl}/${n}`}>
          {n}
        </Link>
      </li>
    );
  },
  render: function() {
    if(this.props.currentPage == 1) {
      var firstPageLink = (
        <li className="disabled">
          <span>
            <span aria-hidden="true">&laquo;</span>
          </span>
        </li>
      );
    } else {
      var firstPageLink = (
        <li>
          <Link to={`${this.props.baseUrl}/${parseInt(this.props.currentPage)-1}`}>
            <span aria-hidden="true">&laquo;</span>
          </Link>
        </li>
      );
    }
    let lastPage = Math.floor(parseInt(this.props.totalRecords)/parseInt(this.props.perPage));
    if(this.props.currentPage >= lastPage) {
      var lastPageLink = (
        <li className="disabled">
          <span>
            <span aria-hidden="true">&raquo;</span>
          </span>
        </li>
      );
    } else {
      var lastPageLink = (
        <li>
          <Link to={`${this.props.baseUrl}/${parseInt(this.props.currentPage)+1}`}>
            <span aria-hidden="true">&raquo;</span>
          </Link>
        </li>
      );
    }
    return (
<nav>
  <ul className="pagination">
    {firstPageLink}
    {this.getPageRange().map(this.renderPage, this)}
    {lastPageLink}
  </ul>
</nav>
    );
  }
});

var ArtistView = React.createClass({
  render: function() {
    return(
      <div className="row artist-row">
        <div className="col-md-3 artist-image col-xs-3">
          <Link to={`/tracks/${this.props.mbid}`}>
            <img src={this.props.thumbnail} alt={this.props.name} className="img-responsive" />
          </Link>
        </div>
        <div className="col-md-9 artist-name col-xs-9">
          <h2>
            <Link to={`/tracks/${this.props.mbid}`}>
              {this.props.name}
            </Link>
          </h2>
        </div>
      </div>
    );
  }
});

var TrackView = React.createClass({
  render: function() {
    return(
      <tr>
        <td>
          {this.props.name}
        </td>
      </tr>
    );
  }
});

var TracksList = React.createClass({
  getInitialState: function() {
    return {
      data: []
    }
  },
  componentDidMount: function() {
    this.loadTracks();
  },
  loadTracks: function() {
    let endpointUrl = '/api/v1/tracks/top/'+this.props.params.artist;
    if(this.props.params.page) {
      endpointUrl += '/?page='+this.props.params.page;
    }
    $.ajax({
      url: endpointUrl,
      dataType: 'json',
      cache: true,
      success: function(data) {
        this.setState({
          data: data.toptracks.track,
          total: data.toptracks['@attributes'].total,
        });
      }.bind(this),
      error: function(xhr, status, err) {
        let errorText = err.toString();
        if(xhr.responseJSON && xhr.responseJSON.error) {
          errorText = xhr.responseJSON.error;
        }
        alert('Error loading tracks: '+errorText);
      }.bind(this)
    });
  },
  getArtistName: function() {
    if(this.state.data.length > 0 && this.state.data[0].artist) {
      return this.state.data[0].artist.name;
    }
    return '';
  },
  previousPage: function() {
    browserHistory.goBack();
  },
  render: function() {
    var tracks = this.state.data.map(function(track) {
      return(
        <TrackView key={track.mbid} name={track.name} />
      );
    });
    var boundClick = this.previousPage;
    return (
      <div className="tracksList">
        <div className="row">
          <a onClick={boundClick} href="#">
            Back
          </a>
        </div>
        <div className="row">
          <table className="table">
            <thead>
              <tr>
                <th>Top Songs by {this.getArtistName()}</th>
              </tr>
            </thead>
            <tbody>
              {tracks}
            </tbody>
          </table>
        </div>
      </div>
    )
  }
});

ReactDOM.render((
  <Router history={browserHistory}>
    <Route path="/" component={CountryFilter}>
      <Route path="/artists/:country(/:page)" component={ArtistsList}/>
    </Route>
    <Route path="/tracks/:artist(/:page)" component={TracksList} />
  </Router>
), document.getElementById('app'));
