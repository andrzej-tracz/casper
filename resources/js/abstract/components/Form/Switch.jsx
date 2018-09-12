import React from "react";
import { default as ReactSwitch }  from "react-switch";

const Switch = ({
  id,
  input,
  label,
  type,
  value,
  meta: { touched, error, warning }
}) => (
  <div>
    <label>{label}</label>
    <div>
      <ReactSwitch
        id={id}
        checked={Boolean(input.value)}
        onChange={input.onChange}
        icons={false}
      />
      {touched && (
        (error && <span className="invalid-feedback">{error}</span> ) || ( warning && <span>{warning}</span>)
      )}
    </div>
  </div>
);

export { Switch };
