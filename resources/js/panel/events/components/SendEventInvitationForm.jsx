import React from 'react';
import { Field, reduxForm } from 'redux-form';
import { AsyncSelect } from '../../../abstract/components';
import { EVENTS_INVITATION_FORM_ID } from "../constants";
import PropTypes from 'prop-types';
import axios from 'axios';

const validate = values => {
  const errors = {};

  if (!values.user || !values.user.value) {
    errors.user = 'Please select a user to invite.'
  }

  return errors;
};

let SendEventInvitationForm = props => {

  const { handleSubmit, submitting, eventId } = props;

  const loadUsers = (value) => {
    return axios
      .get('/panel/ajax/users-search', {
        params: {
          search: value,
          event_id: eventId
        }
      })
      .then((xhr) => xhr.data)
      .then(response => response.data)
      .then(users => Array.from(users).map(user => {
        return {
          value: user.id,
          label: user.nickname,
        }
      }))
  };

  return (
    <form onSubmit={handleSubmit}>
      <Field
        name="user"
        label="Invitation will be send to"
        component={(props) => (
          <AsyncSelect
            {...props}
            loadOptions={loadUsers}
          />
        )}
      />
      <div className="text-right">
        <button
          className="btn btn-primary mb-1 mt-2"
          type="submit"
          disabled={submitting}
        >
          Send Invitation
        </button>
      </div>
    </form>
  );
};

SendEventInvitationForm.propTypes = {
  onSubmit: PropTypes.func.isRequired,
  eventId: PropTypes.number.isRequired,
};

SendEventInvitationForm = reduxForm({ form: EVENTS_INVITATION_FORM_ID, validate })(SendEventInvitationForm);

export default SendEventInvitationForm;
