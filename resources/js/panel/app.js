import '../bootstrap';
import renderEvents from './events/Events';

const $eventsComponent = document.getElementById('panel-events-component');

if ($eventsComponent) {
  renderEvents($eventsComponent);
}
