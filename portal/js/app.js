let coinPollInterval = null;
let timerInterval = null;

let lastTotal = 0;
let totalAmount = 0;
let countdown = 60;


/* ===============================
   OPEN MODAL
================================= */
function openCoinModal() {

    fetch("/portal/check_vendo.php?mac=" + CLIENT_MAC)
    .then(res => res.text())
    .then(status => {

        if (status.trim() === "BUSY") {
            alert("Vendo is Busy. Please wait.");
            return;
        }

        const modal = document.getElementById("coinModal");
        if (!modal) return;

        modal.classList.add("show");

        fetch("/portal/reset_coins.php?nocache=" + Date.now())
        .then(() => {

            lastTotal = 0;
            totalAmount = 0;
            countdown = 60;

            updateCountdownDisplay();
            updateDisplay();

            startCountdown();
            startPolling();

        });

    });

}


/* ===============================
   COUNTDOWN
================================= */
function startCountdown() {

    clearInterval(timerInterval);

    timerInterval = setInterval(() => {

        if (countdown > 0) {

            countdown--;
            updateCountdownDisplay();

        } else {

            clearInterval(timerInterval);
            closeCoinModal();
        }

    }, 1000);
}


function updateCountdownDisplay() {
    const el = document.getElementById("countdownDisplay");
    if (el) {
        el.innerText = countdown + " seconds";
    }
}


/* ===============================
   POLL COINS
================================= */
function startPolling() {

    clearInterval(coinPollInterval);

    coinPollInterval = setInterval(() => {

        fetch("/portal/get_coins.php?nocache=" + Date.now())
        .then(res => res.text())
        .then(data => {

            let newTotal = parseInt(data.trim());

            if (isNaN(newTotal)) {
                console.log("Invalid response:", data);
                return;
            }

            // If new coin inserted → reset countdown
            if (newTotal > lastTotal) {
                countdown = 60;
                updateCountdownDisplay();
            }

            lastTotal = newTotal;
            totalAmount = newTotal;

            updateDisplay();

        })
        .catch(err => console.error("Fetch error:", err));

    }, 1000);
}


/* ===============================
   TIME COMPUTATION
================================= */
function computeMinutes(coins) {

    let minutes = 0;

    // Convert packages into sorted array (highest price first)
    let packages = Object.keys(PACKAGES)
        .map(price => ({
            price: Number(price),
            minutes: (Number(PACKAGES[price].hours) * 60) + Number(PACKAGES[price].minutes)
        }))
        .sort((a, b) => b.price - a.price);

    for (let pkg of packages) {

        while (coins >= pkg.price) {
            minutes += pkg.minutes;
            coins -= pkg.price;
        }

    }

    return minutes;
}


function formatTime(mins) {

    let h = Math.floor(mins / 60);
    let m = mins % 60;

    if (h > 0 && m > 0) return h + " hr " + m + " min";
    if (h > 0) return h + " hr";
    return m + " min";
}


/* ===============================
   UPDATE DISPLAY
================================= */
function updateDisplay() {

    const totalEl = document.getElementById("totalAmount");
    if (totalEl) {
        totalEl.innerText = totalAmount;
    }

    const estimatedEl = document.getElementById("estimatedTime");
    if (estimatedEl) {
        estimatedEl.innerText = formatTime(computeMinutes(totalAmount));
    }

    const cancelBtn = document.getElementById("cancelBtn");
    if (cancelBtn) {
        cancelBtn.innerText = (totalAmount > 0) ? "Done" : "Cancel";
    }
}


/* ===============================
   CLOSE MODAL
================================= */
function closeCoinModal() {

    const modal = document.getElementById("coinModal");
    if (modal) {
        modal.classList.remove("show");
    }

    clearInterval(timerInterval);
    clearInterval(coinPollInterval);

    fetch("/portal/reset_coins.php?nocache=" + Date.now());

    // release vendo
    fetch("/portal/release_vendo.php");

}


/* ===============================
   FINISH SESSION
================================= */
function finishCoinSession() {

    if (totalAmount <= 0) {
        closeCoinModal();
        return;
    }

    let minutes = computeMinutes(totalAmount);

    fetch("/portal/api/start_session.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body:
            "clientMac=" + encodeURIComponent(clientMac) +
            "&apMac=" + encodeURIComponent(apMac) +
            "&ssidName=" + encodeURIComponent(ssidName) +
            "&radioId=" + encodeURIComponent(radioId) +
            "&site=" + encodeURIComponent(site) +
            "&minutes=" + minutes

    })
    .then(res => res.text())
    .then(text => {

        console.log("Server response:", text);

        let data = JSON.parse(text);

        if (data.errorCode === 0) {

            window.location.href = "success.html";

        } else {

            alert("Authentication failed");

        }

    })
    .catch(err => console.error(err));

}