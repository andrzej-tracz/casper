import { fork, takeLatest, put, select, call } from 'redux-saga/effects';
import { CrudSagas } from '../../../abstract/sagas/crud-saga';
import { GUESTS_PREFIX, GUESTS_API_ENDPOINT } from '../constants';
import { guestsActions, eventsActions } from "../actions";
import { notify } from "../../../abstract/utils";

const guestsSagas = new CrudSagas(GUESTS_PREFIX, GUESTS_API_ENDPOINT);

function * handleSuccessGuestRemove() {
  const currentEvent = yield select(state => state.currentEvent.event);

  yield put(eventsActions.read(currentEvent));
  yield call(notify().success, 'Removed', 'Guest has been removed');
}

function * customGuestSagas() {
  yield takeLatest(guestsActions.delete.SUCCESS, handleSuccessGuestRemove);
}

export default [
  fork(guestsSagas.getSagas()),
  fork(customGuestSagas),
];
