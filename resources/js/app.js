// app.js

import "./bootstrap";
import {Html5QrcodeScanner} from "html5-qrcode";
import quagga from "@ericblade/quagga2"; 
import QrScanner from "qr-scanner";

import.meta.glob([
    "../images/**",
    "../fonts/**",
]);

let CURRENT_REQUEST = null;

hideNoscript();
setTimezone();
activateAttendanceRecorder();
showAttachmentPreview();

function hideNoscript() {
    const noscript = document.querySelector("#noscript");
    if (noscript) noscript.remove();
}

function showAttachmentPreview() {
    const mainPage = document.querySelector(".main-content.events.attachments.form.create");
    if (!mainPage) return;
    const viewLinksEl = mainPage.querySelector("#attachment-view-links");
    const viewsEl= mainPage.querySelector("#attachment-views");
    const viewLinkTemp = mainPage.querySelector("#attachment-view-link-temp").content;
    const viewTemp = mainPage.querySelector("#attachment-view-temp").content;
    const fileInput = mainPage.querySelector("#images-input");
    let blobUrls = [];
    fileInput.addEventListener("change", () => {
        for (let url of blobUrls) {
            URL.revokeObjectURL(url);
            blobUrls = [];
        }
        viewLinksEl.replaceChildren();
        const files = fileInput.files;
        for (let i = 0; i < files.length; i++) {
            const url = URL.createObjectURL(files[i]);
            const viewLinkEl = viewLinkTemp.cloneNode(true);
            const imageEl = viewLinkEl.querySelector("img");
            const viewEl = viewTemp.cloneNode(true);
            const fullImageEl = viewEl.querySelector("img");
            const linkEl = viewLinkEl.querySelector("a");
            imageEl.src = url;
            fullImageEl.src = url;
            viewEl.firstElementChild.id = `attachment-item-${i}`;
            linkEl.href = `#attachment-item-${i}`;
            viewLinksEl.appendChild(viewLinkEl);
            viewsEl.appendChild(viewEl);
        } 
    });
}

function setTimezone() {
    let timezone;
    timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    setCookie('timezone', timezone);
}

function setCookie(name, value) {
    const now = new Date();
    now.setFullYear(now.getFullYear() + 1);
    const expires = now.toUTCString(); 
    document.cookie = `${name}=${value}; expires=${expires}; sameSite=Lax; path=/`;
}

function activateToggler(togglerSelector, selector) {
    let e, f;
    e = document.querySelector(selector);
    f = document.querySelector(togglerSelector);
    if (e && f) {
       f.addEventListener("click", () => {
           e.classList.toggle("visible");
       });
   }
}

function activateExpander(togglerSelector, selector) {
    let e, f, body;
    e = document.querySelector(selector);
    f = document.querySelector(togglerSelector);
    body = document.querySelector("body");
    if (e && f) {
       f.addEventListener("click", () => {
           e.classList.remove("hidden");
           body.classList.add("underlay");
           e.firstElementChild.focus();
       });
   }
}

function activateCollapser(togglerSelector, selector) {
    let e, f, body;
    e = document.querySelector(selector);
    f = document.querySelector(togglerSelector);
    body = document.querySelector("body");
    if (e && f) {
       f.addEventListener("click", () => {
           e.classList.add("hidden");
           body.classList.remove("underlay");
       });
   }
}

function addClass(classList, elements) {
    elements.forEach((e) => {
        const element = document.querySelector(e);
        if (element) {
            element.classList.add(...classList);
        }
    });
}


function showIdleStatus() {
    const indicator = document.querySelector("#barcode .indicator");
    const statuses = document.querySelector("template.statuses").content;
    const statusText = indicator.querySelector(".status");
    if (statusText) {
        statusText.remove();
    }
    let statusEl = statuses.querySelector(".idle").cloneNode(true);
    indicator.appendChild(statusEl);
}

function removeIdleStatus() {
    const statusText = document.querySelector("#barcode .indicator .status");
    if (statusText) {
        statusText.remove();
    }
}

function initQuagga() {
    quagga.init({
        inputStream: {
            name : "Live",
            type : "LiveStream",
            target: document.querySelector('#barcode .scanner'),
            constraints: {
                width: {min: 640},
                height: {min: 480},
                facingMode: "environment",
                aspectRatio: {min: 1, max: 2}
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: true
        },
        numOfWorkers: 2,
        frequency: 10,
        decoder: {
            readers : [{
                format: "code_39_reader",
                config: {}
            }]
        },
        locate: true
    }, function(err) {
        if (err) {
            return
        }
        quagga.start();
        showIdleStatus();
        const videoStream = quagga.canvas.dom.overlay;
        const canvas = videoStream.getBoundingClientRect();
        const barcodeElement = document.querySelector("#barcode");
        barcodeElement.style.width = canvas.width + "px";   
    });

    quagga.onProcessed(function (result) {
        let drawingCtx = quagga.canvas.ctx.overlay;
        let drawingCanvas = quagga.canvas.dom.overlay;
        if (result) {
            if (result.boxes) {
                drawingCtx.clearRect(0, 0, parseInt(drawingCanvas
                    .getAttribute("width")), parseInt(drawingCanvas
                    .getAttribute("height")));
                result.boxes.filter(function (box) {
                    return box !== result.box;
                }).forEach(function (box) {
                    quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, 
                        {color: "green", lineWidth: 2});
                });
            }
            if (result.box) {
                quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, 
                    drawingCtx, {color: "#00F", lineWidth: 2});
            }
            if (result.codeResult && result.codeResult.code) {
                quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, 
                    drawingCtx, {color: 'red', lineWidth: 3});
            }
        }
    });
    const indicator = document.querySelector("#barcode .indicator");
    const timeout = indicator.querySelector(".timeout");
    const statuses = document.querySelector("template.statuses").content;
    quagga.onDetected(async function (data) {
        const statusText = indicator.querySelector(".status");
        if (statusText) {
            statusText.remove();
        }
        const statusEl = statuses.querySelector(".processing").cloneNode(true);
        indicator.appendChild(statusEl);
        indicator.classList.remove("success", "failure", "processing");
        void indicator.offsetWidth;
        indicator.classList.add("processing");
        let status = await storeAttendance(data.codeResult.code);
        if (status === 200) {
            const statusText = indicator.querySelector(".status");
            if (statusText) {
                statusText.remove();
            }
            const statusEl = statuses.querySelector(".success").cloneNode(true);
            indicator.appendChild(statusEl);
            const resultCode = document.querySelector("#result-code");
            resultCode.textContent = data.codeResult.code;
            indicator.classList.remove("success", "processing");
            void indicator.offsetWidth;
            indicator.classList.add("success");
            timeout.addEventListener("animationend", function callback() {
                indicator.classList.remove("success");
                timeout.removeEventListener("animationend", callback);            
                showIdleStatus();
            });
        } else if (status === 404) {
            const statusText = indicator.querySelector(".status");
            if (statusText) {
                statusText.remove();
            }
            const statusEl = statuses.querySelector(".failure").cloneNode(true);
            indicator.appendChild(statusEl);
            const resultCode = document.querySelector("#result-code");
            resultCode.textContent = data.codeResult.code;
            indicator.classList.remove("failure", "processing");
            void indicator.offsetWidth;
            indicator.classList.add("failure");
            timeout.addEventListener("animationend", function callback() {
                indicator.classList.remove("failure");
                timeout.removeEventListener("animationend", callback);            
                showIdleStatus();
            });
        }
    });
}

function quaggajs() {
    hideNoscript();
    const attendancePage = document.querySelector(".main-content.attendance " +
        ".scripting");
    if (!attendancePage) return;
    attendancePage.hidden = false;
    const barcodeScannerElement = document.querySelector("#barcode .scanner");
    const resultCode = document.querySelector("#result-code");
    const eventField = document.querySelector("#event");
    if (!barcodeScannerElement) {
        return;
    }
    if (eventField.value) {
        initQuagga();
    } else {
        quagga.stop();
        removeIdleStatus();
    }
    eventField.addEventListener("change", function () {
        if (this.value) {
            initQuagga();
        } else {
            quagga.stop();
            removeIdleStatus();
            while (barcodeScannerElement.firstChild) {
                barcodeScannerElement.removeChild(barcodeScannerElement
                    .firstChild);
            }
        }
    });
}


function showBarcodeScanner() {
    hideNoscript();
    const attendancePage = document.querySelector(".main-content.attendance " +
        ".scripting");
    if (!attendancePage) return;
    attendancePage.hidden = false;

    const barcodeScannerElement = document.querySelector("#barcode-scanner");
    const eventField = document.querySelector("#event");
    if (!barcodeScannerElement) {
        return;
    }
    barcodeScannerElement.hidden = eventField.value ? false : true;
    eventField.addEventListener("change", () => {
        barcodeScannerElement.hidden = eventField.value ? false : true;
    });
    const html5QrcodeScanner = new Html5QrcodeScanner("barcode-scanner", {
        fps: 10,
    }, true);
    html5QrcodeScanner.render((decodedText, decodedResult) => {
        storeAttendance(decodedText);
    }, (error) => {
        // console.warn(`Code scan error = ${error}`);
    });
}

async function storeAttendance(token) {
    if (CURRENT_REQUEST) {
        const apiResponse = await CURRENT_REQUEST;
        return apiResponse.status;
    }
    const eventField = document.querySelector("#event");
    try { 
        CURRENT_REQUEST = axios.post(`/api/attendance/${eventField.value}`, {
            token: token
        });
        const apiResponse =  await CURRENT_REQUEST;
        return apiResponse.status;
    } finally {
        CURRENT_REQUEST = null;
    }
}

function addCloseMenuButton() {
    const parent = document.querySelector(".main-header .content-block");
    if (parent) {
        const closeMenuButton = document.createElement("button");
        closeMenuButton.classList.add("close-menu-button");
        parent.prepend(closeMenuButton);
    }
}

function addMenuButton() {
    const oldMenuButton = document.querySelector('.menu-button')
    const template = document.querySelector("#menu-button-template");
    if (oldMenuButton) {
        oldMenuButton.remove();
    }
    if (template) {
        const parent = document.querySelector(".main-content-header " +
            ".content-block nav");
        parent.prepend(template.content);
    }
}

function camelToSnake(str) {
    return str.replace(/([a-z0-9])([A-Z])/g, '$1_$2').toLowerCase();
}

function removeUrlLastSegment(url) {
    let u = new URL(url);
    u.pathname = u.pathname
        .replace(/\/[^\/?#]+\/?$/, '')
        .replace(/\/+$/, '');  
    return u.toString();
}

function activateDeleteButton(...items) {
    items.forEach(e => {
        const items = document.querySelectorAll(".item." + e);
        if (!items.length) return false;
        const dialog = document.getElementById("delete-" + e + "-dialog");
        const dialogForm = dialog.querySelector("form");
        const contentToDelete = dialog.querySelector(".content-delete");
        items.forEach(item => {
            const form = item.querySelector(".delete-action");
            const content = item.querySelector(".content");
            const button = item.querySelector(".delete-action button");
            button.addEventListener("click", function() {
                contentToDelete.textContent = " " + content.textContent;
                dialogForm.action = removeUrlLastSegment(form.action);
                dialog.showModal();
            });
        })
    })
}

function activateEditButton(...items) {
    items.forEach(e => {
        const forms = document.querySelectorAll(".item." + e + " .edit-action");
        if (!forms.length) return false;
        const dialog = document.getElementById("edit-" + e + "-dialog");
        const dialogForm = dialog.querySelector("form");
        forms.forEach(form => {
            const button = form.querySelector("button");
            const fields = JSON.parse(button.parentElement.querySelector(".field-values").textContent);
            button.addEventListener("click", function () {
                dialogForm.action = removeUrlLastSegment(form.action);
                for (const name in fields) {
                    const fieldElement = dialogForm.elements.namedItem(camelToSnake(name));
                    fieldElement.value = fields[name];
                }
                dialog.showModal();
            });
        })
    })
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
    indicator.classList.value = `indicator ${statusVal[type].class}`;
    statusText.textContent = statusVal[type].text;
    if (["success", "failure"].includes(type)) {
        timeout.addEventListener("animationend", function callback() {
            indicator.classList.remove(statusVal[type].class);
            timeout.removeEventListener("animationend", callback);            
            void indicator.offsetWidth;
            indicator.classList.value = `indicator ${statusVal.idle.class}`;
            statusText.textContent = statusVal.idle.text;
        });
    }
}

function startQrScanner() {
    showQrScannerStatus("idle");
    const videoEl = document.querySelector('#id-scanner .video');
    if (!videoEl) return;
    const qrScanner = new QrScanner(videoEl, async (result) => {
            showQrScannerStatus("processing");
            const statusCode = await storeAttendance(result.data);
            switch (statusCode) {
            case 200:
                showQrScannerStatus("success");
                break;
            case 404:
                showQrScannerStatus("failure");
                break;
            }
        }, {
            returnDetailedScanResult: true
        }
    );
    qrScanner.start();
}

function activateAttendanceRecorder() {
    const mainPage = document.querySelector('.main-content.attendance .article');
    if (!mainPage) return;
    if (!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia)) {
        const el = document.createElement("p");
        el.textContent = "It looks like camera is not supported in this web browser.";
        mainPage.append(el);
        return;
    }
    const mainElTemp = document.querySelector('.attendance #scanner-feature');
    const mainEl = mainElTemp?.content.cloneNode(1);
    if (mainElTemp) { 
        mainElTemp.before(mainEl);
        startQrScanner();
    }
}


/*
addMenuButton();
addCloseMenuButton();
activateExpander(".menu-button", ".main-header");
activateCollapser(".close-menu-button", ".main-header");
quaggajs();
activateEditButton('event-date', 'gspoa-event');
activateDeleteButton('event-date', 'gspoa-event');
*/
