import React from 'react';
import BaseAsyncSelect from 'react-select/lib/Async';

const AsyncSelect = ({
  id,
  input,
  label,
  type,
  value,
  loadOptions,
  onInputChange,
  dirty,
  meta: { touched, error, warning }
}) => {
  return (
    <div>
      <label>
        {error && (
          (error && <span className="text-danger">{error}</span> ) || ( warning && <span>{warning}</span>)
        ) || label}
      </label>
      <div>
        <BaseAsyncSelect
          cacheOptions
          defaultOptions
          loadOptions={loadOptions}
          onInputChange={onInputChange}
          onChange={input.onChange}
          value={input.value}
        />
      </div>

    </div>
  );
};

export { AsyncSelect };
