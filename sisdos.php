<?php
session_start();
include 'koneksi.php';
$id_pt = "412a29ff-2e1e-4075-adf3-6aae6477d940"; // sesuaikan id PT-mu
$id_sdm = $_SESSION['login_sdm'] ?? (isset($_SESSION['login_id']) ? mysqli_fetch_row(mysqli_query($link, "select uid from masterdos where kodedos='".$_SESSION['login_id']."'"))[0] : "");
$nm_dos = $_SESSION['login_dos'] ?? $_SESSION['login_dtt'] ?? "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<title>Sister <?=$nm_dos?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body >

<table><tr><td style="vertical-align: top;">
	<table><tr><td colspan="2"></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=anggota_profesi'>anggota_profesi</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=bahan_ajar'>bahan_ajar</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=beasiswa'>beasiswa</a></td></tr>
	<tr><td style="color: #F00" title="clear session" id="cs" onclick="clearToken()">GET</td><td><a href='?p=bimbing_dosen'>bimbing_dosen</a></td></tr>
	<tr><td style="color: #22F">GET</td><td><a href='?p=bimbingan_mahasiswa'>bimbingan_mahasiswa</a></span></td></tr>
	<tr><td style="color: #F22">GET</td><td><a href='?p=bkd'>bkd</a></td></tr>
	<tr><td style="color: #22F">GET</td><td><a href='?p=data_pribadi'>data_pribadi</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td title="adalah penempatan pegawai, dosen, atau tenaga profesional untuk bertugas di suatu instansi, organisasi, atau wilayah lain dalam jangka waktu tertentu. Dalam bahasa Inggris, istilah ini dikenal sebagai secondment"><a href='?p=detasering'>detasering</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=diklat'>diklat</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=dokumen'>dokumen</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=inpassing'>inpassing</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=jabatan_fungsional'>jabatan_fungsional</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=jabatan_struktural'>jabatan_struktural</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=kekayaan_intelektual'>kekayaan_intelektual</a></td></tr>
	<tr><td style="color: #F00">GET</td><td><a href='?p=kepangkatan'>kepangkatan</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=kesejahteraan'>kesejahteraan</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=kolaborator_eksternal'>kolaborator_eksternal</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=nilai_tes'>nilai_tes</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=orasi_ilmiah'>orasi_ilmiah</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=pembicara'>pembicara</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=pendidikan_formal'>pendidikan_formal</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=penelitian'>penelitian</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=pengabdian'>pengabdian</a></td></tr>
	<tr><td style="color: #22F">GET</td><td><a href='?p=pengajaran'>pengajaran</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=pengelola_jurnal'>pengelola_jurnal</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=penghargaan'>penghargaan</a></td></tr>
	<tr><td style="color: #F22">GET</td><td><a href='?p=pengujian_mahasiswa'>pengujian_mahasiswa</a></td></tr>
	<tr><td style="color: #F22">GET</td><td><a href='?p=penugasan'>penugasan</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=penunjang_lain'>penunjang_lain</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=publikasi'>publikasi</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=riwayat_pekerjaan'>riwayat_pekerjaan</a></td></tr>
	<tr><td style="color: #F9F">GET</td><td><a href='?p=sertifikasi_dosen'>sertifikasi_dosen</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=sertifikasi_profesi'>sertifikasi_profesi</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=tugas_tambahan'>tugas_tambahan</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=tunjangan'>tunjangan</a></td></tr>
	<tr><td style="color: #2F2">GET</td><td><a href='?p=visiting_scientist'>visiting_scientist</a></td></tr>
	</table></td>
    <?php 
    if (isset($_GET['p'])) {
        $p = $_GET['p'];
        $pnjng = strpos($p, "/");
        ?><td style='background: yellow; padding: 1px'></td><td style="vertical-align: top;"><table><caption style='padding: 4px; background: gold;'><b><?=$p?></b> <span id="iddos" onclick="copyID();"><?=$id_sdm?></span></caption><tr><td>
        <?php
        if ($pnjng>0) {
            $param = substr($p, 0, $pnjng);
            include "exec/$param.php";
        } else {
            include "exec/$p.php";
        } echo "</td></tr></table></td>";
    }
    if (isset($_SESSION['token_sister'])) {
        $token = $_SESSION['token_sister'];
        ?>
	    <script>
	    	document.getElementById('token').value = "<?=$token?>";
	    	document.getElementById('cc').disabled = false;
	    </script>
	    <?php
	}
	?>
</tr></table>
</body></html>
<?php ?>