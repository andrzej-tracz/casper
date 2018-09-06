import { fork, put, takeLatest, call } from 'redux-saga/effects';
import { CrudSagas } from '../../../abstract/sagas/crud-saga';
import { EVENTS_PREFIX, EVENTS_API_ENDPOINT, EVENTS_FORM_ID } from '../constants';
import { eventsActions } from "../actions";
import { reset, stopSubmit } from 'redux-form';

const sagas = new CrudSagas(EVENTS_PREFIX, EVENTS_API_ENDPOINT);

function * handleSuccessEventCreate() {
  yield put(reset(EVENTS_FORM_ID));
}

function * handleFailedEventCreate(action) {
  const validation = {};
  const { errors } = action.payload.response.data;

  if (errors && Object.keys(errors).length) {
    Object.keys(errors).forEach(key => {
      validation[key] = 'This field is not valid';
    });

    yield call(stopSubmit, EVENTS_FORM_ID, validation)
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
