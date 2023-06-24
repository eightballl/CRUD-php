-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Agu 2022 pada 14.49
-- Versi server: 10.4.21-MariaDB
-- Versi PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paket`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` varchar(10) NOT NULL,
  `nama_admin` varchar(30) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` char(40) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username`, `password`, `email`) VALUES
('adm01', 'Admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@admin.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `paket`
--

CREATE TABLE `paket` (
  `no_resi` varchar(11) NOT NULL,
  `nama_pengirim` varchar(100) DEFAULT NULL,
  `nama_penerima` varchar(30) DEFAULT NULL,
  `nohp_penerima` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `paket`
--

INSERT INTO `paket` (`no_resi`, `nama_pengirim`, `nama_penerima`, `nohp_penerima`, `alamat`, `tanggal_masuk`, `tanggal_keluar`) VALUES
('12344', 'Hanan', 'Iqbal', '09098098', 'Boyolali', '2022-08-17', '2022-08-20'),
('878978', 'Iqbal', 'Iqbal', '809809809', 'jjkhjkhjkh', '2022-08-14', '2022-08-17');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`no_resi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
