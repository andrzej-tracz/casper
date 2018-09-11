import React from 'react';
import ReactDom from 'react-dom';
import { createStore, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import createSagaMiddleware from 'redux-saga';
import { composeWithDevTools } from 'redux-devtools-extension';
import eventsReducer from './reducers';
import { sagas }  from './sagas';
import EventCreate from "./containers/EventCreate";

const sagaMiddleware = createSagaMiddleware();

const enhancer = composeWithDevTools(
  applyMiddleware(sagaMiddleware)
);

const store = createStore(eventsReducer, enhancer);

sagaMiddleware.run(sagas);

const EventsComponent = () => (
  <Provider store={store}>
    <div>
      <EventCreate />
    </div>
  </Provider>
);

export default function (element) {
    return ReactDom.render(<EventsComponent />, element);
}
