<?php
  // periksa apakah user sudah login, cek kehadiran session name
  // jika tidak ada, redirect ke login.php
  session_start();
  if (!isset($_SESSION["nama"])) {
     header("Location: login.php");
  }

  // buka koneksi dengan MySQL
  include("connection.php");

  // cek apakah form telah di submit
  if (isset($_POST["submit"])) {

    if ($_POST["submit"]=="Edit") {

      $no_resi = htmlentities(strip_tags(trim($_POST["no_resi"])));
      // filter data
      $no_resi = mysqli_real_escape_string($link,$no_resi);

      // ambil semua data dari database untuk menjadi nilai awal form
      $query = "SELECT * FROM paket WHERE no_resi='$no_resi'";
      $result = mysqli_query($link, $query);

      if(!$result){
        die ("Query Error: ".mysqli_errno($link).
             " - ".mysqli_error($link));
      }

      // tidak perlu pakai perulangan while, karena hanya ada 1 record
      $data = mysqli_fetch_assoc($result);

      $nama_pengirim      = $data["nama_pengirim"];
      $nama_penerima     = $data["nama_penerima"];
      $nohp_penerima            = $data["nohp_penerima"];
      $alamat     = $data["alamat"];
      $tanggal_masuk  = $data["tanggal_masuk"];
      $tanggal_keluar            = $data["tanggal_keluar"];

    // bebaskan memory
    mysqli_free_result($result);
    }

    else if ($_POST["submit"]=="Edit Paket") {
      // nilai form berasal dari halaman form_edit.php
      // ambil semua nilai form
      $no_resi      = htmlentities(strip_tags(trim($_POST["no_resi"])));
      $nama_pengirim    = htmlentities(strip_tags(trim($_POST["nama_pengirim"])));
      $nama_penerima   = htmlentities(strip_tags(trim($_POST["nama_penerima"])));
      $nohp_penerima          = htmlentities(strip_tags(trim($_POST["nohp_penerima"])));
      $alamat   = htmlentities(strip_tags(trim($_POST["alamat"])));
      $tanggal_masuk= htmlentities(strip_tags(trim($_POST["tanggal_masuk"])));
      $tanggal_keluar          = htmlentities(strip_tags(trim($_POST["tanggal_keluar"])));
    }

    // proses validasi form
    // siapkan variabel untuk menampung pesan error
    $pesan_error="";

    if (empty($no_resi)) {
      $pesan_error .= "ID Rental harap diisi <br>";
    }
    if (empty($nama_pengirim)) {
      $pesan_error .= "Merk Kamera harap diisi <br>";
    }
    if (empty($nama_penerima)) {
      $pesan_error .= "Nama harap diisi <br>";
    }
    if (empty($nohp_penerima)) {
      $pesan_error .= "No HP harap diisi <br>";
    }
    if (empty($alamat)) {
      $pesan_error .= "Tanggal Sewa harap diisi <br>";
    }
    if (empty($tanggal_masuk)) {
      $pesan_error .= "Tanggal Kembali harap diisi <br>";
    }
    if (empty($tanggal_keluar)) {
      $pesan_error .= "Email harap diisi <br>";
    }

    // jika tidak ada error, input ke database
    if (($pesan_error === "") AND ($_POST["submit"]=="Edit Paket")) {

      // buka koneksi dengan MySQL
      include("connection.php");

      // filter semua data
      $no_resi        = mysqli_real_escape_string($link,$no_resi);
      $nama_pengirim      = mysqli_real_escape_string($link,$nama_pengirim);
      $nama_penerima     = mysqli_real_escape_string($link,$nama_penerima);
      $nohp_penerima            = mysqli_real_escape_string($link,$nohp_penerima);
      $alamat     = mysqli_real_escape_string($link,$alamat);
      $tanggal_masuk  = mysqli_real_escape_string($link,$tanggal_masuk);
      $tanggal_keluar            = mysqli_real_escape_string($link,$tanggal_keluar);

      //buat dan jalankan query UPDATE
      $query  = "UPDATE paket SET ";
      $query .= "nama_pengirim = '$nama_pengirim', nama_penerima = '$nama_penerima', ";
      $query .= "nohp_penerima = '$nohp_penerima', alamat='$alamat', ";
      $query .= "tanggal_masuk = '$tanggal_masuk', tanggal_keluar='$tanggal_keluar' ";
      $query .= "WHERE no_resi = '$no_resi'";

      $result = mysqli_query($link, $query);

      //periksa hasil query
      if($result) {
        $pesan = "ID Rental = \"<b>$no_resi</b>\" sudah berhasil di update";
        $pesan = urlencode($pesan);
        header("Location: data_paket.php?pesan={$pesan}");
      }
      else {
      die ("Query gagal dijalankan: ".mysqli_errno($link).
           " - ".mysqli_error($link));
      }
    }
  }
  else {
    // form diakses secara langsung!
    header("Location: edit_paket.php");
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
  <h2 align="center">Edit Data Paket</h2>
<?php
  // tampilkan error jika ada
  if ($pesan_error !== "") {
      echo "<div class=\"error\">$pesan_error</div>";
  }
?>
<form id="edit" action="form_edit.php" method="post">
<fieldset>
  <table>
    <tr>
      <td><label for="no_resi">No. Resi </label></td>
      <td>:&emsp;<input type="text" name="no_resi" id="no_resi" value="<?php echo $no_resi ?>" readonly></td>
    </tr>
    <tr>
      <td><label for="nama_pengirim">Nama Pengirim </label></td>
      <td>:&emsp;<input type="text" name="nama_pengirim" id="nama_pengirim" value="<?php echo $nama_pengirim ?>"></td>
    </tr>
    <tr>
      <td><label for="nama_penerima">Nama Penerima</label></td>
      <td>:&emsp;<input type="text" name="nama_penerima" id="nama_penerima" value="<?php echo $nama_penerima ?>"></td>
    </tr>
    <tr>
      <td><label for="nohp_penerima">No. HP </label></td>
      <td>:&emsp;<input type="text" name="nohp_penerima" id="nohp_penerima" value="<?php echo $nohp_penerima ?>"></td>
    </tr>
    <tr>
      <td><label for="alamat">Alamat </label></td>
      <td>:&emsp;<textarea rows="4" cols="20" name="alamat" id="alamat"><?php echo $alamat ?></textarea></td>
    </tr>
    <tr>
      <td><label for="tanggal_masuk">Tanggal Masuk </label></td>
      <td>:&emsp;<input type="date" name="tanggal_masuk" id="tanggal_masuk" value="<?php echo $tanggal_masuk ?>"></td>
    </tr>
    <tr>
      <td><label for="tanggal_keluar">Tanggal Keluar </label></td>
      <td>:&emsp;<input type="date" name="tanggal_keluar" id="tanggal_keluar" value="<?php echo $tanggal_keluar ?>"></td>
    </tr>
  </table>

</fieldset>
  <br>
  <p>
    <input type="submit" name="submit" value="Edit Paket">
  </p>
</form>

</div>

</body>
</html>
<?php
  // tutup koneksi dengan database mysql
  mysqli_close($link);
?>
