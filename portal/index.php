<?php
require_once "config_loader.php";

// Get client IP
$client_ip = $_SERVER['REMOTE_ADDR'];

// Get client MAC Orange Pi
$leases_file = '/var/lib/misc/dnsmasq.leases';
$client_mac = 'unknown';

if (file_exists($leases_file)) {
    $leases = file($leases_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($leases as $lease) {
        // Split lease line by spaces
        $parts = preg_split('/\s+/', $lease);
        // parts[2] = IP, parts[1] = MAC
        if (isset($parts[2]) && $parts[2] === $client_ip) {
            $client_mac = $parts[1];
            break;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Client Portal</title>
    <link rel="stylesheet" href="/portal/css/style.css">
</head>
<body>

<div class="container">

    <img src="<?php echo LOGO_FILE; ?>" class="logo">

    <h1 class="wifi-name"><?php echo WIFI_NAME; ?></h1>

    <div class="timer" id="remainingTime">
        00:00:00
    </div>

    <div class="client-info">
        <p>IP: <?php echo $client_ip; ?> |
        MAC: <?php echo $client_mac; ?></p>
    </div>

    <div class="balance">
        Account Balance: ₱ <span id="accountBalance">0.00</span>
    </div>

    <div class="buttons">
        <button onclick="openCoinModal()">Insert Coins</button>
        <button onclick="useVoucher()">Voucher</button>
    </div>

    <div class="status">
        Controller Status:
        <span id="controllerStatus">Checking...</span><br>

        Time Server Status:
        <span id="timeServerStatus">Checking...</span>
    </div>

</div>

<!-- Coin Insert Modal -->
<div id="coinModal" class="modal">
    <div class="modal-content">
        <h2>Please Insert Coins</h2>

        <p id="countdownDisplay">Waiting for 60 seconds</p>

        <div class="progress-bar">
            <div id="progressFill"></div>
        </div>

        <p>Time: <span id="estimatedTime">0 minutes</span></p>
        <p>Total Amount: ₱ <span id="totalAmount">0</span></p>

        <button id="cancelBtn" onclick="finishCoinSession()">Cancel</button>
    </div>
</div>


<script>
    const CLIENT_MAC = "<?php echo $client_mac; ?>";

    // Load packages from config.json
    const PACKAGES = <?php echo json_encode(PACKAGES); ?>;


    const clientMac = "<?php echo $client_mac; ?>";
    const apMac = "<?php echo AP_MAC; ?>";
    const ssidName = "<?php echo SSID_NAME; ?>";
    const radioId = "<?php echo RADIO_ID_24G; ?>";  // or RADIO_ID_5G depending
    const site = "<?php echo SITE_NAME; ?>";

</script>
<script src="/portal/js/app.js"></script>


</body>
</html>