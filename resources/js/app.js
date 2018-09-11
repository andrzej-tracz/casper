import './bootstrap';
import renderNearestEvents from "./web/nearest-events/NearestEvents";

const $nearestEventsComponent = document.getElementById('web-nearest-events-component');

if ($nearestEventsComponent) {
  renderNearestEvents($nearestEventsComponent);
}
