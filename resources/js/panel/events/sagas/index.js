import { all } from 'redux-saga/effects';
import eventsSagas from './events';

export function * sagas() {
  yield all([
    eventsSagas
  ]);
}
