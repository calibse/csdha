/* app-legacy.js */

import * as timezone from "./modules/timezone";
import * as events from "./modules/events";
import * as home from "./modules/home";
import * as window from "./modules/window";

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

function addEventToMatchingIds(element) {
	var elements, currentEl, idParts, id, pattern, containerEl, containerId;

	pattern = element.element;
	containerId = pattern.substring(0, pattern.indexOf("-*")) + "-items";
	containerEl = document.getElementById(containerId);
	if (!containerEl) return;
	elements = containerEl.getElementsByTagName("*"); 
	idParts = pattern.split("*"); 
	for (var i = 0; i < elements.length; i++) {
		currentEl = elements[i];
		id = currentEl.id;
		if (!(id && id.indexOf(idParts[0]) === 0 && 
			id.lastIndexOf(idParts[1]) === 
			(id.length - idParts[1].length))) continue;
		currentEl.addEventListener(element.event, element.action);
	}
}

function addEvents(elementActions) {
	var element, currentEl;

	for (var i = 0; i < elementActions.length; i++) {
		element = elementActions[i];
		if (element.element.indexOf("*") !== -1) {
			addEventToMatchingIds(element);
			continue;
		}
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
		{
			element: "event-date-*_delete-button",
			event: "click",
			action: window.openDeleteItemWindow 
		},
		{
			element: "event-date-watten-*_delete-button",
			event: "click",
			action: window.openDeleteItemWindow 
		},
		{
			element: "event-attachment-set_create-button",
			event: "click",
			action: events.createEventAttachmentSet
		},
		{
			element: "event-attachment-set-*_edit-button",
			event: "click",
			action: events.editEventAttachmentSet
		},
		{
			element: "main-back-link",
			event: "click",
			action: setBackLink
		},
		{
			element: "event-attachment_delete-button",
			event: "click",
			action: events.deleteEventAttachment
		},
	];
	addEvents(elementActions);
}

function setBackLink(e) {
	var href, ref;

	href = e.currentTarget.href;
	ref = document.referrer;
	if (ref && ref.indexOf(href) === 0 && history.length > 1) {
		e.preventDefault();
		history.back();
	}
}

setTimezoneActions();
setActions();
setEvents();
window.openWindow();

