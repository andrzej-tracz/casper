import { all } from 'redux-saga/effects';
import eventsSagas from './events';
import guestsSagas from './guests';
import invitationsSagas from './invitations';

export function * sagas() {
  yield all([
    eventsSagas,
    guestsSagas,
    invitationsSagas
  ]);
}
