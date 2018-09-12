import React from "react";
import PropTypes from 'prop-types';

const AddressField = (props) => (
  <div className="row">
    <div className="col">
      <input
        type="text"
        name="address"
        className="form-control"
        placeholder="Enter address details"
        onChange={props.onChange}
      />
    </div>
  </div>
);

AddressField.defaultProps = {
  onChange: () => {}
};

AddressField.propTypes = {
  onChange: PropTypes.func
};

export { AddressField };
