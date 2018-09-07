import { fork, put, takeLatest } from 'redux-saga/effects';
import { CrudSagas } from '../../../abstract/sagas/crud-saga';
import { EVENTS_PREFIX, EVENTS_API_ENDPOINT, EVENTS_FORM_ID } from '../constants';
import { eventsActions } from "../actions";
import { reset, stopSubmit } from 'redux-form';
import { extractErrors } from "../../../abstract/api";

const sagas = new CrudSagas(EVENTS_PREFIX, EVENTS_API_ENDPOINT);

function * handleSuccessEventCreate() {
  yield put(reset(EVENTS_FORM_ID));
}

function * handleFailedEventCreate(action) {
  const { errors } = action.payload.response.data;
  const messages = extractErrors(errors);

  if (messages && Object.keys(messages).length) {
    yield put(stopSubmit(EVENTS_FORM_ID, messages))
  }
}

function * customEventSagas() {
  yield takeLatest(eventsActions.create.SUCCESS, handleSuccessEventCreate);
  yield takeLatest(eventsActions.create.FAILURE, handleFailedEventCreate);
}

export default [
  fork(sagas.getSagas()),
  fork(customEventSagas)
];
