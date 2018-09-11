import ReactDom from "react-dom";
import React from "react";
import { ReadonlyGoogleMap } from "../../abstract/components/Map/ReadonlyGoogleMap";

const EventDetails = ({ eventId, eventLat, eventLng }) => {
  const markers = [
    {
      lat: +eventLat,
      lng: +eventLng,
    }
  ];

  return (
    <div>
      <ReadonlyGoogleMap
        markers={markers}
        defaultCenter={{ lat: +eventLat, lng: +eventLng }}
      />
    </div>
  );
};

EventDetails.defaultProps = {
  eventId: null,
  eventLat: null,
  eventLng: null,
};

export default function (element) {
  return ReactDom.render(<EventDetails {...element.dataset} />, element);
}
