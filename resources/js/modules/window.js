// main.js

var WINDOW_DRAGGED, WINDOW_OFFSET_X, WINDOW_OFFSET_Y;

export function setOpenedWindowId(id) {
	var key, elementId;

	key = "opened_window";
	sessionStorage.setItem(key, id);
}

export function getOpenedWindowId() {
	var key, elementId;

	key = "opened_window";
	elementId = sessionStorage.getItem(key);
	return elementId;
}

export function forgetOpenedWindowId() {
	var key, elementId;

	key = "opened_window";
	sessionStorage.removeItem(key);
}

export function openEditWindow(windowEl) {
	var field, valueEl, fieldEl;

	for (var i = 0; i < windowEl.fields.length; i++) {
		field = windowEl.fields[i];
		fieldEl = document.getElementById(field.field);
		valueEl = document.getElementById(field.value);
		fieldEl.value = valueEl.textContent;
	}
	setOpenedWindowId(windowEl.element);
	openWindow(true);
}

export function isThereOpenWindow() {
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

export function closeWindow() {
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

export function setWindowDragging(e) {
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

export function startWindowDragging(e) {
	var elementId, el;

	e.preventDefault();
	elementId = getOpenedWindowId();
	el = document.getElementById(elementId);
	el.style.left = (e.clientX - WINDOW_OFFSET_X) + "px";
	el.style.top = (e.clientY - WINDOW_OFFSET_Y) + "px";
}

export function stopWindowDragging(e) {
	var elementId, el;

	elementId = getOpenedWindowId();
	titleEl = document.getElementById(elementId + "_title-bar");
	titleEl.style.removeProperty("cursor");
	document.removeEventListener("mousemove", startWindowDragging);
	document.removeEventListener("mouseup", stopWindowDragging);
}

export function openWindow(force) {
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

