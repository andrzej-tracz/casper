import React from 'react';
import ReactDom from 'react-dom';
import { createStore, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import createSagaMiddleware from 'redux-saga';
import { composeWithDevTools } from 'redux-devtools-extension';
import guestReducers from './reducers';
import { sagas }  from './sagas';
import EventCreate from "./containers/EventCreate";

const sagaMiddleware = createSagaMiddleware();

const enhancer = composeWithDevTools(
  applyMiddleware(sagaMiddleware)
);

const store = createStore(guestReducers, enhancer);

sagaMiddleware.run(sagas);

const EventsComponent = () => (
  <Provider store={store}>
    <EventCreate />
  </Provider>
);

export default function (element) {
    return ReactDom.render(<EventsComponent />, element);
}
