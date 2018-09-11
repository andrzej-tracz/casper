import React from "react";
import config from 'config';
import { GoogleMap, Marker, withGoogleMap, withScriptjs } from "react-google-maps";
import { compose, withProps } from "recompose";

const SimpleGoogleMap = (props) => {

  const renderMarkers = () => {
    if (!props.markers) {
      return;
    }

    return props.markers.map((marker, index) => (
      <Marker
        key={index}
        position={{ lat: marker.lat, lng: marker.lng }}
      />
    ))
  };

  return (
    <div>
      <GoogleMap
        defaultZoom={12}
        defaultCenter={props.defaultCenter}
      >
        {renderMarkers()}
      </GoogleMap>
    </div>
  );
};

SimpleGoogleMap.defaultProps = {
  markers: []
};

const ReadonlyGoogleMap = compose(
  withProps({
    googleMapURL: `https://maps.googleapis.com/maps/api/js?key=${config.googleMapKey}&libraries=geometry,drawing,places`,
    loadingElement: <div style={{ height: `100%` }}/>,
    containerElement: <div className="mb-3" style={{ height: `500px` }}/>,
    mapElement: <div style={{ height: `100%` }}/>,
  }),
  withScriptjs,
  withGoogleMap
)(SimpleGoogleMap);

export { ReadonlyGoogleMap };
