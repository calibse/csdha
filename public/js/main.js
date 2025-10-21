// main.js

function setCookie(name, value) {
    var now, expires;
    now = new Date();
    now.setFullYear(now.getFullYear() + 1);
    expires = now.toUTCString();
    document.cookie = name + "=" + value + "; expires=" + expires 
        + "; samesite=lax; path=/";
}

function supportsTimeZoneName() {
    try {
        if (typeof Intl === "object" && 
                typeof Intl.DateTimeFormat === "function") {
            var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
            if (typeof tz === "string" && tz.length > 0) {
                return true;
            }
        }
    } catch (e) {}
    return false;
}

function setTimezone() {
    var timezone;
    if (supportsTimeZoneName()) {
        timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    } else {
        timezone = new Date().getTimezoneOffset();
    }
    setCookie('timezone', timezone);
}

setTimezone();

