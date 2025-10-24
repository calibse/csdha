// app.js

import "./bootstrap";
import.meta.glob([
    "../images/**",
    "../fonts/**",
]);

//import QrScanner from "qr-scanner";
//import "qr-scanner/qr-scanner.legacy.min.js";

let CURRENT_REQUEST = null;
let QR_SCANNER = null;

function getCookie(name) {
	return document.cookie.split('; ')
		.find(row => row.startsWith(name + '='))?.split('=')[1];
}

async function storeAttendance(token) {
	var timezone
    if (CURRENT_REQUEST) {
        const apiResponse = await CURRENT_REQUEST;
        return apiResponse.status;
    }
    const eventField = document.querySelector("#event");
    try {
	timezone = getCookie('timezone');
	if (!timezone) timezone = 'UTC';
        CURRENT_REQUEST = axios.post("/api/attendance/" + eventField.value, {
            token: token,
            timezone: timezone
        });
        const apiResponse =  await CURRENT_REQUEST;
        return apiResponse.status;
    } finally {
        CURRENT_REQUEST = null;
    }
}

function showQrScannerStatus(type) {
    const idScanner = document.getElementById("id-scanner");
    if (!idScanner) return;
    const indicator = idScanner.querySelector(".indicator");
    const statusVal = JSON.parse(idScanner.querySelector(".status-values")
        .textContent);
    const timeout = indicator.querySelector(".timeout");
    const statusText = indicator.querySelector(".status .text");
    void indicator.offsetWidth;
    indicator.classList.value = "indicator " + statusVal[type].class;
    statusText.textContent = statusVal[type].text;
    if (["success", "failure"].includes(type)) {
        timeout.addEventListener("animationend", function callback() {
            indicator.classList.remove(statusVal[type].class);
            timeout.removeEventListener("animationend", callback);
            void indicator.offsetWidth;
            indicator.classList.value = "indicator " + statusVal.idle.class;
            statusText.textContent = statusVal.idle.text;
        });
    }
}

function startQrScanner() {
    showQrScannerStatus("idle");
    const videoEl = document.querySelector('#id-scanner .video');
    if (!videoEl) return;
    const idScanner = document.getElementById("id-scanner");
    idScanner.hidden = false;
    QR_SCANNER = QR_SCANNER || new window.QrScanner(videoEl, async (result) => {
        showQrScannerStatus("processing");
        const statusCode = await storeAttendance(result.data);
        switch (statusCode) {
        case 200:
            showQrScannerStatus("success");
            break;
        case 403:
            showQrScannerStatus("forbidden");
            break;
        case 404:
            showQrScannerStatus("failure");
            break;
        }
    }, {
        returnDetailedScanResult: true
    });
    QR_SCANNER.start();
}

function stopQrScanner() {
    const idScanner = document.getElementById("id-scanner");
    if (!idScanner) return;
    idScanner.hidden = true;
    if (QR_SCANNER) {
        QR_SCANNER.stop();
    }
}

function activateAttendanceRecorder() {
    let mainEl;
    const mainPage = document.querySelector(".main-content.attendance "
        + ".article");
    if (!mainPage) return;
    if (!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)) {
        const el = document.createElement("p");
        el.textContent = "It looks like camera is not supported in this web "
            + "browser.";
        mainPage.append(el);
        return;
    }
    const mainElTemp = document.querySelector('.attendance #scanner-feature');
    if (!mainElTemp) {
        return;
    } else {
        mainEl = mainElTemp.content.cloneNode(1);
    }
    const idScanner = mainEl.getElementById("id-scanner");
    if (idScanner) {
        idScanner.hidden = true;
    }
    mainElTemp.before(mainEl);
    const selectEventEl = mainPage.querySelector("select#event");
    if (selectEventEl) {
        selectEventEl.addEventListener("change", (event) => {
            if (event.target.value) {
                startQrScanner();
            }
            else stopQrScanner();
        });
        return;
    }
    startQrScanner();
}

activateAttendanceRecorder();
