import { createCrudRoutines } from "../../abstract/actions/crud-actions";
import { EVENTS_PREFIX, GUESTS_PREFIX, INVITATIONS_PREFIX } from "./constants";

const eventsActions = createCrudRoutines(EVENTS_PREFIX);
const guestsActions = createCrudRoutines(GUESTS_PREFIX);
const invitationsActions = createCrudRoutines(INVITATIONS_PREFIX);

export {
  eventsActions,
  guestsActions,
  invitationsActions
}
