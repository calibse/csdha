/* gpoa_activities.js */

import * as dialog from "./window.js";

function openActionWindow(e, $action) {
	var el, id, actionUrl, formEl, titleEl, buttonEl;

	e.preventDefault();
	if (dialog.isThereOpenWindow()) {
		return;
	}
	id = "gpoa-activity_prepare";
	titleEl = document.getElementById(id + "_title-text");
	buttonEl = document.getElementById(id + "-button");
	titleEl.textContent = $action + " activity";
	buttonEl.textContent = $action;
	el = document.getElementById(id);
	actionUrl = e.currentTarget.dataset.action;
	formEl = el.getElementsByTagName("form")[0];
	formEl.action = actionUrl;
	dialog.setOpenedWindowId(id);
	dialog.openWindow(true);
}

export function submitActivity(e) {
	openActionWindow(e, "Submit");
}

export function returnActivity(e) {
	openActionWindow(e, "Return");
}

export function approveActivity(e) {
	openActionWindow(e, "Approve");
}

export function rejectActivity(e) {
	openActionWindow(e, "Reject");
}

export function deleteActivity(e) {
	dialog.prepareOpenWindow(e, "gpoa-activity_delete");
}

export function closeGpoa(e) {
	dialog.prepareOpenWindow(e, "gpoa_close");
}
