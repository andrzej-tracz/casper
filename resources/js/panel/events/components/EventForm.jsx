import React from 'react';
import { connect } from 'react-redux';
import { Field, reduxForm, change } from 'redux-form';
import { FormField, Textarea, Switch, MapField } from '../../../abstract/components';
import { EVENTS_FORM_ID } from "../constants";
import Validator from 'validatorjs';
import PropTypes from 'prop-types';

class EventForm extends React.Component {

  static validate = values => {
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

  state = {
    position: null
  };

  constructor(props) {
    super(props);

    this.resolveCordsFromAddress = _.debounce(this.resolveCordsFromAddress, 500);
  }

  geocodeAddress = (value) => {
    const googleMaps = this.props.googleMaps || (window.google && window.google.maps);
    this.geocoder = new googleMaps.Geocoder();

    return new Promise((resolve, reject) => {
      this.geocoder.geocode({
        address: value
      }, (results, status) => {
        if (status === 'OK') {
          const location = results[0].geometry.location;
          return resolve({
            lat: location.lat(),
            lng: location.lng(),
          });
        }

        return reject({ results, status })
      });
    });
  };

  renderMapField = () => {
    const { isEdit } = this.props;
    const { lat, lng } = _.get(this.props, 'initialValues.position', {});

    if (isEdit && (!lat || !lng)) {
      return;
    }

    return (
      <Field
        name="position"
        lavel="Choose a place of event"
        component={MapField}
        requestBrowserLocation={false}
      />
    );
  };

  resolveCordsFromAddress = async (address) => {
    const cords = await this.geocodeAddress(address);

    this.props.changeField('position', cords);
  };

  handlePlaceChange = (event) => {
    const address = event.currentTarget.value;

    this.resolveCordsFromAddress(address);
  };

  render() {
    const { handleSubmit, submitting } = this.props;

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
          onChange={this.handlePlaceChange}
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
        {this.renderMapField()}
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
  }
}

EventForm.defaultProps = {
  isEdit: false
};

EventForm.propTypes = {
  isEdit: PropTypes.bool
};

const mapDispatchToProps = {
  changeField: (...args) => change(EVENTS_FORM_ID, ...args)
};

const ConnectedEventForm = connect(null, mapDispatchToProps)(EventForm);

const EnhancedEventForm = reduxForm({ form: EVENTS_FORM_ID, validate: EventForm.validate })(ConnectedEventForm);

export default EnhancedEventForm;
