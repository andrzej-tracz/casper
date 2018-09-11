import React from "react";
import { formatDate } from "../../../abstract/utils/date";

const EventInfo = ({ event }) => (
  <div>
    <h5>{event.name} - {formatDate(event.date)} - {event.time}</h5>
    <p>{event.description}</p>
    <div>
      <a
        href={`/event/${event.id}`}
       target="_blank"
      >
        Learn More
      </a>
    </div>
  </div>
);

EventInfo.defaulProps = {
  event: {}
};

export { EventInfo }
