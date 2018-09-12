import { eventsActions } from "../actions";

const initialState = {
  pending: false,
  event: null
};

const currentEvent = (state = initialState, action) => {

  switch (action.type) {

    case eventsActions.read.REQUEST:
      return {
        event: null,
        pending: true
      };

    case eventsActions.read.SUCCESS:
      return {
        ...state,
        event: action.payload
      };

    case eventsActions.read.FULFILL:
      return {
        ...state,
        pending: false
      };

    default:
      return state;
  }
};

export default currentEvent;
