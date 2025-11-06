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

export function createEventAttachmentSet(e) {
	var el, id;

	e.preventDefault();
	if (window.isThereOpenWindow()) {
		return;
	}
	id = "event-attachment-set_create";
	el = document.getElementById(id);
	window.setOpenedWindowId(id);
	window.openWindow(true);
}

export function editEventAttachmentSet(e) {
	var windowEl, contentId, deleteEl;

	e.preventDefault();
	if (window.isThereOpenWindow()) {
		return;
	}
	contentId = e.currentTarget.id.replace("_edit-button", "");
	windowEl = {
		item: contentId,
		hasDelete: true,
		element: "event-attachment-set_edit",
		fields: [
			{
				field: "event-attachment-set-caption_field",
				value: contentId
			},
			{
				field: "event-attachment-set_id",
				value: contentId + "_id"
			},
		]
	};
	window.openEditItemWindow(windowEl)
}

export function deleteEventAttachment(e) {
	var el, id;

	e.preventDefault();
	if (window.isThereOpenWindow()) {
		return;
	}
	id = "event-attachment_delete";
	el = document.getElementById(id);
	window.setOpenedWindowId(id);
	window.openWindow(true);
}

