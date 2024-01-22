<?php
require('config.php');

class Tellimus {
    public $orderNumber;
    public $nimi;
    public $email;
    public $mustrinr;
    public $onloigatud;
    public $onvarvitud;
    public $onkomplekteeritud;

    public function __construct($orderNumber, $nimi, $email, $mustrinr) {
        $this->orderNumber = $orderNumber;
        $this->nimi = $nimi;
        $this->email = $email;
        $this->mustrinr = $mustrinr;
        $this->onloigatud = ""; // Algselt tühi väärtus
        $this->onvarvitud = ""; // Algselt tühi väärtus
        $this->onkomplekteeritud = ""; // Algselt tühi väärtus
    }
}

function generateOrderNumber() {
    $prefix = 'ORD' . date('YmdHis');
    $randomPart = rand(1000, 9999);

    // Calculate the remaining available characters for the random part
    $remainingChars = 200 - strlen($prefix);

    // Ensure the random part does not exceed the remaining characters
    $randomPart = substr($randomPart, 0, $remainingChars);

    return $prefix . $randomPart;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nimi']) && isset($_POST['email']) && isset($_POST['mustrinr'])) {
        $nimi = $_POST['nimi'];
        $email = $_POST['email'];
        $mustrinr = $_POST['mustrinr'];

        $orderNumber = generateOrderNumber();
        $tellimus = new Tellimus($orderNumber, $nimi, $email, $mustrinr);

        // Save order to the database
        $sql = "INSERT INTO rulood (orderNumber, nimi, email, mustrinr, onloigatud, onvarvitud, onkomplekteeritud)
        VALUES ('$tellimus->orderNumber', '$tellimus->nimi', '$tellimus->email', '$tellimus->mustrinr', '$tellimus->onloigatud', '$tellimus->onvarvitud', '$tellimus->onkomplekteeritud')";

        if ($conn->query($sql) === TRUE) {
            header("Location: riie.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Palun täitke kõik väljad!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tellimus</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>Aknaruloode tootmine</header>
    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <dl>
                <dt>Nimi:</dt>
                <input type="text" name="nimi"><br>

                <dt>E-mail:</dt>
                <input type="text" name="email"><br>

                <dt>Mustri number:</dt>
                <select name="mustrinr" id="mustrinr">
                    <option value="">--- Vali mustri number ---</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>

                <div>
                    <button type="submit" name="submit">Esita tellimus</button>
                </div>

                <button type="submit" formaction="riie.php">Vaata kõiki tellimusi</button>
                <button type="submit" formaction="tellimused.php">Jälgi tellimust</button>
            </dl>
        </form>
    </div>
</body>

</html>
