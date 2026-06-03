<?php

date_default_timezone_set("Asia/Jakarta");

/* =========================
   KONFIGURASI
========================= */

$token = "fYsLU9AyVYV93G3ZqsJ5nZYKOQ-hQkIa";
$server = "https://blynk.cloud";

$conn = mysqli_connect("localhost","root","","smarthome");

/* =========================
    KONTROL BUTTON
========================= */

if(isset($_GET['pin'])){

    $pin = $_GET['pin'];
    $value = $_GET['value'];

    file_get_contents(
    "$server/external/api/update?token=$token&$pin=$value"
    );
}

/* =========================
    SIMPAN JADWAL
========================= */

if(isset($_POST['simpan'])){

    $pin = $_POST['pin'];
    $waktu = $_POST['waktu'];
    $status = $_POST['status'];

    mysqli_query($conn,
    "INSERT INTO jadwal(pin,waktu,status)
    VALUES('$pin','$waktu','$status')");
}

/* =========================
    AUTO JALANKAN JADWAL
========================= */

$jam = date("H:i");

$data = mysqli_query($conn,
"SELECT * FROM jadwal WHERE waktu='$jam'");

while($d = mysqli_fetch_array($data)){

    $pin = $d['pin'];
    $status = $d['status'];

    file_get_contents(
    "$server/external/api/update?token=$token&$pin=$status"
    );
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Smart Home</title>

    <style>

        body{
            font-family: Arial;
            background:#0f172a;
            color:white;
            padding:20px;
        }

        .card{
            background:#1e293b;
            padding:20px;
            border-radius:15px;
            margin-bottom:20px;
        }

        button{
            padding:15px;
            border:none;
            border-radius:10px;
            cursor:pointer;
            margin:5px;
        }

        .on{
            background:green;
            color:white;
        }

        .off{
            background:red;
            color:white;
        }

        input,select{
            padding:10px;
            width:100%;
            margin-top:10px;
            border-radius:10px;
        }

    </style>
</head>
<body>

<h1>SMART HOME BLYNK</h1>

<div class="card">

<h2>Kontrol Lampu</h2>

<a href="?pin=V0&value=1">
<button class="on">Lampu 1 ON</button>
</a>

<a href="?pin=V0&value=0">
<button class="off">Lampu 1 OFF</button>
</a>

<br>

<a href="?pin=V1&value=1">
<button class="on">Lampu 2 ON</button>
</a>

<a href="?pin=V1&value=0">
<button class="off">Lampu 2 OFF</button>
</a>

</div>

<div class="card">

<h2>Tambah Jadwal</h2>

<form method="POST">

<select name="pin">
    <option value="V0">Lampu 1</option>
    <option value="V1">Lampu 2</option>
</select>

<input type="time" name="waktu" required>

<select name="status">
    <option value="1">ON</option>
    <option value="0">OFF</option>
</select>

<br><br>

<button name="simpan" class="on">
Simpan Jadwal
</button>

</form>

</div>

<div class="card">

<h2>Daftar Jadwal</h2>

<?php

$tampil = mysqli_query($conn,"SELECT * FROM jadwal");

while($j = mysqli_fetch_array($tampil)){

    echo "
    <p>
    {$j['pin']} -
    {$j['waktu']} -
    ".($j['status']==1 ? 'ON':'OFF')."
    </p>
    ";
}

?>

</div>

</body>
</html>