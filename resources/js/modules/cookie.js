/* cookie.js */

export function setCookie(name, value) {
	var now, expires;
	now = new Date();
	now.setFullYear(now.getFullYear() + 1);
	expires = now.toUTCString();
	document.cookie = name + "=" + value + "; expires=" + expires 
		+ "; samesite=lax; path=/";
}

