import { combineReducers } from 'redux';
import events from './events';
import { reducer as formReducer } from 'redux-form'
import currentEvent from "./currentEvent";
import eventsMeta from "./eventsMeta";
import { reducer as toastr } from 'react-redux-toastr'

export default combineReducers({
  form: formReducer,
  events,
  currentEvent,
  eventsMeta,
  toastr
})
