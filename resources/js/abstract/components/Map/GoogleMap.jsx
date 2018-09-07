import React from 'react';
import config from 'config';
import { compose, withProps } from 'recompose';
import { withScriptjs, withGoogleMap, GoogleMap, Marker } from 'react-google-maps';

class GoogleMapComponent extends React.Component {

  marker = null;

  map = null;

  geocoder = null;

  state = {
    position: null
  };

  componentDidMount() {
    this.requestBrowserGeoLocation();
  }

  onPositionChanged = () => {
    const markerPosition = this.marker.getPosition();
    const position = {
      lat: markerPosition.lat(),
      lng: markerPosition.lng(),
    };

    this.props.onMakerPositionChanged(position);
  };

  onMarkerMounted = ref => {
    this.marker = ref;
  };

  onMapMounted = ref => {
    this.map = ref;

    this.createGeoCoder();
  };

  createGeoCoder = () => {
    if (!this.props.address) {
      return;
    }

    const googleMaps = this.props.googleMaps || ( window.google && window.google.maps ) || this.googleMaps;
    this.geocoder = new googleMaps.Geocoder();

    this.geocoder.geocode({
      address: this.props.address
    }, (results, status) => {
      if (status === 'OK') {
        const location = results[ 0 ].geometry.location;

        this.setState({
          position: {
            lat: location.lat(),
            lng: location.lng()
          }
        });
      }
    });
  };

  requestBrowserGeoLocation = () => {

    if (!navigator.geolocation) {
      console.warn('Browser geolocation not supported');

      return;
    }

    navigator.geolocation.getCurrentPosition(
      ({ coords }) => {
        const position = {
          lat: coords.latitude,
          lng: coords.longitude,
        };

        this.setState({ position });
      }
    );
  };

  getDefaultCenterPosition = () => {
    return { lat: 52.237049, lng: 21.017532 };
  };

  getCenterPosition = () => {
    if (this.state.position) {
      return this.state.position;
    }

    return this.getDefaultCenterPosition();
  };

  render() {
    return (
      <div>
        <GoogleMap
          ref={this.onMapMounted}
          defaultZoom={8}
          defaultCenter={this.getDefaultCenterPosition()}
          center={this.getCenterPosition()}
        >
          {this.props.isMarkerShown && this.state.position && (
            <Marker
              position={{ lat: this.state.position.lat, lng: this.state.position.lng }}
              draggable={true}
              ref={this.onMarkerMounted}
              onPositionChanged={this.onPositionChanged}
            />
          )}
        </GoogleMap>
      </div>
    );
  }
}

const EnhancedGoogleMap = compose(
  withProps({
    googleMapURL: `https://maps.googleapis.com/maps/api/js?key=${config.googleMapKey}&libraries=geometry,drawing,places`,
    loadingElement: <div style={{ height: `100%` }}/>,
    containerElement: <div style={{ height: `450px` }}/>,
    mapElement: <div style={{ height: `100%` }}/>,
  }),
  withScriptjs,
  withGoogleMap
)(GoogleMapComponent);

export { EnhancedGoogleMap };
