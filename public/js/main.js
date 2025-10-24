// main.js

var HOME_STREAM;
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
	setCookie('timezone', timezone);
}

function setTimezoneFromIntl() {
	var timezone;
	timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	setCookie('timezone', timezone);
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
	var event, i, el;
	for (i = 0; i < HOME_STREAM_ELEMENTS.length; i++) {
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
	for (i = 0; i < HOME_STREAM_ELEMENTS.length; i++) {
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

if (
	typeof Intl !== "undefined" &&
	typeof Intl.DateTimeFormat().resolvedOptions().timeZone !== "undefined"
) {
	setTimezoneFromIntl();
} else if (
	typeof Date !== "undefined"
) {
	setTimezoneFromDate();
}

if (
	typeof EventSource !== "undefined" &&
	typeof JSON !== "undefined"
) {
	streamHome();
	streamHomeInfos();
}

