import ReactDom from "react-dom";
import React from "react";
import { AddressField } from "./components/AddressField";
import { EnhancedEventsMap } from "./components/EventsMap";
import api from '../../abstract/api/rest-service';
import _  from 'lodash';

class NearestEvents extends React.Component {

  state = {
    position: null,
    events: []
  };

  constructor(props) {
    super(props);

    this.searchAddressForCords = _.debounce(this.geocodeAddress, 500);
  }

  componentDidMount() {
    this.requestBrowserGeoLocation();
  }

  handleAddressChange = (event) => {
    const value = event.currentTarget.value;
    event.persist();

    this.searchAddressForCords(value);
  };

  geocodeAddress = (value) => {
    const googleMaps = this.props.googleMaps || (window.google && window.google.maps) || this.googleMaps;
    this.geocoder = new googleMaps.Geocoder();

    this.geocoder.geocode({
      address: value
    }, (results, status) => {
      if (status === 'OK') {
        const location = results[0].geometry.location;
        this.setState({
          position: {
            lat: location.lat(),
            lng: location.lng()
          }
        });
        this.searchForNearestEvents();
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
          lng: coords.longitude
        };

        this.setState({ position });
        this.searchForNearestEvents();
      }
    );
  };

  searchForNearestEvents = () => {
    this.fetchNearestEvents()
      .then((events) => {
        this.setState({
          events
        });
      });
  };

  fetchNearestEvents = () => {
    return api.fetch({
      url: '/event/ajax/nearest-events-search',
      params: {
        lat: this.state.position.lat,
        lng: this.state.position.lng
      }
    }).then(response => response.data);
  };

  render() {
    return (
      <div>
        <h1 className="mb-lg-4">Nearest Events</h1>
        <AddressField
          onChange={this.handleAddressChange}
        />
        <EnhancedEventsMap
          position={this.state.position}
          events={this.state.events}
        />
      </div>
    );
  }
}

export default function (element) {
  return ReactDom.render(<NearestEvents />, element);
}
