<?php
session_start();
if (!$_SESSION['logged_in']) {
    header('Location: index.php');
    exit;
}

$config = json_decode(file_get_contents('config.json'), true);
$message = '';
$upload_dir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save updated admin password
    $config['admin_username'] = $_POST['admin_username'];
    if (!empty($_POST['admin_password'])) {
        $config['admin_password'] = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
    }
    //Branding and Logo
    $config['wifi_name'] = $_POST['wifi_name'];
    $config['logo_file'] = $_POST['logo_file'];

    // Save Omada info
    $config['controller_ip'] = $_POST['controller_ip'];
    $config['port'] = $_POST['port'];
    $config['controller_id'] = $_POST['controller_id'];
    $config['operator_username'] = $_POST['operator_username'];
    $config['operator_password'] = $_POST['operator_password'];
    $config['site_name'] = $_POST['site_name'];
    $config['ssid_name'] = $_POST['ssid_name'];
    $config['ap_mac'] = $_POST['ap_mac'];

    // Save packages with hours + minutes
    $config['packages']['1'] = [
        "hours" => $_POST['package_1_hours'],
        "minutes" => $_POST['package_1_minutes']
    ];

    $config['packages']['5'] = [
        "hours" => $_POST['package_5_hours'],
        "minutes" => $_POST['package_5_minutes']
    ];

    $config['packages']['10'] = [
        "hours" => $_POST['package_10_hours'],
        "minutes" => $_POST['package_10_minutes']
    ];

    $config['packages']['20'] = [
        "hours" => $_POST['package_20_hours'],
        "minutes" => $_POST['package_20_minutes']
    ];

    if(isset($_FILES['logo_upload']) && $_FILES['logo_upload']['error'] == 0) {
        $filename = basename($_FILES['logo_upload']['name']);
        $target = $upload_dir . $filename;

        move_uploaded_file($_FILES['logo_upload']['tmp_name'], $target);

        $config['logo_file'] = $filename;
    }

    file_put_contents('config.json', json_encode($config, JSON_PRETTY_PRINT));
    $message = "Configuration saved successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="dashboard-container">
    <h2>Admin Dashboard</h2>
    <?php if($message) echo "<p class='success'>$message</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Admin Username:</label>
        <input type="text" name="admin_username" value="<?php echo htmlspecialchars($config['admin_username']); ?>" required>
        
        <label>Admin Password:</label>
        <input type="password" name="admin_password" placeholder="Leave blank to keep current password">

	<label>Wifi Name:</label>
	<input type="text" name="wifi_name"
	    value="<?php echo $config['wifi_name'] ?? ''; ?>">

	<label>Logo File Name:</label>
	<input type="text" name="logo_file"
	    value="<?php echo $config['logo_file'] ?? ''; ?>">

	<a class="download-btn"
	   href="uploads/<?php echo $config['logo_file']; ?>"
	   download>
	   Download
	</a>
	
	<br><br>
	
	<label>Upload New Logo:</label>
	<input type="file" name="logo_upload">


        <label>Controller IP Address:</label>
        <input type="text" name="controller_ip" value="<?php echo htmlspecialchars($config['controller_ip']); ?>">

        <label>Port:</label>
        <input type="text" name="port" value="<?php echo htmlspecialchars($config['port']); ?>">

        <label>Controller ID:</label>
        <input type="text" name="controller_id" value="<?php echo htmlspecialchars($config['controller_id']); ?>">

        <label>Operator Username:</label>
        <input type="text" name="operator_username" value="<?php echo htmlspecialchars($config['operator_username']); ?>">

        <label>Operator Password:</label>
        <input type="password" name="operator_password" value="<?php echo htmlspecialchars($config['operator_password']); ?>">

        <label>Site Name:</label>
        <input type="text" name="site_name" value="<?php echo htmlspecialchars($config['site_name']); ?>">

        <label>SSID Name:</label>
        <input type="text" name="ssid_name" value="<?php echo htmlspecialchars($config['ssid_name']); ?>">

        <label>AP MAC Address:</label>
        <input type="text" name="ap_mac" value="<?php echo htmlspecialchars($config['ap_mac']); ?>">

	<hr>
	<h3>Time Packages (Hours & Minutes)</h3>

	<div class="package-table">

	    <label>1 Peso</label>
	    <input type="number" name="package_1_hours" min="0"
 	       value="<?php echo $config['packages']['1']['hours'] ?? 0; ?>"> Hours
	    <input type="number" name="package_1_minutes" min="0" max="59"
	        value="<?php echo $config['packages']['1']['minutes'] ?? 0; ?>"> Minutes


	    <label>5 Pesos</label>
	    <input type="number" name="package_5_hours" min="0"
	        value="<?php echo $config['packages']['5']['hours'] ?? 0; ?>"> Hours
	    <input type="number" name="package_5_minutes" min="0" max="59"
	        value="<?php echo $config['packages']['5']['minutes'] ?? 0; ?>"> Minutes


	    <label>10 Pesos</label>
	    <input type="number" name="package_10_hours" min="0"
	        value="<?php echo $config['packages']['10']['hours'] ?? 0; ?>"> Hours
	    <input type="number" name="package_10_minutes" min="0" max="59"
	        value="<?php echo $config['packages']['10']['minutes'] ?? 0; ?>"> Minutes


	    <label>20 Pesos</label>
	    <input type="number" name="package_20_hours" min="0"
	        value="<?php echo $config['packages']['20']['hours'] ?? 0; ?>"> Hours
	    <input type="number" name="package_20_minutes" min="0" max="59"
	        value="<?php echo $config['packages']['20']['minutes'] ?? 0; ?>"> Minutes

	</div>


        <button type="submit">Save</button>
    </form>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>