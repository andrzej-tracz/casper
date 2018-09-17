import React from 'react';
import classNames from 'classnames';

const Alert = (props) => (
  <div className={classNames(['alert', `alert-${props.variant}`, 'mb-2', 'mt-2'])}>
    {props.children}
  </div>
);

Alert.defaultProps = {
  variant: 'info'
};

export { Alert }
