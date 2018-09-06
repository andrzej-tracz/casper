export function createCrudReducer(prefix) {

  if (!prefix) {
    throw new Error('Can not create reducer. Prefix not provided.');
  }

  let nextState = [];

  return function (state = [], action) {
    switch (action.type) {
      case `${prefix}_FETCH/SUCCESS`:

        return action.payload;

      case `${prefix}_CREATE/SUCCESS`:
        nextState = [ ...state ];
        nextState.push(action.payload);

        return nextState;

      case `${prefix}_UPDATE/SUCCESS`:
      case `${prefix}_READ/SUCCESS`:
        nextState = state.map((item) => {
          if (item.id === action.payload.id) {
            return action.payload;
          }

          return item;
        });

        return nextState;

      case `${prefix}_DELETE/SUCCESS`:
        nextState = state.filter((item) => item.id !== action.payload.id);

        return nextState;

      default:
        return state;
    }
  };
}

