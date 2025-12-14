/* events.js */

import * as dialog from "./window.js";

export function createEventDate(e) {
	var el, id;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
		return;
	}
	id = "event-date_create";
	el = document.getElementById(id);
	dialog.setOpenedWindowId(id);
	dialog.openWindow();
}

export function editEventVenue(e) {
	var windowEl;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
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
	dialog.openEditWindow(windowEl)
}

export function editEventNarrative(e) {
	var windowEl;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
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
	dialog.openEditWindow(windowEl)
}

export function editEventDescription(e) {
	var windowEl;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
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
	dialog.openEditWindow(windowEl)
}

export function createEventAttachmentSet(e) {
	var el, id;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
		return;
	}
	id = "event-attachment-set_create";
	el = document.getElementById(id);
	dialog.setOpenedWindowId(id);
	dialog.openWindow();
}

export function createEventLink(e) {
	var el, id;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
		return;
	}
	id = "event-link_create";
	el = document.getElementById(id);
	dialog.setOpenedWindowId(id);
	dialog.openWindow();
}

export function editEventBanner(e) {
	var el, id;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
		return;
	}
	id = "event-banner_edit";
	el = document.getElementById(id);
	dialog.setOpenedWindowId(id);
	dialog.openWindow();
}

export function editEventAttachmentSet(e) {
	var windowEl, contentId, deleteEl;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
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
	dialog.openEditItemWindow(windowEl)
}

export function deleteEventAttachment(e) {
	var el, id;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
		return;
	}
	id = "event-attachment_delete";
	el = document.getElementById(id);
	dialog.setOpenedWindowId(id);
	dialog.openWindow();
}

