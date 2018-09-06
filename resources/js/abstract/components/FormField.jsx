import React from 'react';
import classNames from 'classnames';

const FormField = ({
   input,
   label,
   type,
   meta: { touched, error, warning }
 }) => (
  <div>
    <label>{label}</label>
    <div>
      <input
        {...input}
        className={classNames({
          'form-control': true,
          'mb-2': true,
          'is-invalid': touched && error
        })}
        placeholder={label}
        type={type}
      />
      {touched &&
      ((error && <span className="invalid-feedback">{error}</span>) || (warning && <span>{warning}</span>))
      }
    </div>
  </div>
);

export { FormField };
