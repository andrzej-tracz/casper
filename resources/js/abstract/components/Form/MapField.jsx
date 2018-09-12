import React from "react";
import { EnhancedGoogleMap } from '../Map/GoogleMap';

const MapField = ({
  input,
  label,
  type,
  address,
  requestBrowserLocation,
  meta: { touched, error, warning }
 }) => {
  return (
    <div>
      <label>{label}</label>
      <div>
        {touched &&
        ((error && <span className="invalid-feedback">{error}</span>) || (warning && <span>{warning}</span>))
        }
        <EnhancedGoogleMap
          isMarkerShown={true}
          requestBrowserLocation={requestBrowserLocation}
          onMakerPositionChanged={input.onChange}
          address={address}
          position={input.value}
        />
      </div>
    </div>
  );
};

MapField.defaultProps = {
  address: null,
  requestBrowserLocation: true,
};

export { MapField };
