import React from 'react';
import { connect } from 'react-redux';
import EventForm from "../components/EventForm";
import { eventsActions } from "../actions";
import { Link } from "react-router-dom";
import { Loader } from "../../../abstract/components";
import { formatToValue } from "../../../abstract/utils";

class EventEdit extends React.Component {

  componentDidMount() {
    const { id } = this.props.match.params;

    this.props.fetchEvent({ id });
  }

  handleEventSave = values => {
    const attributes = {
      ...values,
      event_type: Boolean(values.is_private) ? 'private' : 'public'
    };

    if (values.position) {
      attributes.geo_lat = values.position.lat;
      attributes.geo_lng = values.position.lng;
      delete attributes.position;
    } else {
      delete attributes.geo_lat;
      delete attributes.geo_lng;
    }

    this.props.eventUpdate(attributes);
  };

  getInitialFormValues = () => {
    const { event } = this.props;

    if (!event) {
      return;
    }

    const values = {
      ...event,
      is_private: event.event_type === 'private',
      date: formatToValue(event.date),
      time: event.time.substr(0, 5),
      applications_ends_at: formatToValue(event.applications_ends_at)
    };

    if (event.geo_lat &&  event.geo_lng) {
      values.position = {
        lat: event.geo_lat,
        lng: event.geo_lng,
      }
    }

    return values;
  };

  render() {
    return (
      <div>
        <div
          className="text-right"
        >
          <Link
            to="/"
            className="btn btn-warning"
          >
            Cancel
          </Link>
        </div>
        {this.props.isPending && (
          <Loader />
        ) || (
          <React.Fragment>
            <h3>Edit Event</h3>
            <EventForm
              onSubmit={this.handleEventSave}
              initialValues={this.getInitialFormValues()}
              isEdit={true}
            />
          </React.Fragment>
        )}
      </div>
    );
  }
}

const mapStateToProps = state => {
  return {
    event: state.currentEvent.event,
    isPending: state.currentEvent.pending
  }
};

const mapDispatchToProps = {
  fetchEvent: eventsActions.read,
  eventUpdate: eventsActions.update,
};

export { EventEdit }

export default connect(mapStateToProps, mapDispatchToProps)(EventEdit);
