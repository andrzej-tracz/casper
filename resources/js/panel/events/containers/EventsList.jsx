import React from 'react';
import { connect } from 'react-redux';
import { eventsActions } from "../actions";
import { Loader, Table } from '../../../abstract/components';
import { Link } from 'react-router-dom';
import { formatDate } from "../../../abstract/utils/date";

class EventsList extends React.Component {

  static defaultProps = {
    events: []
  };

  componentDidMount() {
    this.props.fetchEvents();
  }

  handleEventRemove = (event) => {
    this.props.destroyEvent(event);
  };

  renderRowOptions = (event) => {
    return (
      <div>
        <Link
          to={`/edit/${event.id}`}
          className="btn btn-primary"
        >
          Edit
        </Link>
        <button
          className="btn btn-danger"
          onClick={() => this.handleEventRemove(event)}
        >
          Delete
        </button>
      </div>
    );
  };

  renderEventAnchor = (event) => (
    <a href={`/event/${event.id}`} target="_blank">{event.name}</a>
  );

  renderEventType = (event) => {
    if (event.event_type === 'public') {
      return <span className="badge badge-primary">Public</span>;
    }

    if (event.event_type === 'private') {
      return <span className="badge badge-danger">Private</span>;
    }
  };

  renderEventDate = (event) => formatDate(event.date);

  render() {

    const columns = [
      {
        label: 'Name',
        value: this.renderEventAnchor
      },
      {
        label: 'Type',
        value: this.renderEventType
      },
      {
        key: 'place',
        label: 'Place'
      },
      {
        key: 'date',
        label: 'Date',
        value: this.renderEventDate
      },
    ];

    return (
      <div>
        <div className="text-right">
          <Link
            to="/create"
            className="btn btn-primary"
          >
            Create Event
          </Link>
        </div>
        {this.props.isPending && (
          <Loader />
        ) || (
          <Table
            options={this.renderRowOptions}
            columns={columns}
            items={this.props.events}
          />
        )}
      </div>
    );
  }
}

const mapStateToProps = state => {
  return {
    events: state.events,
    isPending: state.eventsMeta.pending
  }
};

const mapDispatchToProps = {
  fetchEvents: eventsActions.fetch,
  destroyEvent: eventsActions.delete
};

export default connect(mapStateToProps, mapDispatchToProps)(EventsList);
