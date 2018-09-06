import { createRoutine } from 'redux-saga-routines';

export function createCrudRoutines(prefix) {

  if (!prefix) {
    throw new Error('Prefix is not provided.');
  }

  return {
    fetch: createRoutine(`${prefix}_FETCH`),
    create: createRoutine(`${prefix}_CREATE`),
    read: createRoutine(`${prefix}_READ`),
    update: createRoutine(`${prefix}_UPDATE`),
    delete: createRoutine(`${prefix}_DELETE`),
    restore: createRoutine(`${prefix}_RESTORE`),
  };
}
