/* accom_reports.js */

import * as window from "./window.js";

function openActionWindow(e, $action) {
	var el, id, actionUrl, formEl, titleEl, buttonEl;

	e.preventDefault();
	if (window.isThereOpenWindow()) {
		return;
	}
	id = "accom-report_prepare";
	titleEl = document.getElementById(id + "_title-text");
	buttonEl = document.getElementById(id + "-button");
	titleEl.textContent = $action + " accom. report";
	buttonEl.textContent = $action;
	el = document.getElementById(id);
	actionUrl = e.currentTarget.dataset.action;
	formEl = el.getElementsByTagName("form")[0];
	formEl.action = actionUrl;
	window.setOpenedWindowId(id);
	window.openWindow(true);
}

export function returnAccomReport(e) {
	openActionWindow(e, "Return");
}

export function submitAccomReport(e) {
	openActionWindow(e, "Submit");
}

export function approveAccomReport(e) {
	openActionWindow(e, "Approve");
}

