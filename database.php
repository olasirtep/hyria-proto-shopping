<?php

// Määritellään palautettavan vastauksen tyypiksi JSON
header("Content-Type: application/json");

/*
*   XAMPP oletustunnukset
*/
// Tietokantapalvelimen osoite
$servername = "localhost";
// Käytettävä käyttäjätunnus
$username = "root";
// Salasana
$password = "";

/*
*   Tässä yhdistetään tietokantaan
*/
try {
  $conn = new PDO("mysql:host=$servername;dbname=test", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

/*
*   Jos pyynnössä on mukava arvo nimellä 'vote', tallennetaan äänestys tietokantaan
*/
if (isset($_POST['tuote'])) {
    // Haetaan äänestyksen valinta
    $tuote = $_POST['tuote'];
    // Haetaan käyttäjän ip-osoite
    $ip = $_SERVER['REMOTE_ADDR'];

    // Määritellään SQL-kysely
    $sql = "INSERT INTO kauppalista (tuote, ip) VALUES (?, ?)";
    // Lähetetään kysely palvelimelle
    $sttm = $conn->prepare($sql);
    // Suoritetaan kysely muuttujiin asetetuilla arvoilla
    $sttm->execute(array($tuote, $ip));
}

// SQL-kysely, jolla haetaan eri vaihtoehtojen äänimäärät
$sql = "SELECT tuote FROM kauppalista";

// Luodaan tyhjä sanakirja
$data = array();
// Haetaan tietokannasta äänimäärät
foreach($conn->query($sql) as $row) {
    array_push($data, $row['tuote']);
}

// Palautetaan JSON enkoodattu data
print (json_encode($data));

?>