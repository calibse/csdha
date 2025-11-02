/* events.js */

import * as window from "./window.js";

export function createEventDate(e) {
	var el, id;

	e.preventDefault();
	if (window.isThereOpenWindow()) {
		return;
	}
	id = "event-date_create";
	el = document.getElementById(id);
	if (!el) return;
	window.setOpenedWindowId(id);
	window.openWindow(true);
}

export function editEventVenue(e) {
	var windowEl;

	e.preventDefault();
	if (window.isThereOpenWindow()) {
		return;
	}
	windowEl = {
		element: "event-venue_edit",
		fields: [
			{
				field: "event-venue_field",
				value: "event-venue"
			},
		]
	};
	window.openEditWindow(windowEl)
}

export function editEventNarrative(e) {
	var windowEl;

	e.preventDefault();
	if (window.isThereOpenWindow()) {
		return;
	}
	windowEl = {
		element: "event-narrative_edit",
		fields: [
			{
				field: "event-narrative_field",
				value: "event-narrative"
			},
		]
	};
	window.openEditWindow(windowEl)
}

export function editEventDescription(e) {
	var windowEl;

	e.preventDefault();
	if (window.isThereOpenWindow()) {
		return;
	}
	windowEl = {
		element: "event-description_edit",
		fields: [
			{
				field: "event-description_field",
				value: "event-description"
			},
		]
	};
	window.openEditWindow(windowEl)
}

