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
	e.currentTarget.style.cursor = "grab";
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
	var elementId, el, titleEl;

	elementId = getOpenedWindowId();
	titleEl = document.getElementById(elementId + "_title-bar");
	titleEl.style.removeProperty("cursor");
	document.removeEventListener("mousemove", startWindowDragging);
	document.removeEventListener("mouseup", stopWindowDragging);
}

export function openWindow(force) {
	var elementId, el, offsetX, offsetY, marginLeft, dragging, 
		parentWidth, leftPercent, realLeft, elWidth, dragged, 
		titleEl, closeEl, errorEl;

	force = (typeof force !== "undefined") ? force : false;
	elementId = getOpenedWindowId();
	if (!elementId) {
		return;
	}
	errorEl = document.getElementById("window-form-error");
	if (!errorEl && !force) {
		closeWindow();
		return;
	} else if (errorEl && force) {
		errorEl.style.display = "none";
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

export function openDeleteItemWindow(e) {
        var windowId, formId, actionLink, formEl, content, contentId, 
		contentEl, windowEl, baseId, actionEl;

        e.preventDefault();
        if (isThereOpenWindow()) {
                return;
        } 
        contentId = e.currentTarget.id.replace("_delete-button", "");
	baseId = contentId.replace(/-\d+/g, "");
        windowId = baseId + "_delete";
        actionEl = document.getElementById(contentId + "_delete-link");
        actionLink = actionEl.value || actionEl.dataset.action;
	windowEl = document.getElementById(windowId);
	formEl = windowEl.getElementsByTagName("form")[0];
	formEl.action = actionLink;
	content = document.getElementById(contentId).textContent;
	contentEl = document.getElementById(baseId + "_delete-content");
	contentEl.textContent = content;
	setOpenedWindowId(windowId);
	openWindow(true);
}

export function openDeleteWindowOnWindow(e) {
        var windowId, formId, actionLink, formEl, content, contentId, 
		contentEl, windowEl, baseId, itemId;

        e.preventDefault();
	closeWindow();
        contentId = e.currentTarget.id.replace("_delete-button", "");
	itemId = document.getElementById(contentId + "_id").value;
	contentId = contentId + "-" + itemId;
	baseId = contentId.replace(/-\d+/g, "");
        windowId = baseId + "_delete";
	actionLink = document.getElementById(contentId + "_delete-link").value;
	windowEl = document.getElementById(windowId);
	formEl = windowEl.getElementsByTagName("form")[0];
	formEl.action = actionLink;
	content = document.getElementById(contentId).textContent;
	contentEl = document.getElementById(baseId + "_delete-content");
	contentEl.textContent = content;
	setOpenedWindowId(windowId);
	openWindow(true);
}

export function openEditItemWindow(windowEl) {
        var actionLink, formId, formEl, element, deleteEl, baseId;

	actionLink = document.getElementById(windowEl.item + "_update-link")
		.value;
	element = document.getElementById(windowEl.element);
	formEl = element.getElementsByTagName("form")[0];
	formEl.action = actionLink;
	if (!windowEl.hasDelete) {
		openEditWindow(windowEl);
		return;
	}
	baseId = windowEl.item.replace(/-\d+/g, "");
	deleteEl = document.getElementById(baseId + "_delete-button");
	deleteEl.removeEventListener("click", openDeleteWindowOnWindow);
	deleteEl.addEventListener("click", openDeleteWindowOnWindow);
	openEditWindow(windowEl);
}

export function prepareOpenWindow(e, id) {
	e.preventDefault();
	if (isThereOpenWindow()) {
		return;
	}
	setOpenedWindowId(id);
	openWindow(true);
}

