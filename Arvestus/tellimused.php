<?php
require('config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orders = [];
$searchResults = '';

// võtab andmed
$sql = "SELECT * FROM rulood";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    echo "0 results";
}

// Funktsioon tellimuste kuvamiseks
function displayOrders($orderList)
{
    foreach ($orderList as $order) {
        echo '<div class="order-container">';
        echo "Tellimuse number: " . $order['orderNumber'] . "<br>";
        echo "Nimi: " . $order['nimi'] . "<br>";
        echo "E-mail: " . $order['email'] . "<br>";
        echo "Mustri number: " . $order['mustrinr'] . "<br>";
        echo "On lõigatud: " . $order['onloigatud'] . "<br>";
        echo "On värvitud: " . $order['onvarvitud'] . "<br>";
        echo "On komplekteeritud: " . $order['onkomplekteeritud'] . "<br>";
        echo "</div>";
        echo "<hr>";
    }
}

// nime järgi tellimuse olek
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nimi']) && !empty($_POST['nimi'])) {
    $nimi = $_POST['nimi'];

    // filter nimi
    $filteredOrders = array_filter($orders, function ($order) use ($nimi) {
        return strtolower($order['nimi']) == strtolower($nimi);
    });

    if (!empty($filteredOrders)) {
        $searchResults .= '<div class="order-container">';
        foreach ($filteredOrders as $order) {
            $searchResults .= "Tellimuse number: " . $order['orderNumber'] . "<br>";
            $searchResults .= "Nimi: " . $order['nimi'] . "<br>";
            $searchResults .= "E-mail: " . $order['email'] . "<br>";
            $searchResults .= "Mustri number: " . $order['mustrinr'] . "<br>";
            $searchResults .= "On lõigatud: " . $order['onloigatud'] . "<br>";
            $searchResults .= "On värvitud: " . $order['onvarvitud'] . "<br>";
            $searchResults .= "On komplekteeritud: " . $order['onkomplekteeritud'] . "<br>";
            $searchResults .= "<hr>";
        }
        $searchResults .= '</div>';
    } else {
        $searchResults = "Tellimust ei leitud.";
    }
}

$conn->close();
?>

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

<div class="order-container" style="float: left; margin-right: 20px;">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nimi">Sisesta oma nimi:</label>
        <input type="text" name="nimi">
        <button type="submit" name="submit">Jälgi tellimust</button>
    </form>

    <?php echo $searchResults; ?>
</div>

<?php
// Kõikide tellimuste kuvamine
displayOrders($orders);

echo '<button onclick="location.href=\'tellimus.php\'" style="position: absolute; top: 10px; left: 10px;">Tagasi</button>';
?>

</body>
</html>
