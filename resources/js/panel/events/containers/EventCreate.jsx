import React from 'react';
import { connect } from 'react-redux';
import EventForm from "../components/EventForm";
import { eventsActions } from "../actions";

class EventCreate extends React.Component {

  handleCreateEvent = values => {
    this.props.eventCreate({
      ...values,
      event_type: Boolean(values.is_private) ? 'private' : 'public'
    });
  };

  render() {
    return (
      <div>
        <h3>Create new Event</h3>
        <EventForm onSubmit={this.handleCreateEvent} />
      </div>
    );
  }
}

const mapDispatchToProps = {
  eventCreate: eventsActions.create
};

export { EventCreate }

export default connect(null, mapDispatchToProps)(EventCreate);
