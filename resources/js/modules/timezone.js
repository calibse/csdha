/* timezone.js */

import * as cookie from "./cookie.js";

export function setTimezoneFromDate() {
	var timezone;
	timezone = new Date().getTimezoneOffset();
	cookie.setCookie("timezone", timezone);
}

export function setTimezoneFromIntl() {
	var timezone;
	timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
	cookie.setCookie("timezone", timezone);
}

