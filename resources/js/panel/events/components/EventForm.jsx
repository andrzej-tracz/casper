import React from 'react';
import { Field, reduxForm } from 'redux-form';
import { FormField, Textarea, Switch, MapField } from '../../../abstract/components';
import { EVENTS_FORM_ID } from "../constants";

const validate = values => {
  const errors = {};

  const required = {
    name: "Name is required",
    description: "Description is required",
    place: "Place is required",
    date: "Start date is required",
    time: "Start time is required",
    duration_minutes: "Duration is required",
    max_guests_number: "Maksimum number of guests is required",
    applications_ends_at: "End date of applications is required",
  };

  Object.keys(required).map((key) => {
    if (!values[key]) {
      errors[key] = required[key];
    }
  });

  return errors;
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
        label="Duration"
        component={FormField}
        type="number"
      />
      <Field
        name="max_guests_number"
        label="Maksimum number of guests"
        component={FormField}
        type="number"
      />
      <Field
        name="applications_ends_at"
        label="End date of applications"
        component={FormField}
        type="date"
      />
      <Field
        name="position"
        lavel="Choose a place of event"
        component={MapField}
      />
      <button
        className="btn btn-primary mb-1 mt-1"
        type="submit"
        disabled={submitting}
      >
        Submit
      </button>
    </form>
  );
};

EventForm = reduxForm({ form: EVENTS_FORM_ID, validate })(EventForm);

export default EventForm;

