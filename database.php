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
  $conn = new PDO("mysql:host=$servername;dbname=voting", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

/*
*   Jos pyynnössä on mukava arvo nimellä 'vote', tallennetaan äänestys tietokantaan
*/
if (isset($_POST['vote'])) {
    // Haetaan äänestyksen valinta
    $vote = $_POST['vote'];
    // Haetaan käyttäjän ip-osoite
    $ip = $_SERVER['REMOTE_ADDR'];

    // Määritellään SQL-kysely
    $sql = "INSERT INTO votes (vote, ip) VALUES (?, ?)";
    // Lähetetään kysely palvelimelle
    $sttm = $conn->prepare($sql);
    // Suoritetaan kysely muuttujiin asetetuilla arvoilla
    $sttm->execute(array($vote, $ip));
}

// SQL-kysely, jolla haetaan eri vaihtoehtojen äänimäärät
$sql = "SELECT vote, count(*) as 'count' FROM votes GROUP BY vote";

// Luodaan tyhjä sanakirja
$data = array();
// Haetaan tietokannasta äänimäärät
foreach($conn->query($sql) as $row) {
    $data[$row['vote']] = $row['count'];
}

// Palautetaan JSON enkoodattu data
print (json_encode($data));

?>