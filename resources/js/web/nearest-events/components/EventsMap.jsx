import React from 'react';
import PropTypes from 'prop-types';
import config from 'config';
import { withScriptjs, withGoogleMap, GoogleMap, Marker, InfoWindow, Circle } from 'react-google-maps';
import { compose, withProps } from "recompose";
import { EventInfo } from "./EventInfo";

class EventsMap extends React.Component {

  static propTypes = {
    position: PropTypes.objectOf(PropTypes.number),
    events: PropTypes.arrayOf(PropTypes.object)
  };

  static defaultProps = {
    position: null,
    events: []
  };

  state = {
    showingInfoWindow: false,
    activeMarker: {},
    selectedPlace: {},
    selectedEvent: null,
  };

  map = null;

  onMapMount = (map) => {
    this.map = map;
  };

  getDefaultCenterPosition = () => {
    return { lat: 52.237049, lng: 21.017532 };
  };

  getCenterPosition = () => {
    if (this.props.position) {
      return this.props.position;
    }

    return this.getDefaultCenterPosition();
  };

  handleInfoBoxClose = () => {
    this.closeInfoBox();
  };

  onMarkerClick = (place, marker, event) => {
    this.setState({
      selectedPlace: place,
      selectedEvent: event,
      activeMarker: marker,
      showingInfoWindow: true
    });
  };

  onMapClicked = () => {
    this.closeInfoBox();
  };

  closeInfoBox = () => {
    if (this.state.showingInfoWindow) {
      this.setState({
        showingInfoWindow: false,
        activeMarker: null,
        selectedEvent: null,
        selectedPlace: null,
      })
    }
  };

  renderEvents = () => {
    if (! this.props.events) {
      return;
    }

    return this.props.events.map(event => (
      <Marker
        key={event.id}
        onClick={(place, marker) => this.onMarkerClick(place, marker, event)}
        name={event.name}
        position={{ lat: event.geo_lat, lng: event.geo_lng }}
      />
    ));
  };

  renderCurrentEventInfo = () => {
    const { selectedEvent } = this.state;

    if (!this.state.showingInfoWindow) {
      return;
    }

    if (!selectedEvent) {
      return;
    }

    return (
      <InfoWindow
        onCloseClick={this.handleInfoBoxClose}
        position={{
          lat: selectedEvent.geo_lat,
          lng: selectedEvent.geo_lng,
        }}
      >
        <EventInfo event={selectedEvent} />
      </InfoWindow>
    );
  };

  render() {

    return (
      <div>
        <GoogleMap
          onClick={this.onMapClicked}
          ref={this.onMapMount}
          defaultZoom={11}
          defaultCenter={this.getDefaultCenterPosition()}
          center={this.getCenterPosition()}
        >
          <Circle
            center={this.getCenterPosition()}
            radius={5000}
          />
          {this.renderEvents()}
          {this.renderCurrentEventInfo()}
        </GoogleMap>
      </div>
    );
  }
}

const EnhancedEventsMap = compose(
  withProps({
    googleMapURL: `https://maps.googleapis.com/maps/api/js?key=${config.googleMapKey}&libraries=geometry,drawing,places`,
    loadingElement: <div style={{ height: `100%` }}/>,
    containerElement: <div className="mt-2 mb-2" style={{ height: `650px` }}/>,
    mapElement: <div style={{ height: `100%` }}/>,
  }),
  withScriptjs,
  withGoogleMap
)(EventsMap);

export { EnhancedEventsMap }
