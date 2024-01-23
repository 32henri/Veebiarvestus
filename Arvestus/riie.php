<!doctype html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Riide osakond</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>

<!-- Add a button in the top-left corner -->
<button onclick="location.href='tellimus.php'" style="position: absolute; top: 10px; left: 10px;">Tagasi</button>

<?php
// Your existing PHP code
require('config.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission and update database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderNumber'])) {
    $orderNumber = $_POST['orderNumber'];
    $onloigatud = $_POST['onloigatud'];
    $onvarvitud = $_POST['onvarvitud'];
    $onkomplekteeritud = $_POST['onkomplekteeritud'];

    // Update the database with the new values
    $updateSql = "UPDATE rulood SET onloigatud = '$onloigatud', onvarvitud = '$onvarvitud', onkomplekteeritud = '$onkomplekteeritud' WHERE orderNumber = '$orderNumber'";

    if ($conn->query($updateSql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Handle order deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteOrder'])) {
    $orderNumber = $_POST['deleteOrder'];

    // Delete the order from the database
    $deleteSql = "DELETE FROM rulood WHERE orderNumber = '$orderNumber'";

    if ($conn->query($deleteSql) === TRUE) {
        echo "Order deleted successfully";
    } else {
        echo "Error deleting order: " . $conn->error;
    }
}

// Fetch data from the database
$sql = "SELECT * FROM rulood ORDER BY rulooID DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display order details and form to update and delete values in the database
        echo '<div class="order-container">';
        echo "Tellimuse number: " . $row['orderNumber'] . "<br>";
        echo "Nimi: " . $row['nimi'] . "<br>";
        echo "E-mail: " . $row['email'] . "<br>";
        echo "Mustri number: " . $row['mustrinr'] . "<br>";

        echo '<form action="" method="post">';
        echo '<input type="hidden" name="orderNumber" value="' . $row['orderNumber'] . '">';

        echo '<label for="onloigatud">Kas on lõigatud:</label>';
        echo '<select name="onloigatud">';
        echo '<option value="Jah" ' . ($row['onloigatud'] == 'Jah' ? 'selected' : '') . '>Jah</option>';
        echo '<option value="Ei" ' . ($row['onloigatud'] == 'Ei' ? 'selected' : '') . '>Ei</option>';
        echo '</select><br>';

        echo '<label for="onvarvitud">Kas on värvitud:</label>';
        echo '<select name="onvarvitud">';
        echo '<option value="Jah" ' . ($row['onvarvitud'] == 'Jah' ? 'selected' : '') . '>Jah</option>';
        echo '<option value="Ei" ' . ($row['onvarvitud'] == 'Ei' ? 'selected' : '') . '>Ei</option>';
        echo '</select><br>';

        echo '<label for="onkomplekteeritud">Kas on komplekteeritud:</label>';
        echo '<select name="onkomplekteeritud">';
        echo '<option value="Jah" ' . ($row['onkomplekteeritud'] == 'Jah' ? 'selected' : '') . '>Jah</option>';
        echo '<option value="Ei" ' . ($row['onkomplekteeritud'] == 'Ei' ? 'selected' : '') . '>Ei</option>';
        echo '</select><br>';

        echo '<input type="submit" value="Salvesta tellimuse olek">';

        // Form to delete the order
        echo '<button type="submit" formaction="" name="deleteOrder" value="' . $row['orderNumber'] . '">Kustuta tellimus</button>';
        
        echo '</form>';
        echo '</div>'; // Closing the styled div
    }
} else {
    echo "0 results";
}

$conn->close();
?>

</body>
</html>
