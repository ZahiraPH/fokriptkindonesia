-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 12, 2025 at 03:05 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fokri_ptk`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `penulis` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `deskripsi`, `gambar`, `penulis`, `created_at`) VALUES
(1, 'Buka Bersama & Bagi Takjil FOKRI PTK INDONESIA', 'Pada Sabtu, 15 Maret 2025, FOKRI-PTK menggelar kegiatan Buka Bersama dan Bagi Takjil yang berlangsung di Kedai Kayu Manis. Acara ini dihadiri oleh pengurus Senat dan Majelis Syuro FOKRI-PTK serta beberapa delegasi dari berbagai PTK lainnya. Kegiatan dimulai menjelang waktu berbuka dengan pembagian takjil kepada masyarakat umum di sekitar lokasi acara, sebagai bentuk kepedulian dan semangat berbagi di bulan suci Ramadan. Takjil dibagikan langsung oleh para peserta, disambut hangat oleh warga yang melintas. Suasana hangat dan penuh kebersamaan begitu terasa, mempererat hubungan antara sesama anggota FOKRI dan masyarakat sekitar.\r\n\r\nSetelah pembagian takjil, kegiatan dilanjutkan dengan tausiyah singkat yang membahas pentingnya ukhuwah dan kebermanfaatan di bulan Ramadan. Memasuki waktu maghrib, para peserta bersama-sama menikmati hidangan buka puasa dalam suasana yang hangat dan bersahaja. Tidak hanya sebagai ajang untuk berbagi, kegiatan ini juga menjadi sarana memperkuat tali persaudaraan antaranggota dan memperkokoh semangat kolektif dalam menjalankan nilai-nilai keislaman. Melalui kegiatan ini, FOKRI-PTK berharap dapat terus menghadirkan kontribusi positif dan menebarkan semangat kebersamaan di tengah masyarakat.', 'berita_1751769576.jpg', 'SN18', '2025-06-24 03:05:59'),
(2, 'RAKERNAS FOKRI PTK 2025: Bersama Ukhuwah, Bersatu dalam Dakwah', 'Rapat Kerja Nasional (RAKERNAS) Forum Kerja Sama Rohani Islam Perguruan Tinggi Kedinasan (FOKRI PTK) tahun 2025 sukses diselenggarakan pada hari Sabtu, 26 April 2025, bertempat di Gedung DPP Persatuan Ummat Islam (PUI), Pancoran, Jakarta Selatan. Dengan mengusung tema “Bersama Ukhuwah, Bersatu dalam Dakwah”, kegiatan ini menjadi momen penting dalam menyatukan arah dan langkah dakwah kampus kedinasan di seluruh Indonesia.\r\nAcara ini dihadiri oleh para pengurus FOKRI PTK periode 2024/2025 serta perwakilan Rohani Islam (Rohis) dan Lembaga Dakwah Kampus (LDK) dari berbagai perguruan tinggi kedinasan, baik secara luring maupun daring. Rangkaian acara dimulai sejak pukul 09.00 WIB dengan registrasi peserta, dilanjutkan pembukaan, pembacaan program kerja, sidang AD/ART, hingga forum diskusi strategis.\r\nRAKERNAS ini menjadi sarana konsolidasi nasional bagi para aktivis dakwah kampus kedinasan untuk mengevaluasi capaian program sebelumnya, merumuskan strategi ke depan, dan memperkuat sinergi lintas kampus. Suasana penuh semangat dan ukhuwah mengiringi jalannya forum, meskipun terdapat beberapa kendala teknis seperti keterlambatan waktu.\r\nMeski demikian, seluruh sesi dapat terlaksana dengan baik berkat kerja sama panitia dan partisipasi aktif peserta. Melalui kegiatan ini, FOKRI PTK semakin mantap dalam visinya membentuk kampus kedinasan yang cerah dan islami, serta menghadirkan peran strategis dalam pembinaan karakter mahasiswa secara spiritual dan sosial.', 'berita_1751769622.jpg', 'SN18', '2025-06-24 04:09:33'),
(3, 'KADERNAS Part 1: Time Management dan Pengelolaan Diri untuk Maksimalkan Potensi', 'Sabtu, 24 Mei 2025 menjadi awal rangkaian kegiatan KADERNAS (Kaderisasi Nasional) FOKRI PTK 2025, yang dibuka dengan Part 1: Time Management dan Pengelolaan Diri untuk Mencapai Potensi. Kegiatan ini diselenggarakan sebagai bentuk pembinaan kapasitas diri bagi para kader dakwah kampus kedinasan dari berbagai institusi di seluruh Indonesia.\r\nDalam sesi ini, peserta diajak untuk merefleksikan cara mereka mengelola waktu dan diri di tengah kesibukan akademik dan aktivitas organisasi. Materi difokuskan pada pentingnya mengenali prioritas, menjaga konsistensi dalam amal harian, serta membangun kebiasaan yang menunjang produktivitas dan keberkahan waktu.\r\nDisampaikan dengan pendekatan yang interaktif dan aplikatif, para peserta tidak hanya mendapatkan wawasan teoretis, tetapi juga diajak untuk membuat rencana nyata dalam kehidupan sehari-hari mereka. Kegiatan ini diharapkan menjadi bekal awal bagi para kader untuk lebih siap menjalankan amanah dakwah secara seimbang antara spiritualitas, akademik, dan kontribusi sosial.\r\nKADERNAS Part 1 ditutup dengan semangat dan tekad baru dari para peserta untuk menjadi pribadi yang teratur, produktif, dan berdaya guna tinggi—demi meraih potensi maksimal dalam peran mereka sebagai agen dakwah di kampus masing-masing.', 'berita_1751769676.jpg', 'SN1', '2025-06-24 04:09:58'),
(4, 'Pelantikan Pengurus Pengganti dan KADERNAS Part 2: Penguatan Kader Dakwah FOKRI PTK Berbasis Virtual', 'Sabtu, 28 Juni 2025, Forum Kerja Sama Rohani Islam Perguruan Tinggi Kedinasan (FOKRI PTK) sukses menyelenggarakan kegiatan Pelantikan Pengurus Pengganti dan Kaderisasi Nasional (KADERNAS) Part 2 secara daring melalui platform Zoom Meeting. Kegiatan ini merupakan lanjutan dari rangkaian pembinaan nasional yang ditujukan untuk memperkuat soliditas kepengurusan serta kapasitas kader dakwah kampus kedinasan di seluruh Indonesia.\r\nAcara ini dihadiri oleh para pengurus Senat dan Majelis Syuro FOKRI PTK Periode 2024/2025, serta delegasi dari berbagai Perguruan Tinggi Kedinasan yang tergabung dalam forum. Mengawali kegiatan, dilaksanakan pelantikan pengurus pengganti sebagai bentuk regenerasi struktural yang responsif terhadap dinamika kepengurusan. Proses pelantikan berlangsung khidmat dan disaksikan langsung oleh jajaran pimpinan FOKRI.\r\nDilanjutkan dengan KADERNAS Part 2, peserta mendapatkan pembekalan terkait visi keorganisasian, semangat perjuangan dakwah, serta penanaman nilai-nilai kepemimpinan Islami. Dengan mengusung semangat \"berkader untuk membina, membina untuk berperan\", sesi ini menjadi bekal penting bagi para kader dalam menghadapi tantangan dakwah di lingkungan kampus yang dinamis.\r\nMeskipun dilakukan secara daring, kegiatan ini tetap berlangsung dengan penuh antusias dan interaktif, ditunjukkan dari partisipasi aktif peserta dalam sesi diskusi dan refleksi. Suasana kekeluargaan dan semangat ukhuwah tetap terasa meski terbatas secara fisik.\r\nKegiatan ini diharapkan mampu memperkuat struktur internal FOKRI PTK, membentuk kader-kader tangguh, dan menciptakan kesinambungan gerak dakwah kampus yang terarah dan berdampak. Semoga kegiatan ini menjadi awal dari kontribusi yang lebih luas bagi umat dan bangsa.', 'berita_1751769717.jpg', 'SN1', '2025-06-24 04:10:22'),
(8, 'Selamat Bergabung, Pengurus Baru FOKRI PTKI 2024/2025!', 'Pada awal tahun 2025, Forum Kerja Sama Rohani Islam Perguruan Tinggi Kedinasan (FOKRI PTKI) resmi mengumumkan terbentuknya kepengurusan baru Majelis Syuro dan Senat periode 2024/2025. Ketua Senat, Erlangga Danny, menyambut para pengurus baru dengan ajakan kolaboratif dan semangat kekeluargaan.\r\nDalam sambutannya, ia menekankan bahwa seluruh pengurus diharapkan aktif menyumbangkan gagasan dan kontribusi nyata demi kemajuan dakwah kampus kedinasan, serta menegaskan tidak adanya senioritas di internal organisasi.', 'berita_1751900934.png', 'SN15', '2025-07-07 15:08:54'),
(9, 'Rapat Perdana FOKRI-PTKI: Awal Sinergi Pengurus Baru', 'Rapat perdana pengurus Majelis Syuro dan Senat FOKRI-PTKI periode 2024/2025 digelar secara daring pada Sabtu, 1 Februari 2025.\r\nKegiatan ini menjadi momentum penting untuk membangun kesepahaman dan arah gerak dakwah kampus selama satu periode ke depan.\r\nAgenda meliputi perkenalan antar pengurus, pemaparan tugas bidang, evaluasi program sebelumnya, hingga diskusi rancangan program kerja baru.\r\nAntusiasme peserta menggambarkan semangat baru dalam menjalankan amanah dakwah.', 'berita_1751900972.png', 'SN15', '2025-07-07 15:09:32'),
(10, 'Koordinasi Pelantikan Pengurus FOKRI Digelar Jelang Acara Puncak', 'Sebagai persiapan menuju pelantikan resmi, FOKRI-PTKI mengadakan rapat koordinasi bersama tuan rumah kegiatan, Politeknik Pembangunan Pertanian Medan.\r\nRapat digelar secara daring pada Sabtu, 15 Februari 2025 dan dihadiri oleh jajaran Majelis Syuro dan Senat.\r\nFokus pembahasan meliputi teknis acara, rundown, penyambutan tamu, serta penguatan kolaborasi antar bidang.\r\nPelantikan pengurus baru diharapkan berjalan lancar dan menjadi tonggak awal gerakan dakwah nasional di kalangan mahasiswa PTK.', 'berita_1751901002.png', 'SN15', '2025-07-07 15:10:02'),
(11, 'RAKERNAS FOKRI 2025 Akan Digelar, Seluruh Bidang Siapkan Proker', 'Forum Rapat Kerja Nasional (RAKERNAS) FOKRI PTKI tahun 2025 dijadwalkan berlangsung pada 26 April 2025 di Gedung DPP PUI, Jakarta Selatan.\r\nNotulensi rapat persiapan pada 18 April 2025 menyebutkan bahwa setiap kepala bidang diminta menyiapkan program kerja satu tahun ke depan.\r\nTema yang diangkat adalah “Bersama Ukhuwah, Bersatu dalam Dakwah”.\r\nBeberapa peran kunci seperti MC, publikasi, penyambutan tamu, hingga konsumsi telah ditentukan.\r\nGedung PUI tidak memungut biaya sewa, namun dana kebersihan akan diberikan sebagai bentuk apresiasi FOKRI.', 'berita_1751901032.png', 'SN15', '2025-07-07 15:10:32'),
(12, 'FOKRI dan FMKD Jakarta Raya Bahas Kolaborasi Dakwah dan Edukasi', 'Pada Sabtu, 7 Juni 2025, FOKRI PTKI dan FMKD Jakarta Raya mengadakan pertemuan kerja sama di Kopi Wangi, Jakarta Timur.\r\nAgenda rapat mencakup rencana visitasi ke tokoh publik, rencana lomba edukatif dan islami di Rembang, podcast dakwah, hingga webinar bersama.\r\nKerja sama ini diharapkan memperluas jangkauan dakwah ke kalangan siswa SMA/SMK hingga mahasiswa umum.\r\nBeberapa kegiatan kolaboratif seperti podcast muslimah, lomba esai dan debat, serta kunjungan panti asuhan juga turut dibahas.\r\nLangkah ini menjadi bentuk nyata penguatan jaringan antar organisasi rohis kedinasan.', 'berita_1751901063.png', 'SN15', '2025-07-07 15:11:03'),
(13, 'Bedah Buku FOKRI PTKI: Sinergi Ilmu dan Kebersamaan di Ruang Terbuka', 'FOKRI PTKI mengadakan kegiatan Bedah Buku pada Sabtu, 14 Juni 2025 bertempat di Tebet Eco Park, Jakarta Selatan.\r\nKegiatan yang dihadiri pengurus Majelis Syuro dan Senat ini dilaksanakan dengan suasana santai namun sarat makna.\r\nPeserta mengenakan Pakaian Dinas Olahraga (PDO) dan mengikuti sesi diskusi reflektif seputar nilai-nilai Islam dalam kehidupan sehari-hari.\r\nBedah buku ini menjadi ruang sinergi antara intelektual, spiritual, dan ukhuwah antar kader dalam nuansa alam terbuka.', 'berita_1751901130.jpg', 'SN15', '2025-07-07 15:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uploaded_by` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id`, `judul`, `deskripsi`, `file`, `uploaded_by`, `created_at`) VALUES
(1, 'LAPORAN PERTANGGUNGJAWABAN KEGIATAN  TENTANG  RAPAT KERJA NASIONAL (RAKERNAS)', 'Rapat Kerja Nasional (Rakernas) FOKRI PTK merupakan agenda tahunan \r\nyang mempertemukan perwakilan Rohis dan LDK dari seluruh perguruan tinggi \r\nkedinasan di Indonesia dalam satu forum nasional. Kegiatan ini menjadi sarana \r\nstrategis untuk memperkuat koordinasi, merumuskan arah dakwah kampus, serta \r\nmenyusun program kerja yang selaras dengan kebutuhan umat dan dinamika \r\nkampus kedinasan. \r\nMelalui Rakernas ini, diharapkan terwujud langkah-langkah konkret yang \r\nmampu mengarahkan peran FOKRI PTK secara lebih strategis dan berdampak, baik \r\ndi lingkungan kampus masing-masing maupun di lingkup nasional sebagai bagian \r\ndari gerakan dakwah kampus kedinasan..', 'dokumen_1751770119.pdf', 'SN3', '2025-06-24 03:07:43'),
(2, 'NOTULA  RAPAT KERJA SAMA FORUM MAHASISWA KEDINASAN DAERAH JAKARTA RAYA  DAN FORUM KERJA SAMA ROHANI ISLAM (FOKRI)', 'Telah disepakati bahwa FOKRI dan FMKD Jakarta Raya akan menjalin kerja sama \r\ndalam berbagai program kerja untuk mempererat hubungan antarorganisasi, memperluas \r\nmanfaat sosial, keagamaan, dan pendidikan kepada masyarakat, serta memperkuat \r\neksistensi kedua organisasi. Kerja sama ini dibangun atas dasar saling mendukung, sinergi, \r\ndan profesionalisme.', 'dokumen_1751770206.pdf', 'SN1', '2025-06-28 14:56:41'),
(3, 'Iuran Forum Kerja Sama Rohani Islam  Perguruan Tinggi Kedinasan Indonesia  (FOKRI-PTKI)', 'Berdasarkan Peraturan Ketua Senat Nomor 2 Tahun 2025 tentang Keuangan Forum Kerja \r\nSama Rohani Islam Perguruan Tinggi Kedinasan Indonesia dan Surat Keputusan Ketua Senat \r\nForum Kerja Sama Rohani Islam Perguruan Tinggi Kedinasan Indonesia Nomor \r\n016/FKR/SK/III/2025 tentang Iuran Forum Kerja Sama Rohani Islam Perguruan Tinggi Kedinasan \r\nIndonesia bahwa dalam rangka menunjang kegiatan organisasi, diperlukan sumber pendanaan yang \r\nberkelanjutan dan partisipatif dari anggota Forum Kerja Sama Rohani Islam Perguruan Tinggi \r\nKedinasan Indonesia (FOKRI-PTKI) dan pengurus senat FOKRI-PTKI Periode 2024/2025.', 'dokumen_1751770275.pdf', 'SN5', '2025-06-28 14:57:10'),
(7, 'Dummy', 'Dummy 1', 'dokumen_1751900510.pdf', 'SN15', '2025-07-07 15:01:50'),
(8, 'Dummy 2', 'Dummy 2', 'dokumen_1751900531.pdf', 'SN15', '2025-07-07 15:02:11'),
(9, 'Dummy 3', 'Dummy', 'dokumen_1751900552.pdf', 'SN15', '2025-07-07 15:02:32'),
(10, 'Dummy 4', 'Dummy', 'dokumen_1751900575.pdf', 'SN15', '2025-07-07 15:02:55'),
(11, 'Dummy 5', 'Dummy', 'dokumen_1751900592.pdf', 'SN15', '2025-07-07 15:03:12'),
(12, 'Dummy 6', 'Dummy', 'dokumen_1751900609.pdf', 'SN15', '2025-07-07 15:03:29'),
(13, 'Dummy 7', 'Dummy', 'dokumen_1751900623.pdf', 'SN15', '2025-07-07 15:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `penanggung_jawab` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bidang` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('upcoming','finished') COLLATE utf8mb4_general_ci DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `judul`, `deskripsi`, `tanggal_mulai`, `tanggal_selesai`, `lokasi`, `penanggung_jawab`, `bidang`, `status`, `created_at`) VALUES
(123456790, 'RAKERNAS', 'Rakernas (Rapat Kerja Nasional) merupakan kegiatan yang fungsinya sebagai rapat untuk mengesahkan program kerja dari ketua Majelis Syuro dan ketua Senat selama setahun kepengurusan.', '2025-04-26', '2025-04-26', 'Kantor DPP PUI', 'SN1', 'BPH', 'finished', '2025-06-24 03:07:15'),
(123456792, 'FOKRI GAMES', 'FOKRI GAMES adalah ajang tahunan yang melibatkan berbagai perguruan tinggi kedinasan di Indonesia, dengan tujuan mempererat ukhuwah Islamiyah dan mengembangkan potensi mahasiswa dalam bidang seni dan keagamaan.', '2025-11-01', '2025-11-30', 'Belum diketahui', 'SN18', 'Tarbiyah', 'upcoming', '2025-06-28 14:25:01'),
(123456793, 'KADERNAS', 'Kaderisasi Nasional (Kadernas) adalah kegiatan yang bertujuan untuk membina dan mengembangkan kapasitas internal pengurus FOKRI, sehingga mereka siap menjalankan peran kepengurusan di masa mendatang. Selain itu, kegiatan ini juga memberikan bekal yang bermanfaat bagi peserta dalam dunia kerja.', '2025-05-01', '2025-08-31', 'Tentatif', 'SN18', 'Tarbiyah', 'upcoming', '2025-06-28 14:25:32'),
(123456794, 'MUNAS', 'Munas (Musyawarah Nasional) merupakan salah satu agenda rutin tahunan FOKRI PTK dengan tujuan mempererat silaturahim dan menikmati indahnya ukhuwah islamiyah serta untuk melantik ketua majelis syuro dan ketua senat yang baru', '2025-02-22', '2025-02-22', 'Zoom', 'SN1', 'BPH', 'finished', '2025-06-28 14:37:13'),
(123456796, '30 days content for Ramadhan', 'Kegiatan ini adalah program unggulan TIK FOKRI PTK berupa konten dakwah digital harian selama 30 hari di bulan Ramadhan. Konten yang disajikan dapat berupa kutipan ayat/hadits, infografis, refleksi harian, maupun video pendek inspiratif.\r\n🎯 Tujuan: Menyemarakkan bulan Ramadhan dengan konten yang mencerahkan, ringan namun menggugah, serta memperluas jangkauan dakwah digital ke berbagai platform media sosial FOKRI PTK.', '2025-02-28', '2025-03-30', 'Online', 'SN15', 'Hubungan Masyarakat', 'finished', '2025-07-07 14:31:31'),
(123456797, 'Warta FOKRI/ Kabar FOKRI PTKI', 'Sebuah rubrik berita bulanan yang menyajikan informasi terkini seputar kegiatan internal FOKRI PTK maupun perkembangan dakwah kampus kedinasan secara nasional. Disajikan dalam bentuk buletin digital atau unggahan di Instagram & web resmi.\r\n🎯 Tujuan: Membangun transparansi kegiatan, memperkuat branding FOKRI, dan mendokumentasikan peristiwa penting sebagai arsip dakwah kampus.', '2025-03-01', '2026-02-07', 'Online', 'SN15', 'Hubungan Masyarakat', 'upcoming', '2025-07-07 14:32:32'),
(123456798, 'Tanya FOKRI PTK Indonesia', 'Program konten interaktif berbentuk tanya-jawab antara followers dengan tim FOKRI PTK. Pertanyaan bisa seputar keislaman, kehidupan mahasiswa, dakwah kampus, maupun topik personal-spiritual.\r\n🎯 Tujuan: Meningkatkan interaksi kader dengan FOKRI, serta menjadi sarana edukasi ringan dan ramah bagi para pengikut media sosial.', '2025-03-01', '2026-02-01', 'Online', 'SN15', 'Hubungan Masyarakat', 'upcoming', '2025-07-07 14:34:09'),
(123456799, 'Buka Bersama & Bagi Takjil FOKRI PTK INDONESIA', 'Kegiatan tahunan di bulan Ramadhan berupa pembagian takjil kepada masyarakat dan buka puasa bersama pengurus serta kader FOKRI. Dapat dilaksanakan secara serentak oleh perwakilan PTK masing-masing.\r\n🎯 Tujuan: Menumbuhkan semangat berbagi, meningkatkan kepedulian sosial, serta mempererat ukhuwah kader dalam nuansa Ramadhan yang hangat dan bermakna.', '2025-03-15', '2025-03-15', 'Kedai Kayu Manis', 'SN15', 'Hubungan Masyarakat', 'finished', '2025-07-07 14:35:15'),
(123456800, 'Bedah Buku FOKRI PTK Indonesia', 'Sesi diskusi terbuka yang membahas sebuah buku pilihan bertema keislaman, kepemimpinan, atau motivasi dakwah. Biasanya diikuti oleh kader dari berbagai PTK dan dipandu oleh fasilitator atau pemateri khusus.\r\n🎯 Tujuan: Meningkatkan literasi kader, memperluas wawasan dakwah, serta membentuk budaya membaca dan berdiskusi yang kritis namun tetap islami.', '2025-06-14', '2025-06-14', 'Tebet ECO Park', 'SN15', 'Hubungan Masyarakat', 'finished', '2025-07-07 14:36:21'),
(123456801, 'Kunjungan Kerja FOKRI PTK Indonesia x FMKD Jakarta Raya', 'Kegiatan ini merupakan bentuk kunjungan kelembagaan FOKRI PTK Indonesia dan FMKD Jakarta Raya ke Kantor Berita Antara untuk mempelajari pengelolaan media, komunikasi publik, dan proses pemberitaan. Selain itu, kegiatan ini juga menjadi ruang dialog terkait peluang kolaborasi dalam penyebaran konten dakwah dan informasi strategis antar lembaga.', '2025-07-25', '2025-07-25', 'Kantor Berita Antara', 'SN15', 'Hubungan Masyarakat', 'upcoming', '2025-07-07 14:39:51');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `deskripsi`, `status`, `created_at`, `created_by`) VALUES
(2, '📌 OPEN RECRUITMENT PENGURUS FOKRI PTK PERIODE 2025/2026', 'Assalamu’alaikum warahmatullahi wabarakatuh,\r\nDengan semangat regenerasi dan dakwah kampus yang terus berlanjut, FOKRI PTK membuka kesempatan bagi seluruh mahasiswa Perguruan Tinggi Kedinasan untuk menjadi bagian dari keluarga besar FOKRI PTK Periode 2025/2026.\r\n🧭 Apa yang kamu dapatkan?\r\n✅ Lingkungan penuh semangat dakwah\r\n✅ Jejaring nasional antar PTK\r\n✅ Pelatihan kepemimpinan & komunikasi\r\n✅ Pengalaman organisasi nasional berbasis nilai Islami\r\n📅 Pendaftaran dibuka: -\r\n📍 Link pendaftaran: -\r\n📞 Contact person: -\r\nMari menjadi bagian dari gerak dakwah kampus nasional. Karena dakwah butuh kamu.\r\nWassalamu’alaikum warahmatullahi wabarakatuh.', 'inactive', '2025-06-25 16:33:19', 'SN1'),
(7, '💫 RAPAT PERDANA KEPENGURUSAN FOKRI-PTKI 2024/2025 💫', 'Deskripsi:\r\n📅 Sabtu, 1 Februari 2025\r\n⏰ 20.00 WIB – selesai\r\n📍 Zoom Meeting\r\n👔 Bebas Rapi\r\nAgenda: perkenalan pengurus, pengenalan tugas, evaluasi program sebelumnya, rancangan program kerja awal.', 'inactive', '2025-07-07 14:46:44', 'SN15'),
(8, '📣 RAKOR PELANTIKAN PENGURUS FOKRI-PTKI', 'Deskripsi:\r\n📅 Sabtu, 15 Februari 2025\r\n⏰ 08.00 WIB – selesai\r\n📍 Zoom Meeting\r\n👔 Bebas Rapi\r\nRapat koordinasi bersama Politeknik Pembangunan Pertanian Medan menjelang pelantikan pengurus.', 'inactive', '2025-07-07 14:47:16', 'SN15'),
(9, '✨ PELANTIKAN PENGURUS PENGGANTI & KADERNAS PART 2 ✨', 'Deskripsi:\r\n📅 Sabtu, 28 Juni 2025\r\n⏰ 09.00 WIB – selesai\r\n📍 Zoom Meeting\r\n👔 PDH masing-masing PTK\r\nPelantikan pengurus pengganti dan lanjutan kaderisasi nasional.', 'inactive', '2025-07-07 14:47:45', 'SN15'),
(10, '📢 KADERNAS PART 1 – \"Manajemen Waktu: Amanah & Prioritas\"', 'Deskripsi:\r\n📅 Sabtu, 24 Mei 2025\r\n⏰ 20.00 WIB – selesai\r\n📍 Zoom\r\n🎙 Wafi Aulia Tsabitah\r\n📎 Isi GForm: bit.ly/FormulirAbsensiKadernas2025\r\n📷 Upload bukti screenshot di GDrive\r\n📌 WAJIB untuk seluruh pengurus', 'inactive', '2025-07-07 14:48:09', 'SN15'),
(11, '🕌 BUKA BERSAMA & BERBAGI TAKJIL FOKRI PTKI', 'Deskripsi:\r\n📅 Sabtu, 15 Maret 2025\r\n⏰ 16.00 WIB – selesai\r\n📍 Kedai Kayumanis\r\nAgenda: berbagi takjil, buka bersama, mempererat ukhuwah Ramadhan\r\n📌 Jangan lupa bawa semangat & isi ulang kehangatan!', 'inactive', '2025-07-07 14:48:34', 'SN15'),
(12, '📖 BEDAH BUKU FOKRI – Juni 2025', 'Deskripsi:\r\n📅 Sabtu, 14 Juni 2025\r\n⏰ 09.30 WIB – selesai\r\n📍 Tebet Eco Park, Jakarta Selatan\r\n👔 Pakaian Dinas Olahraga (PDO)\r\n📌 Bawa semangat belajar & kas buku bacaan!', 'inactive', '2025-07-07 14:48:58', 'SN15'),
(13, '🤝 KUNJUNGAN KERJA FOKRI x KANTOR BERITA ANTARA', '📅 25 Juli 2025\r\nKunjungan lembaga ke Kantor Berita Antara sebagai bagian dari pembelajaran komunikasi publik dan eksplorasi kerja sama media.', 'active', '2025-07-07 14:49:18', 'SN15'),
(14, '🎮 FOKRI GAMES 2025 – Seru, Solid, Sportif!', '📅 21–22 September 2025\r\n📍 Online (Zoom + games platform)\r\nLomba antar PTK: Cerdas Cermat, Tebak Kata Islami, Puzzle Dakwah, Escape Room Virtual\r\n🎁 Hadiah & sertifikat menanti!\r\n📌 Daftar: bit.ly/FOKRIGAMES2025', 'active', '2025-07-07 14:50:00', 'SN15'),
(15, '📌 PENGINGAT KAS WAJIB BULAN JULI 2025', 'Assalamu’alaikum,\r\nDiberitahukan kepada seluruh pengurus agar segera menyetorkan kas bulanan untuk bulan Juli maksimal tanggal 10 Juli 2025.\r\nRekap kas akan dibacakan saat rapat bulanan.\r\n📌 Transfer ke bendahara masing-masing divisi.', 'active', '2025-07-07 14:50:22', 'SN15');

-- --------------------------------------------------------

--
-- Table structure for table `pengurus`
--

CREATE TABLE `pengurus` (
  `id` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('Majelis Syuro','Senat') COLLATE utf8mb4_general_ci NOT NULL,
  `bidang` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asal_ptk` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sub_bidang` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengurus`
--

INSERT INTO `pengurus` (`id`, `nama`, `username`, `password`, `kategori`, `bidang`, `jabatan`, `asal_ptk`, `jenis_kelamin`, `created_at`, `sub_bidang`) VALUES
('MS1', 'Ferdian Hikmal Saputra', 'ferdianhikmal', 'ferdian@fokri2025', 'Majelis Syuro', 'BPH', 'Ketua', 'Politeknik Statistika STIS', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS10', 'Taruna Anugrah Lase', 'tarunaanugrah', 'taruna@fokri2025', 'Majelis Syuro', 'Komisi Legislasi', 'Staf', 'Sekolah Tinggi Meteorologi Klimatologi Dan Geofisika', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS11', 'Aljan Tegar Hambali', 'aljantegar', 'aljan@fokri2025', 'Majelis Syuro', 'Komisi Legislasi', 'Staf', 'Politeknik Enjiniring Pertanian Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS12', 'Faatih Ahnaf Syauqyi', 'faatihahnaf', 'faatih@fokri2025', 'Majelis Syuro', 'Komisi Legislasi', 'Staf', 'Sekolah Tinggi Meteorologi Klimatologi Dan Geofisika', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS13', 'Galih Oktaviano', 'galihoktaviano', 'galih@fokri2025', 'Majelis Syuro', 'Komisi Pengawasan', 'Pengawas BPH', 'Sekolah Tinggi Meteorologi Klimatologi Dan Geofisika', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS14', 'Muhammad Haidar Ali Ibrahim', 'muhammadhaidar', 'haidar@fokri2025', 'Majelis Syuro', 'Komisi Pengawasan', 'Pengawas Humas', 'Politeknik Keuangan Negara STAN', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS15', 'Sarah Nurfajar', 'sarahnurfajar', 'sarah@fokri2025', 'Majelis Syuro', 'Komisi Pengawasan', 'Pengawas Kemuslimahan', 'Politeknik Statistika STIS', 'Perempuan', '2025-06-17 12:40:14', NULL),
('MS16', 'Tiara Amanda Putri Arkiang', 'tiaraamanda', 'tiara@fokri2025', 'Majelis Syuro', 'Komisi Pengawasan', 'Pengawas Pelayanan Umat', 'Sekolah Tinggi Meteorologi Klimatologi Dan Geofisika', 'Perempuan', '2025-06-17 12:40:14', NULL),
('MS17', 'Muhammad Soeltan Sirradj', 'muhammadsoeltan', 'soeltan@fokri2025', 'Majelis Syuro', 'Komisi Pengawasan', 'Pengawas Tarbiyah', 'Politeknik Enjinering Pertanian Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS2', 'Jauharil Imam', 'jauharilimam', 'jauharil@fokri2025', 'Majelis Syuro', 'BPH', 'Wakil Ketua', 'Politeknik Siber Dan Sandi Negara', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS3', 'Ahmad Laroyba', 'ahmadlaroyba', 'ahmad@fokri2025', 'Majelis Syuro', 'BPH', 'Sekretaris', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS4', 'Syadza Khumairah Akmul', 'syadzakhumairah', 'syadza@fokri2025', 'Majelis Syuro', 'BPH', 'Bendahara', 'Politeknik Statistika STIS', 'Perempuan', '2025-06-17 12:40:14', NULL),
('MS5', 'Muhadzib Rezki Hilmy', 'muhadzibrezki', 'muhadzib@fokri2025', 'Majelis Syuro', 'Komisi Aspirasi', 'Staf', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS6', 'Muhammad Farouq Al Yaasin', 'muhammadfarouq', 'farouq@fokri2025', 'Majelis Syuro', 'Komisi Aspirasi', 'Staf', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS7', 'Nasywa Sabrina Zain', 'nasywasabrina', 'nasywa@fokri2025', 'Majelis Syuro', 'Komisi Aspirasi', 'Staf', 'Politeknik Enjiniring Pertanian Indonesia', 'Perempuan', '2025-06-17 12:40:14', NULL),
('MS8', 'Muhammad Saka Almadi', 'muhammadsaka', 'saka@fokri2025', 'Majelis Syuro', 'Komisi Aspirasi', 'Staf', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('MS9', 'Refalita Candra', 'refalitacandra', 'refalita@fokri2025', 'Majelis Syuro', 'Komisi Legislasi', 'Staf', 'Politeknik Siber Dan Sandi Negara', 'Perempuan', '2025-06-17 12:40:14', NULL),
('SN1', 'Erlangga Danny Rimba Pradana', 'erlanggadanny', 'erlangga@fokri2025', 'Senat', 'BPH', 'Ketua', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('SN10', 'Hikmawati Ahmad', 'hikmawatiahmad', 'hikmawati@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Staf', 'Pokuteknik Statistika STIS', 'Perempuan', '2025-06-17 12:40:14', 'TIK'),
('SN11', 'Prigani Ayadilaga', 'priganiayadilaga', 'prigani@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Staf', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', 'TIK'),
('SN12', 'Syifa Ramadhani', 'syifaramadhani', 'syifa@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Staf', 'Politeknik Enjiniring Pertanian Indonesia', 'Perempuan', '2025-06-17 12:40:14', 'TIK'),
('SN13', 'Firkatun Najia', 'firkatunnajia', 'firkatun@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Koordinator', 'Institut Pemeritahan Dalam Negeri', 'Perempuan', '2025-06-17 12:40:14', 'Kerjasama'),
('SN14', 'Muhammad Hadiyan Najib', 'muhammadhadiyan', 'hadiyan@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Staf', 'Politeknik Keuangan Negara STAN', 'Laki-laki', '2025-06-17 12:40:14', 'Kerjasama'),
('SN15', 'Zahira Priyan Husna', 'zahirapriyan', 'zahira@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Staff', 'Politeknik Statistika STIS', 'Perempuan', '2025-06-17 12:40:14', 'Kerjasama'),
('SN16', 'Ahmad Naufal Arkhan', 'ahmadnaufal', 'ahmad@fokri2025', 'Senat', 'Pelayanan Umat', 'Koordinator', 'Politeknik Siber Dan Sandi Negara', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('SN17', 'Ahmad Aqila Nubala', 'ahmadaqila', 'ahmad@fokri2025', 'Senat', 'Pelayanan Umat', 'Staf', 'Sekolah Tinggi Meteorologi Klimatologi Dan Geofisika', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('SN18', 'Faiqking Rafif Dary Athallah Adnan', 'faiqkingrafif', 'faiqking@fokri2025', 'Senat', 'Tarbiyah', 'Koordinator', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('SN19', 'Andriyo', 'andriyo', 'andriyo@fokri2025', 'Senat', 'Tarbiyah', 'Koordinator', 'Politeknik Enjinering Pertanian Indonesia', 'Laki-laki', '2025-06-17 12:40:14', 'Syiar'),
('SN2', 'Ferdy Kusuma', 'ferdykusuma', 'ferdy@fokri2025', 'Senat', 'BPH', 'Wakil Ketua', 'Politeknik Statistika STIS', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('SN20', 'Syamril', 'syamril', 'syamril@fokri2025', 'Senat', 'Tarbiyah', 'Staf', 'Sekolah Tinggi Meteorologi Klimatologi Dan Geofisika', 'Laki-laki', '2025-06-17 12:40:14', 'Syiar'),
('SN21', 'Muhammad Vadiellah Abdi', 'muhammadvadiellah', 'vadiellah@fokri2025', 'Senat', 'Tarbiyah', 'Staf', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', 'Kaderisasi'),
('SN22', 'Cut Fitriani', 'cutfitriani', 'cut@fokri2025', 'Senat', 'Kemuslimahan', 'Koordinator', 'Politeknik Enjinering Pertanian Indonesia', 'Perempuan', '2025-06-17 12:40:14', NULL),
('SN23', 'Salwa Berlian Nova', 'salwaberlian', 'salwa@fokri2025', 'Senat', 'Kemuslimahan', 'Staf', 'Politeknik Siber Dan Sandi Negara', 'Perempuan', '2025-06-17 12:40:14', NULL),
('SN24', 'Laila Fiqy Rahayu', 'lailafiqy', 'laila@fokri2025', 'Senat', 'Kemuslimahan', 'Staf', 'Politeknik Enjinering Pertanian Indonesia', 'Perempuan', '2025-06-17 12:40:14', NULL),
('SN25', 'Arya Pratama Putra', 'aryapratama', 'arya@fokri2025', 'Senat', 'Tarbiyah', 'Koordinator', 'Politeknik Pembangunan Pertanian Medan', 'Laki-laki', '2025-06-18 17:06:11', 'Kaderisasi'),
('SN3', 'Isnaini Putri Laili N A', 'isnainiputri', 'isnaini@fokri2025', 'Senat', 'BPH', 'Sekretaris', 'Politeknik Statistika STIS', 'Perempuan', '2025-06-17 12:40:14', NULL),
('SN4', 'Ralfian Faikar A W', 'ralfianfaikar', 'ralfian@fokri2025', 'Senat', 'BPH', 'Sekretaris', 'Politeknik Pengayoman Indonesia', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('SN5', 'Baran Hidayat Azzahra', 'baranhidayat', 'baran@fokri2025', 'Senat', 'BPH', 'Bendahara', 'Politeknik Statistika STIS', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('SN6', 'Diva Aulia', 'divaaulia', 'diva@fokri2025', 'Senat', 'BPH', 'Bendahara', 'Politeknik Enjinering Pertanian Indonesia', 'Perempuan', '2025-06-17 12:40:14', NULL),
('SN7', 'Aga Ucu Fradana', 'agaucu', 'aga@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Koordinator', 'Politeknik Siber Dan Sandi Negara', 'Laki-laki', '2025-06-17 12:40:14', NULL),
('SN8', 'Hafiizh Nabil Aji Purwanto', 'hafiizhnabil', 'hafiizh@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Koordinator', 'Politeknik Perkeretaapian Indonesia Madiun', 'Laki-laki', '2025-06-17 12:40:14', 'TIK'),
('SN9', 'Julanar Jihan Lestari', 'julanarjihan', 'julanar@fokri2025', 'Senat', 'Hubungan Masyarakat', 'Staf', 'Politeknik Siber Dan Sandi Negara', 'Perempuan', '2025-06-17 12:40:14', 'TIK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_berita_penulis` (`penulis`);

--
-- Indexes for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dokumen_uploaded` (`uploaded_by`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kegiatan_pj` (`penanggung_jawab`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pengumuman_dibuat` (`created_by`);

--
-- Indexes for table `pengurus`
--
ALTER TABLE `pengurus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123456802;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `fk_berita_penulis` FOREIGN KEY (`penulis`) REFERENCES `pengurus` (`id`);

--
-- Constraints for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `fk_dokumen_uploaded` FOREIGN KEY (`uploaded_by`) REFERENCES `pengurus` (`id`);

--
-- Constraints for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD CONSTRAINT `fk_kegiatan_pj` FOREIGN KEY (`penanggung_jawab`) REFERENCES `pengurus` (`id`);

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `fk_pengumuman_dibuat` FOREIGN KEY (`created_by`) REFERENCES `pengurus` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
