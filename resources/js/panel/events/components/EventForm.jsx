import React from 'react';
import { Field, reduxForm } from 'redux-form';
import { FormField, Textarea, Switch, MapField } from '../../../abstract/components';
import { EVENTS_FORM_ID } from "../constants";
import Validator from 'validatorjs';
import PropTypes from 'prop-types';

const validate = values => {

  const rules = {
    name: 'required',
    description: 'required',
    place: 'required',
    date: 'required|date',
    time: 'required',
    duration_minutes: 'required|numeric|min:0',
    max_guests_number: 'required|numeric|min:0',
    applications_ends_at: 'required|date',
  };

  const validator = new Validator(values, rules, {
    'min.duration_minutes': 'Duration is invalid. ',
    'min.max_guests_number': 'Guest limit is not valid. ',
  });

  if (validator.fails()) {
    return validator.errors.all();
  }
};

const renderMapField = (props) => {
  const { isEdit } = props;
  const { lat, lng } = _.get(props, 'initialValues.position', {});

  if (isEdit && (!lat || !lng)) {
    return;
  }

  return (
    <Field
      name="position"
      lavel="Choose a place of event"
      component={MapField}
      requestBrowserLocation={!props.isEdit}
    />
  );
};

let EventForm = props => {

  const { handleSubmit, submitting } = props;

  return (
    <form onSubmit={handleSubmit}>
      <Field
        name="is_private"
        label="Is private event?"
        component={Switch}
      />
      <Field
        name="name"
        label="Name"
        component={FormField}
        type="text"
      />
      <Field
        name="place"
        label="Place"
        component={FormField}
        type="text"
      />
      <Field
        name="description"
        label="Description"
        component={Textarea}
      />
      <Field
        name="date"
        label="Start date"
        component={FormField}
        type="date"
      />
      <Field
        name="time"
        label="Start time"
        component={FormField}
        type="time"
      />
      <Field
        name="duration_minutes"
        label="Duration (minutes)"
        component={FormField}
        type="number"
      />
      <Field
        name="max_guests_number"
        label="Maximum number of guests"
        component={FormField}
        type="number"
      />
      <Field
        name="applications_ends_at"
        label="End date of applications"
        component={FormField}
        type="date"
      />
      {renderMapField(props)}
      <div
        className="text-right"
      >
        <button
          className="btn btn-primary mb-1 mt-1"
          type="submit"
          disabled={submitting}
        >
          Submit
        </button>
      </div>
    </form>
  );
};

EventForm.defaultProps = {
  isEdit: false
};

EventForm.propTypes = {
  isEdit: PropTypes.bool
};

EventForm = reduxForm({ form: EVENTS_FORM_ID, validate })(EventForm);

export default EventForm;
