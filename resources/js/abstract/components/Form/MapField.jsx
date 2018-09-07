import React from "react";
import { EnhancedGoogleMap } from '../Map/GoogleMap';

const MapField = ({
  input,
  label,
  type,
  address,
  meta: { touched, error, warning }
 }) => (
  <div>
    <label>{label}</label>
    <div>
      {touched &&
        ((error && <span className="invalid-feedback">{error}</span>) || (warning && <span>{warning}</span>))
      }
      <EnhancedGoogleMap
        isMarkerShown={true}
        onMakerPositionChanged={input.onChange}
        address={address}
      />
    </div>
  </div>
);

export { MapField };
