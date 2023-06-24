<?php
  // periksa apakah user sudah login, cek kehadiran session name 
  // jika tidak ada, redirect ke login.php
  session_start();
  if (!isset($_SESSION["nama"])) {
     header("Location: login.php");
  }

  // buka koneksi dengan MySQL
     include("connection.php");
  
  // ambil pesan jika ada  
  if (isset($_GET["pesan"])) {
      $pesan = $_GET["pesan"];
  }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Gibran Express</title>
  <style>
    .pesan {
      background-color: #C0FFA9;
      padding: 10px 15px;
      margin: 0 0 20px 0;
      border: 1px solid green;
      box-shadow: 1px 0px 3px green;
      text-align: center;
    }
    div.container {
      width: 960px;
      padding: 10px 50px 20px;
      background-color: white;
      margin: 20px auto;
      box-shadow: 1px 0px 10px, -1px 0px 10px ;
    }
    #header {
      height: 60px;
    }
    h1{
      text-align: right;
      font-family: "Helvetica";
      font-weight: 250px;
    }
    #logo {
      font-size: 42px;
      float: right;
      text-shadow: 1px 2px #C0C0C0;
      margin-top: 10px; 
      }
    nav {
      width: 500px;
      float: left;
      clear: both;
    }
    ul{
      padding: 0;
      margin: 0px 0;
      list-style: none;
      overflow: hidden;
    }
    nav li a {
      float: left;
      background-color: #E3E3E3;
      color: black;
      text-decoration: none;
      font-size: 12px;
      height: 30px;
      line-height: 30px;
      padding: 5px 20px;
      font-family: "Helvetica";
    }
    nav li a:hover {
      background-color: #1aa815;
      color: white;
    }
    table {
      border-collapse:collapse;
      border-spacing:0;
      border:1px black solid;
      width:100%
    }
    th, td {
      padding:8px 15px;
      border:1px black solid;
    }
    tr:nth-child(2n+3) {
      background-color: #F2F2F2;
    }
    #footer {
      text-align: right;
      margin-top: 20px;
    }
    #header {
      height: 50px;
    }
    span {
      color: #1aa815;
    }
  </style>
</head>
<body>
<div class="container">
<div id="header">
  <nav>
  <ul>
    <li><a href="data_paket.php">Data Paket</a></li>
    <li><a href="tambah_paket.php">Tambah Paket</a>
    <li><a href="edit_paket.php">Edit Paket</a>
    <li><a href="hapus_paket.php">Hapus Paket</a></li>
    <li><a class="nav-link" href="logout.php" onclick="return confirm('Yakin keluar?')"><i class="bi bi-box-arrow-right"></i> Keluar</a>
  </ul>
  </nav>
  <h1>GIBRAN <span>EXPRESS</span></h1>
</div>
  <h2 align="center">Data Paket</h2>
  <?php
  // tampilkan pesan jika ada
  if (isset($pesan)) {
      echo "<div class=\"pesan\">$pesan</div>";
  }
  ?>
 <table border="1">
  <tr>
  <th>No. Resi</th>
  <th>Nama Pengirim</th>
  <th>Nama Penerima</th>
  <th>No. HP Penerima</th>
  <th>Alamat Penerima</th>
  <th>Tanggal Masuk</th>
  <th>Tanggal Keluar</th>
  </tr>
  <?php
  // jalankan query
  $query = "SELECT * FROM paket ORDER BY no_resi ASC";
  $result = mysqli_query($link, $query);
  
  if(!$result){
      die ("Query Error: ".mysqli_errno($link).
           " - ".mysqli_error($link));
  }

  while($data = mysqli_fetch_assoc($result))
  { 
    // konversi date MySQL (yyyy-mm-dd) menjadi dd-mm-yyyy
    $tanggal_masuk = strtotime($data["tanggal_masuk"]);
    $tm = date("d - m - Y", $tanggal_masuk);
    $tanggal_keluar = strtotime($data["tanggal_keluar"]);
    $tk = date("d - m - Y", $tanggal_keluar);
    
    echo "<tr>";
    echo "<td>$data[no_resi]</td>";
    echo "<td>$data[nama_pengirim]</td>";
    echo "<td>$data[nama_penerima]</td>";
    echo "<td>$data[nohp_penerima]</td>";
    echo "<td>$data[alamat]</td>";
    echo "<td>$tm</td>";
    echo "<td>$tk</td>";
    echo "</tr>";
  }
  
  // bebaskan memory 
  mysqli_free_result($result);
  
  // tutup koneksi dengan database mysql
  mysqli_close($link);
  ?>
  </table>
</div>
</body>
</html>