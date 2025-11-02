// main.js

var WINDOW_DRAGGED, WINDOW_OFFSET_X, WINDOW_OFFSET_Y, 
	HOME_STREAM;
var HOME_STREAM_EVENTS = {
	pendingActivityCountChanged: updatePendingActivityCount,
	upcomingEventCountChanged: updateUpcomingEventCount,
	pendingAccomReportCountChanged: updatePendingAccomReportCount
};
var HOME_STREAM_ELEMENTS = [
	"home-content_pending-activity-count",
	"home-content_upcoming-event-count",
	"home-content_pending-accom-report-count"
];

function setCookie(name, value) {
	var now, expires;
	now = new Date();
	now.setFullYear(now.getFullYear() + 1);
	expires = now.toUTCString();
	document.cookie = name + "=" + value + "; expires=" + expires 
		+ "; samesite=lax; path=/";
}

function setTimezoneFromDate() {
	var timezone;
	timezone = new Date().getTimezoneOffset();
	setCookie("timezone", timezone);
}

function setTimezoneFromIntl() {
	var timezone;
	timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	setCookie("timezone", timezone);
}

function updateHomeInfosCount(id, data) {
	var el, count;
	el = document.getElementById(id);
	if (!el) return;
	data = JSON.parse(data);
	count = data.count;
	el.textContent = count;
}

function updatePendingActivityCount(e) {
	updateHomeInfosCount("home-content_pending-activity-count", e.data);
}

function updateUpcomingEventCount(e) {
	updateHomeInfosCount("home-content_upcoming-event-count", e.data);
}

function updatePendingAccomReportCount(e) {
	updateHomeInfosCount("home-content_pending-accom-report-count", 
		e.data);
}

function streamHomeInfos() {
	var event, el;
	for (var i = 0; i < HOME_STREAM_ELEMENTS.length; i++) {
		el = document.getElementById(HOME_STREAM_ELEMENTS[i]);
		if (!el) return;
	}
	for (event in HOME_STREAM_EVENTS) {
		HOME_STREAM.addEventListener(event, HOME_STREAM_EVENTS[event]);
	}
}

function closeStreamHomeInfos() {
	var events, i;
	events = [
		"pendingActivityCountChanged",
		"upcomingEventCountChanged",
		"pendingAccomReportCountChanged",
	];
	for (event in HOME_STREAM_EVENTS) {
		HOME_STREAM.removeEventListener(event, HOME_STREAM_EVENTS[i]);
	}
}

function updateHomeContent(e) {
	var data, el, gpoaActive;
	infosEl = document.getElementById("home-content_infos");
	noGpoaEl = document.getElementById("home-content_no-gpoa");
	if (!infosEl || !noGpoaEl) return;
	data = JSON.parse(e.data);
	gpoaActive = data.active;
	if (gpoaActive) {
		infosEl.style.display = "block";
		noGpoaEl.style.display = "none";
		streamHomeInfos();
		return
	}
	infosEl.style.display = "none";
	noGpoaEl.style.display = "block";
	for (var i = 0; i < HOME_STREAM_ELEMENTS.length; i++) {
		el = document.getElementById(HOME_STREAM_ELEMENTS[i]);
		if (!el) return;
		el.textContent = "0";
	}
	closeStreamHomeInfos();
}

function streamHome() {
	var el;
	el = document.getElementById("home-content");
	if (!el) return;
	HOME_STREAM = new EventSource("/api/stream/home");
	HOME_STREAM.addEventListener("gpoaStatusChanged", updateHomeContent);
}

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

function setOpenedWindowId(id) {
	var key, elementId;

	key = "opened_window";
	sessionStorage.setItem(key, id);
}

function getOpenedWindowId() {
	var key, elementId;

	key = "opened_window";
	elementId = sessionStorage.getItem(key);
	return elementId;
}

function forgetOpenedWindowId() {
	var key, elementId;

	key = "opened_window";
	sessionStorage.removeItem(key);
}

function openEditWindow(window) {
	var field, valueEl, fieldEl;

	for (var i = 0; i < window.fields.length; i++) {
		field = window.fields[i];
		fieldEl = document.getElementById(field.field);
		valueEl = document.getElementById(field.value);
		fieldEl.value = valueEl.textContent;
	}
	setOpenedWindowId(window.element);
	openWindow(true);
}

function isThereOpenWindow() {
	var id, el;

	id = getOpenedWindowId();
	if (!id) {
		return false;
	}
	el = document.getElementById(id);
	if (!el || el.style.display === "none") {
		forgetOpenedWindowId(id);
		return false;
	}
	return true;
}

function createEventDate(e) {
	var el, id;

	e.preventDefault();
	if (isThereOpenWindow()) {
		return;
	}
	id = "event-date_create";
	el = document.getElementById(id);
	if (!el) return;
	setOpenedWindowId(id);
	openWindow(true);
}

function editEventVenue(e) {
	var window;

	e.preventDefault();
	if (isThereOpenWindow()) {
		return;
	}
	window = {
		element: "event-venue_edit",
		fields: [
			{
				field: "event-venue_field",
				value: "event-venue"
			},
		]
	};
	openEditWindow(window)
}

function editEventNarrative(e) {
	var window;

	e.preventDefault();
	if (isThereOpenWindow()) {
		return;
	}
	window = {
		element: "event-narrative_edit",
		fields: [
			{
				field: "event-narrative_field",
				value: "event-narrative"
			},
		]
	};
	openEditWindow(window)
}

function editEventDescription(e) {
	var window;

	e.preventDefault();
	if (isThereOpenWindow()) {
		return;
	}
	window = {
		element: "event-description_edit",
		fields: [
			{
				field: "event-description_field",
				value: "event-description"
			},
		]
	};
	openEditWindow(window)
}

function closeWindow() {
	var elementId, el, errorEl;

	elementId = getOpenedWindowId();
	if (!elementId) return;
	el = document.getElementById(elementId);
	if (!el) return;
	errorEl = document.getElementById("window-form-error");
	if (errorEl) {
		errorEl.style.display = "none";
	}
	el.style.display = "none";
	el.style.removeProperty("top");
	el.style.removeProperty("left"); 
	el.style.removeProperty("margin-left");
	forgetOpenedWindowId();
}

function setWindowDragging(e) {
	var elementId, el, marginLeft, parentWidth, leftPercent, 
		realLeft, elWidth;

	e.preventDefault();
	elementId = getOpenedWindowId();
	el = document.getElementById(elementId);
	if (!WINDOW_DRAGGED) {
		parentWidth = el.offsetParent.offsetWidth;
		elWidth = el.offsetWidth;
		realLeft = parentWidth / 2 - elWidth / 2;
		el.style.left = realLeft + "px";
		el.style.marginLeft = "0";
		WINDOW_DRAGGED = true;
	}
	e.target.style.cursor = "grab";
	WINDOW_OFFSET_X = e.clientX - el.offsetLeft;
	WINDOW_OFFSET_Y = e.clientY - el.offsetTop;
	document.addEventListener("mousemove", startWindowDragging);
	document.addEventListener("mouseup", stopWindowDragging);
}

function startWindowDragging(e) {
	var elementId, el;

	e.preventDefault();
	elementId = getOpenedWindowId();
	el = document.getElementById(elementId);
	el.style.left = (e.clientX - WINDOW_OFFSET_X) + "px";
	el.style.top = (e.clientY - WINDOW_OFFSET_Y) + "px";
}

function stopWindowDragging(e) {
	var elementId, el;

	elementId = getOpenedWindowId();
	titleEl = document.getElementById(elementId + "_title-bar");
	titleEl.style.removeProperty("cursor");
	document.removeEventListener("mousemove", startWindowDragging);
	document.removeEventListener("mouseup", stopWindowDragging);
}

function openWindow(force) {
	var elementId, el, offsetX, offsetY, marginLeft, dragging, 
		parentWidth, leftPercent, realLeft, elWidth, dragged, 
		titleEl, closeEl;

	force = (typeof force !== "undefined") ? force : false;
	elementId = getOpenedWindowId();
	if (!elementId) {
		return;
	}
	errorEl = document.getElementById("window-form-error");
	if (!errorEl && !force) {
		closeWindow();
		return;
	}
	el = document.getElementById(elementId);
	if (!el) return;
	titleEl = document.getElementById(elementId + "_title-bar");
	closeEl = document.getElementById(elementId + "_close");
	WINDOW_DRAGGED = false;
	titleEl.removeEventListener("mousedown", setWindowDragging);
	closeEl.removeEventListener("click", closeWindow); 
	titleEl.addEventListener("mousedown", setWindowDragging);
	closeEl.addEventListener("click", closeWindow); 
	el.style.display = "block";
}

function setEvents() {
	var elementActions, element, currentEl;

	elementActions = [
		{
			element: "event-description_edit-button",
			event: "click",
			action: editEventDescription
		},
		{
			element: "event-narrative_edit-button",
			event: "click",
			action: editEventNarrative
		},
		{
			element: "event-venue_edit-button",
			event: "click",
			action: editEventVenue
		},
		{
			element: "event-date_create-button",
			event: "click",
			action: createEventDate
		},
	];

	for (var i = 0; i < elementActions.length; i++) {
		element = elementActions[i];
		currentEl = document.getElementById(element.element);
		if (!currentEl) continue;
		currentEl.addEventListener(element.event, element.action);
	}
}

function main() {
	var actionDependencies, timezoneActions;

	actionDependencies = [
		{
			actions: [ streamHome, streamHomeInfos ],
			depends: [ "EventSource", "JSON" ]
		}
	];
	timezoneActions = [
		{
			action: setTimezoneFromIntl,
			depends: [ "Intl" ]
		},
		{
			action: setTimezoneFromDate,
			depends: [ "Date" ]
		}
	];
	runTimezoneAction(timezoneActions);
	runActions(actionDependencies);
	setEvents();
	openWindow();
}

main();
