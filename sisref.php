<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<title>Refferensi Sister</title>
</head>
<body >
<table><tr><td style="vertical-align: top;">
	<table>
	<tr><td><a href='?p=referensi/agama'>referensi/agama</a></td></tr>
	<tr><td><a href='?p=referensi/bidang_studi'>referensi/bidang_studi</a></td></tr>
	<tr><td><a href='?p=referensi/bidang_usaha'>referensi/bidang_usaha</a></td></tr>
	<tr><td><a href='?p=referensi/gelar_akademik'>referensi/gelar_akademik</a></td></tr>
	<tr><td><a href='?p=referensi/golongan_pangkat'>referensi/golongan_pangkat</a></td><td><a href='tool/pangkat_gol.php' target='_blank'>Neo</a></td></tr>
	<tr><td><a href='?p=referensi/ikatan_kerja'>referensi/ikatan_kerja</a></td></tr>
	<tr><td><a href='?p=referensi/jenis_dokumen'>referensi/jenis_dokumen</a></td></tr>
	<tr><td><a href='?p=referensi/jabatan_fungsional'>referensi/jabatan_fungsional</a></td></tr>
	<tr><td><a href='?p=referensi/jabatan_negara'>referensi/jabatan_negara</a></td></tr>
	<tr><td><a href='?p=referensi/jabatan_tugas_tambahan'>referensi/jabatan_tugas_tambahan</a></td><td>nul</td></tr>
	<tr><td><a href='?p=referensi/jenis_bahan_ajar'>referensi/jenis_bahan_ajar</a></td><td>x</td></tr>
	<tr><td><a href='?p=referensi/jenis_penghargaan'>referensi/jenis_penghargaan</a></td></tr>
	<tr><td><a href='?p=referensi/jenis_kepanitiaan'>referensi/jenis_kepanitiaan</a></td></tr>
	<tr><td><a href='?p=referensi/jenis_kesejahteraan'>referensi/jenis_kesejahteraan</a></td><td>nul</td></tr>
	<tr><td><a href='?p=referensi/jenis_beasiswa'>referensi/jenis_beasiswa</a></td><td>nul</td></tr>	
	<tr><td><a href='?p=referensi/jenis_diklat'>referensi/jenis_diklat</a></td><td>nul</td></tr>
	<tr><td><a href='?p=referensi/jenis_keluar'>referensi/jenis_keluar</a></td></tr>
	<tr><td><a href='?p=referensi/jenis_pekerjaan'>referensi/jenis_pekerjaan</a></td></tr>
	<tr><td><a href='?p=referensi/jenis_publikasi'>referensi/jenis_publikasi</a></td></tr>
	<tr><td><a href='?p=referensi/jenis_tes'>referensi/jenis_tes</a></td><td>nul</td></tr>
	<tr><td><a href='?p=referensi/jenis_tunjangan'>referensi/jenis_tunjangan</a></td></tr>	
	<tr><td><a href='?p=referensi/jenjang_pendidikan'>referensi/jenjang_pendidikan</a></td></tr>
	<tr><td><a href='?p=referensi/kategori_capaian_luaran'>referensi/kategori_capaian_luaran</a></td></tr>
	<tr><td><a href='?p=referensi/kategori_kegiatan'>referensi/kategori_kegiatan</a></td></tr>
	<tr><td><a href='?p=referensi/kelompok_bidang'>referensi/kelompok_bidang</a></td><td>x</td></tr>
	<tr><td><a href='?p=referensi/lembaga_sertifikasi'>referensi/lembaga_sertifikasi</a></td><td>x</td></tr>
	<tr><td><a href='?p=referensi/media_publikasi'>referensi/media_publikasi</a></td><td>x</td></tr>
	<tr><td><a href='?p=referensi/negara'>referensi/negara</a></td></tr>
	<tr><td><a href='?p=referensi/skim_kegiatan'>referensi/skim_kegiatan</a></td><td>null</td></tr>
	<tr><td><a href='?p=referensi/status_kepegawaian'>referensi/status_kepegawaian</a></td></tr>
	<tr><td><a href='?p=referensi/sumber_gaji'>referensi/sumber_gaji</a></td></tr>
	<tr><td><a href='?p=referensi/tingkat_penghargaan'>referensi/tingkat_penghargaan</a></td></tr>
	<tr><td><a href='?p=referensi/wilayah'>referensi/wilayah</a></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><a href='?p=referensi/perguruan_tinggi'>referensi/perguruan_tinggi</a></td></tr>
	<tr><td><a href='?p=referensi/unit_kerja'>referensi/unit_kerja</a></td></tr>
	<tr><td><a href='?p=referensi/detail_unit_kerja'>referensi/<s>detail_unit_kerja</s></a></td></tr>
	<tr><td><a href='?p=referensi/dudi'>referensi/<s>dudi</s></a></td></tr>
	<tr><td><a href='?p=referensi/mahasiswa_pddikti'>referensi/mahasiswa_pddikti</a></td></tr>
	<tr><td><a href='?p=referensi/profil_pt'>referensi/profil_pt</a></td></tr>
	<tr><td><a href='?p=referensi/sdm'>referensi/sdm</a></td></tr>
	<tr><td><a href='?p=referensi/semester'>referensi/semester</a></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><a href='?t=truncate'>truncate tabel</a></td></tr>
	</table></td>
<?php 
if (isset($_GET['x'])) {
    include 'exec/ws.php';
    
    ?><td style="background: red;"></td><td style="vertical-align: top;"><?php
    
    $reff = $_GET['x'];
    $mulai = strpos($reff, "/")+1;
    $param = substr($reff, $mulai);
    if ($param=='kategori_kegiatan') {
        $amenu = array("anggota_profesi","bahan_ajar","detasering","diklat","kekayaan_intelektual","jabatan_struktural","orasi_ilmiah","penelitian","pembicara","pengabdian","pengelola_jurnal","penghargaan","penunjang_lain","publikasi","tugas_tambahan","visiting_scientist");
        foreach ($amenu as $menu) {
            $post = [ "tipe" => 'list', 'menu' => $menu];
            $result= json_decode(wsGET($reff, $post), true);
            if (count($result)>1) {
                foreach ($result as $baris) {
                    $id         = $baris['id'];
                    $nama       = $baris['nama'];
                    $parent_id  = $baris['parent_id'];
                    
                    $prep = " sister set menu='$menu', id='$id', parent_id='$parent_id', nama='$nama'";
                    $qcek = mysqli_query($link, "select idx from sister where menu='$menu' and id='$id' and parent_id='$parent_id' and nama='$nama'");
                    if (mysqli_num_rows($qcek)>0) {
                        $prep = "update $prep where idx ='".mysqli_fetch_row($qcek)[0]."'";
                        $iu = 'u';
                    } else {
                        $prep = "insert into ".$prep;
                        $iu = 'i';
                    }
                    if (mysqli_query($link, $prep)) {
                        echo $iu." :: ".$id." => ".$nama."<br>";
                    } else {
                        echo mysqli_error($link)." :: ".$prep."<br>";
                    }
                }
            }
        } $result = null; // agar tidak diproses lagi dibawah
    } elseif ($param=='wilayah') {
        for ($level_wilayah=0; $level_wilayah<4; $level_wilayah++) {
            $post = [ "id_level_wilayah" => $level_wilayah ];
            $result = json_decode(wsGET($reff, $post), true);
            if (count($result)>1) {
                foreach ($result as $baris) {
                    $id         = $baris['id'];
                    $nama       = $baris['nama'];
                    $parent_id  = $baris['id_induk_wilayah'];
                    $prep = " sister set menu='$param', id='$id', parent_id='$parent_id', nama=\"".str_replace("'", "`", $nama)."\"";
                    $qcek = mysqli_query($link, "select idx from sister where menu='$param' and id='$id' and nama=\"".str_replace("'", "`", $nama)."\""); //nama='$nama'");
                    if (mysqli_num_rows($qcek)>0) {
                        $prep = "update $prep where idx ='".mysqli_fetch_row($qcek)[0]."'";
                        $iu = 'update ';
                    } else {
                        $prep = "insert into ".$prep;
                        $iu = 'insert ';
                    }
                    if (mysqli_query($link, $prep)) {
                        echo $iu." :: ".$id." => ".$nama."<br>";
                    } else {
                        echo mysqli_error($link)." :: ".$prep."<br>";
                    }
                }
            }
        } $result = null; // agar tidak diproses lagi dibawah
    } elseif ($param=='kelompok_bidang') {
        $post = [ "iptek" => true ];
        $result = json_decode(wsGET($reff, $post), true);
    } elseif ($param=='media_publikasi') {
        $post = [ "nama" => "islam" ];
        $result = json_decode(wsGET($reff, $post), true);
    } else {
        $result= json_decode(wsREF($reff), true);
    }
    if (count($result)>1) {
        foreach ($result as $baris) {
            $id   = $baris['id'];
            $nama = $baris['nama'];
            $prep = " sister set menu='$param', id='$id', nama=\"".str_replace("'", "`", $nama)."\"";
            $qcek = mysqli_query($link, "select idx from sister where menu='$param' and id='$id' and nama=\"".str_replace("'", "`", $nama)."\""); //nama='$nama'");
            if (mysqli_num_rows($qcek)>0) {
                $prep = "update $prep where idx ='".mysqli_fetch_row($qcek)[0]."'";
                $iu = 'update ';
            } else {
                $prep = "insert into ".$prep;
                $iu = 'insert ';
            }
            if (mysqli_query($link, $prep)) {
                echo $iu." :: ".$id." => ".$nama."<br>";
            } else {
                echo mysqli_error($link)." :: ".$prep."<br>";
            }
        }
    } else {
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
    } ?></td><?php
} elseif (isset($_GET['p'])) {
    ?><td style="background: green;"></td><td style="vertical-align: top;"><?php
    $p = $_GET['p'];
    $pnjng = strpos($p, "/");
    if ($pnjng>0) {
        echo "<a href='?x=$p' title='copy ke tabel lokal' style='margin-left: 8px'>copy</a><br>";
        $param = substr($p, 0, $pnjng);
        include "exec/$param.php";
    } else {
        include "exec/$p.php";
    }
} elseif (isset($_GET['t'])) {
    if (mysqli_query($link, "truncate table sister")) {
        ?><td style="background: black;"></td><td style="vertical-align: top;"><?php
        echo "tabel sister di kosongkan";
        ?></td><?php
    }
}
?></tr></table></body></html>