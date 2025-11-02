/* app-legacy.js */

import * as timezone from "./modules/timezone";
import * as events from "./modules/events";
import * as home from "./modules/home";
import * as dialog from "./modules/window";

function runTimezoneAction(actionDeps) {
	var date, intl, satisfied;
	
	satisfied = true;
	intl = actionDeps[0];
	date = actionDeps[1];
	for (var i = 0; i < intl.depends.length; i++) {
		if (window[intl.depends[i]] === "undefined") {
			satisfied = false;
			break;
		}
	}
	if (satisfied) {
		intl.action();
		return;
	}
	for (var i = 0; i < date.depends.length; i++) {
		if (window[date.depends[i]] === "undefined") {
			satisfied = false;
			break;
		}
	}
	if (satisfied) {
		date.action();
	}
}

function runActions(actionDeps) {
	var actions, depends, action, depend, satisfied;

	for (var i = 0; i < actionDeps.length; i++) {
		satisfied = true;
		action = actionDeps[i];
		for (var j = 0; j < action.depends.length; j++) {
			if (window[action.depends[j]] === "undefined") {
				satisfied = false;
				break;
			}
		}
		if (!satisfied) continue;
		for (var k = 0; k < action.actions.length; k++) {
			action.actions[k]();
		}
	} 
}

function addEvents(elementActions) {
	for (var i = 0; i < elementActions.length; i++) {
		element = elementActions[i];
		currentEl = document.getElementById(element.element);
		if (!currentEl) continue;
		currentEl.addEventListener(element.event, element.action);
	}
}

function setActions() {
	var actionDependencies;

	actionDependencies = [
		{
			actions: [ home.streamHome, home.streamHomeInfos ],
			depends: [ "EventSource", "JSON" ]
		}
	];
	runActions(actionDependencies);
}

function setTimezoneActions() {
	var timezoneActions;

	timezoneActions = [
		{
			action: timezone.setTimezoneFromIntl,
			depends: [ "Intl" ]
		},
		{
			action: timezone.setTimezoneFromDate,
			depends: [ "Date" ]
		}
	];
	runTimezoneAction(timezoneActions);
}

function setEvents() {
	var elementActions, element, currentEl;

	elementActions = [
		{
			element: "event-description_edit-button",
			event: "click",
			action: events.editEventDescription
		},
		{
			element: "event-narrative_edit-button",
			event: "click",
			action: events.editEventNarrative
		},
		{
			element: "event-venue_edit-button",
			event: "click",
			action: events.editEventVenue
		},
		{
			element: "event-date_create-button",
			event: "click",
			action: events.createEventDate
		},
	];
	addEvents(elementActions);
}

setTimezoneActions();
setActions();
setEvents();
dialog.openWindow();

