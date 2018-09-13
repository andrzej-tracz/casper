import { fork, takeLatest, put, call } from 'redux-saga/effects';
import { invitationsActions } from "../actions";
import { EVENTS_API_ENDPOINT } from "../constants";
import api from '../../../abstract/api/rest-service';
import { notify } from "../../../abstract/utils";

function * handleInvitationCreate(action) {
  try {
    yield put({ type: invitationsActions.create.REQUEST, payload: action.payload });

    const response = yield call(api.fetch, {
      url: `${EVENTS_API_ENDPOINT}/${action.payload.event_id}/invitations`,
      method: 'POST',
      data: action.payload
    });

    yield put({ type: invitationsActions.create.SUCCESS, payload: response });
    yield call(notify().success, 'Success', 'Invitation to event has been send');

  } catch (error) {
    yield put({ type: invitationsActions.create.FAILURE, payload: error });
  } finally {
    yield put({ type: invitationsActions.create.FULFILL, payload: action.payload });
  }
}

function * handleFailedInvitationCreate(action) {
  const { errors } = action.payload.response.data || [];

  if (errors && errors.length) {
    for (let error of errors[0]) {
      yield call(notify().error, 'Error', error);
    }
  }
}

function * invitationsSagas() {
  yield takeLatest(invitationsActions.create.TRIGGER, handleInvitationCreate);
  yield takeLatest(invitationsActions.create.FAILURE, handleFailedInvitationCreate);
}

export default [
  fork(invitationsSagas),
];
