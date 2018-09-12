import React from 'react';
import ReactDom from 'react-dom';
import { createStore, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import createSagaMiddleware from 'redux-saga';
import { composeWithDevTools } from 'redux-devtools-extension';
import eventsReducer from './reducers';
import { sagas }  from './sagas';
import { HashRouter, Switch, Route, Redirect } from 'react-router-dom';
import EventCreate from "./containers/EventCreate";
import EventsList from "./containers/EventsList";
import EventEdit from "./containers/EventEdit";
import ReduxToastr from 'react-redux-toastr'

const sagaMiddleware = createSagaMiddleware();

const enhancer = composeWithDevTools(
  applyMiddleware(sagaMiddleware)
);

const store = createStore(eventsReducer, enhancer);

sagaMiddleware.run(sagas);

const EventsComponent = () => (
  <Provider store={store}>
    <React.Fragment>
      <ReduxToastr
        timeOut={4000}
        newestOnTop={false}
        preventDuplicates
        position="top-right"
        transitionIn="fadeIn"
        transitionOut="fadeOut"
        closeOnToastrClick
      />
      <HashRouter
        hashType="noslash"
      >
        <Switch>
          <Route path="/create" component={EventCreate} />
          <Route path="/edit/:id" component={EventEdit} />
          <Route path="/" exaclty={true} component={EventsList} />
          <Redirect to="/" />
        </Switch>
      </HashRouter>
    </React.Fragment>
  </Provider>
);

export default function (element) {
    return ReactDom.render(<EventsComponent />, element);
}
