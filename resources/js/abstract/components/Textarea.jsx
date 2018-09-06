import React from 'react';
import classNames from 'classnames';

const Textarea = ({
  input,
  label,
  type,
  value,
  meta: { touched, error, warning }
}) => (
  <div>
    <label>{label}</label>
    <div>
      <textarea
        {...input}
        className={classNames({
          'form-control': true,
          'mb-2': true,
          'is-invalid': touched && error
        })}
        placeholder={label}
      >{value}</textarea>
      {touched &&
      ( ( error && <span className="invalid-feedback">{error}</span> ) || ( warning && <span>{warning}</span> ) )
      }
    </div>
  </div>
);

export { Textarea };
