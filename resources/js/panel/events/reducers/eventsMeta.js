import { eventsActions } from "../actions";

const initialState = {
  pending: false,
  pagination: null
};

const eventsMeta = (state = initialState, action) => {

  switch (action.type) {

    case eventsActions.fetch.REQUEST:
      return {
        pending: true
      };

    case eventsActions.fetch.FULFILL:
      return {
        ...state,
        pending: false
      };

    default:
      return state;
  }
};

export default eventsMeta;
