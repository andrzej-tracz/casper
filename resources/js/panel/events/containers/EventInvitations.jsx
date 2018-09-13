import React from 'react';
import { connect } from 'react-redux';
import { Loader, Table } from "../../../abstract/components";
import EventInvitationForm from '../components/SendEventInvitationForm';
import { invitationsActions } from "../actions";

class EventInvitations extends React.Component {

  handleInvitationSubmit = (values) => {
    const { value } = values.user;

    this.props.createInvitation({
      event_id: this.props.event.id,
      user_id: value,
    })
  };

  renderInvitationsList = () => {

    const columns = [{
      label: "Username",
      value: invitation => {
        return invitation.invited.nickname;
      }
    }, {
      label: "Status",
      value: invitation => {
        const labels = {
          new: <span>Waiting for response</span>,
          accepted: <span className="text-success">Accepted</span>,
          rejected: <span className="text-danger">Rejected</span>,
        };

        return labels.hasOwnProperty(invitation.status) && labels[invitation.status] || null;
      }
    }];

    if (0 === this.props.invitations.length) {
      return (
        <div className="alert alert-info">
          There are no guests attached to this event yet
        </div>
      );
    }

    return (
      <Table
        columns={columns}
        items={this.props.invitations}
      />
    );
  };

  render() {

    return (
      <div>
        {this.props.isPending && (
          <Loader />
        ) || (
          <div>
            <EventInvitationForm
              onSubmit={this.handleInvitationSubmit}
            />
            {this.renderInvitationsList()}
          </div>
        )}
      </div>
    );
  }
}

const mapStateToProps = state => {
  return {
    event: state.currentEvent.event,
    invitations: state.currentEvent.event && state.currentEvent.event.invitations || [],
    isPending: state.currentEvent.pending
  }
};

const mapDispatchToProps = {
  createInvitation: invitationsActions.create
};

export { EventInvitations }

export default connect(mapStateToProps, mapDispatchToProps)(EventInvitations);
