import './bootstrap';
import renderNearestEvents from "./web/nearest-events/NearestEvents";
import renderEventDetails from "./web/event-details/EventDetails";

const $nearestEventsComponent = document.getElementById('web-nearest-events-component');

if ($nearestEventsComponent) {
  renderNearestEvents($nearestEventsComponent);
}

const $eventDetailsComponent = document.getElementById('web-event-details-component');

if ($eventDetailsComponent) {
  renderEventDetails($eventDetailsComponent);
}
