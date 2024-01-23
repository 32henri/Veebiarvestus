<?php
require('config.php');
// defineerib tellimuse klassi
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
// genereerib suvalise numbri ja kontrollib et ei oleks liiga pikk
function generateOrderNumber() {
    $prefix = 'ORD' . date('YmdHis');
    $randomPart = rand(1000, 9999);

    
    $remainingChars = 200 - strlen($prefix);

    
    $randomPart = substr($randomPart, 0, $remainingChars);

    return $prefix . $randomPart;
}
// If päring
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // kontrollib kas väljad on määratud
    if (isset($_POST['nimi']) && isset($_POST['email']) && isset($_POST['mustrinr'])) {
        $nimi = $_POST['nimi'];
        $email = $_POST['email'];
        $mustrinr = $_POST['mustrinr'];
        
        //numbri genereerimine
        $orderNumber = generateOrderNumber();
        // tellimuse objekt
        $tellimus = new Tellimus($orderNumber, $nimi, $email, $mustrinr);
        
        // väärtuseed
        $onloigatud_default = 'Ei';
        $onvarvitud_default = 'Ei';
        $onkomplekteeritud_default = 'Ei';

        // tellimus andmebaasi
        $sql = "INSERT INTO rulood (orderNumber, nimi, email, mustrinr, onloigatud, onvarvitud, onkomplekteeritud)
        VALUES ('$tellimus->orderNumber', '$tellimus->nimi', '$tellimus->email', '$tellimus->mustrinr', '$onloigatud_default', '$onvarvitud_default', '$onkomplekteeritud_default')";
        // kontrollib kas päring õnnestub
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
