import { eventsActions, invitationsActions } from "../actions";

const initialState = {
  pending: false,
  invitationsPending: false,
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

    case invitationsActions.create.REQUEST:
      return {
        ...state,
        invitationsPending: true
      };

    case invitationsActions.create.FULFILL:
      return {
        ...state,
        invitationsPending: false
      };

    case invitationsActions.create.SUCCESS:
      return {
        ...state,
        event: {
          ...state.event,
          invitations: [
            ...state.event.invitations,
            action.payload
          ]
        }
      };

    default:
      return state;
  }
};

export default currentEvent;
