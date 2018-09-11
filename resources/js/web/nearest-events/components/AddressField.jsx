import React from "react";

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

export { AddressField };
