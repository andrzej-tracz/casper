import React from 'react';
import { connect } from 'react-redux';
import { Loader, Table } from "../../../abstract/components";
import { guestsActions } from "../actions";

class EventGuestList extends React.Component {

  handleGuestRemove = (guest) => {
    this.props.removeGuest(guest);
  };

  renderRowOptions = (guest) => {
    return (
      <div>
        <button
          className="btn btn-danger"
          onClick={() => this.handleGuestRemove(guest)}
        >
          Delete
        </button>
      </div>
    );
  };

  renderGuestsList = () => {

    const columns = [{
      label: "Username",
      value: guest => {
        return guest.user.nickname;
      }
    }];

    if (0 === this.props.guests.length) {
      return (
        <div className="alert alert-info">
          There are no guests attached to this event yet
        </div>
      );
    }

    return (
      <Table
        options={this.renderRowOptions}
        columns={columns}
        items={this.props.guests}
      />
    );
  };

  render() {

    return (
      <div>
        {this.props.isPending && (
          <Loader />
        ) ||
          this.renderGuestsList()
        }
      </div>
    );
  }
}

const mapStateToProps = state => {
  return {
    guests: state.currentEvent.event && state.currentEvent.event.guests || [],
    isPending: state.currentEvent.pending
  }
};

const mapDispatchToProps = {
  removeGuest: guestsActions.delete
};

export { EventGuestList }

export default connect(mapStateToProps, mapDispatchToProps)(EventGuestList);
