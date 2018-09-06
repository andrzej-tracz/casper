import { createCrudReducer } from "../../../abstract/reducers/crud-reducer";
import { EVENTS_PREFIX } from "../constants";

const eventsReducer = createCrudReducer(EVENTS_PREFIX);

const events = (state = [], action) => {
  switch (action.type) {
    default:
      return eventsReducer(state, action);
  }
};

export default events;
