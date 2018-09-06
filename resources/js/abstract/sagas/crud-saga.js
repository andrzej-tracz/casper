import { takeLatest, call, put } from 'redux-saga/effects';
import api from '../api/rest-service';

class CrudSagas {

  constructor(prefix = null, url) {
    this.prefix = prefix;
    this.url = url;
    this.api = api;
    this.precessResponse = (response) => response.data;
    this.processRequest = (data) => data;
  }

  setRequestFilter(callback) {
    this.processRequest = callback;
  }

  setResponseFilter(callback) {
    this.precessResponse = callback;
  }

  * fetchData(self, type, action, options) {
    try {
      yield put({ type: `${self.prefix}_${type}/REQUEST`, payload: action.payload });

      const response = yield call(self.api.fetch, options);
      let payload = yield call(self.precessResponse, response, type);

      if (type === 'DELETE' && !payload) {
        payload = action.payload;
      }

      yield put({ type: `${self.prefix}_${type}/SUCCESS`, payload });
    } catch (error) {
      yield put({ type: `${self.prefix}_${type}/FAILURE`, payload: error });
    } finally {
      yield put({ type: `${self.prefix}_${type}/FULFILL`, payload: action.payload });
    }
  }

  * handleFetch(self, action) {
    yield self.fetchData(self, 'FETCH', action, { method: 'get', url: self.url });
  }

  * handleCreate(self, action) {
    const data = yield call(self.processRequest, action.payload);
    yield self.fetchData(self, 'CREATE', action, { method: 'post', url: self.url, data: data });
  }

  * handleRead(self, action) {
    const id = self.getResourceIdentifier(action);
    const { params } = action.request || {};
    yield self.fetchData(self, 'READ', action, { method: 'get', url: `${self.url}/${id}`, params: params || {} });
  }

  * handleUpdate(self, action) {
    const id = self.getResourceIdentifier(action);
    yield self.fetchData(self, 'UPDATE', action, { method: 'put', url: `${self.url}/${id}`, data: action.payload });
  }

  * handleDelete(self, action) {
    const id = self.getResourceIdentifier(action);

    if (!confirm('Are you sure?')) {
      return;
    }

    yield self.fetchData(self, 'DELETE', action, { method: 'delete', url: `${self.url}/${id}` });
  }

  * handleRestore(self, action) {
    const id = self.getResourceIdentifier(action);
    yield self.fetchData(self, 'RESTORE', action, { method: 'put', url: `${self.url}/${id}/restore` });
  }

  getResourceIdentifier(action) {
    const { id } = action.payload;
    if (!id) {
      throw new Error('ID not provided in action payload.');
    }
    return id;
  }

  getSagas() {
    const prefix = this.prefix;
    const self = this;
    if (!prefix) {
      throw new Error('Crud Saga prefix is not set.');
    }
    return function* () {
      yield takeLatest(`${prefix}_FETCH/TRIGGER`, self.handleFetch, self);
      yield takeLatest(`${prefix}_CREATE/TRIGGER`, self.handleCreate, self);
      yield takeLatest(`${prefix}_READ/TRIGGER`, self.handleRead, self);
      yield takeLatest(`${prefix}_UPDATE/TRIGGER`, self.handleUpdate, self);
      yield takeLatest(`${prefix}_DELETE/TRIGGER`, self.handleDelete, self);
      yield takeLatest(`${prefix}_RESTORE/TRIGGER`, self.handleRestore, self);
    };
  }
}

export { CrudSagas };

