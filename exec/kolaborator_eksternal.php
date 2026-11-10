<!--
// =====================
// kolaborator_eksternal
// =====================
-->
<?php 
include 'ws.php';
function cariKataTerpanjang($kalimat) {
    $kalimatBersih = preg_replace('/[^\p{L}\p{N}\s]/u', '', $kalimat);
    $daftarKata = explode(' ', $kalimatBersih);
    $kataTerpanjang = '';
    foreach ($daftarKata as $kata) {
        if (mb_strlen($kata) > mb_strlen($kataTerpanjang)) {
            $kataTerpanjang = $kata;
        }
    } return $kataTerpanjang;
}
$keyword = cariKataTerpanjang($_SESSION['login_dos']);
function do_kolaborator_eksternal($nama, $nik) {
    if ($nik=="") {
        $post = ["nama" => $nama];
    } else {
        $post = ["nama" => $nama, "nik" => $nik ];
    }
    $result_string = wsGET("kolaborator_eksternal", $post);
    return json_decode($result_string, true);
}
//echo "<h2 style='color:green'>Data dapat diubah di SISTER</h2>";

if (isset($_GET['t'])) {
    $btl = " | <a href='?p=kolaborator_eksternal'>batal</a>";    
    ?>
    <form action="" method="post"><table>
    <tr><th style="text-align: right;">Nama</th><td><input type="text" name="nama" maxlength="200" value="<?=isset($_POST['nama']) ? $_POST['nama'] : ""?>" style="width: 60ch"></td></tr>
    <tr><th style="text-align: right;">Negara</th><td>Indonesia<input type="hidden" name="negara" value="ID"></td></tr>
    <tr><th style="text-align: right;">Gender</th><td><input type="radio" name="gender" value="<?=isset($_POST['gender']) ? ($_POST['gender']=="L" ? "checked='checked'" : "") : ""?>" required="required">Laki-laki <input type="radio" name="gender" value="<?=isset($_POST['gender']) ? ($_POST['gender']=="P" ? "checked='checked'" : "") : ""?>" required="required">Perempuan</td></tr>
    <tr><th style="text-align: right;">NIK</th><td><input type="text" name="nik" id="nik" maxlength="16" value="<?=isset($_POST['nik']) ? $_POST['nik'] : ""?>" style="width: 17ch"></td></tr>
    <tr><th style="text-align: right;">Tempat lahir</th><td><input type="text" name="tempat_lahir" value="<?=isset($_POST['tempat_lahir']) ? $_POST['tempat_lahir'] : ""?>" style="width: 33ch"></td></tr>
    <tr><th style="text-align: right;">Tanggal lahir</th><td><input type="date" name="tanggal_lahir" value="<?=isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : ""?>"></td></tr>
    <tr><th style="text-align: right;">Jalan</th><td><input type="text" name="nama" value="<?=isset($_POST['jalan']) ? $_POST['jalan'] : ""?>" style="width: 60ch"></td></tr>
    <tr><th style="text-align: right;">RT</th><td><input type="text" name="rt" id="rt" maxlength="3" value="<?=isset($_POST['rt']) ? $_POST['rt'] : ""?>"></td></tr>
    <tr><th style="text-align: right;">RW</th><td><input type="text" name="rw" id="rw" maxlength="3" value="<?=isset($_POST['rw']) ? $_POST['rw'] : ""?>"></td></tr>
    <tr><th style="text-align: right;">Dusun</th><td><input type="text" name="dusun" value="<?=isset($_POST['dusun']) ? $_POST['dusun'] : ""?>" style="width: 60ch"></td></tr>
    <tr><th style="text-align: right;">Kelurahan</th><td><input type="text" name="kelurahan" value="<?=isset($_POST['kelurahan']) ? $_POST['kelurahan'] : ""?>" style="width: 60ch"></td></tr>
    <tr><th style="text-align: right;">Kode pos</th><td><input type="text" name="kode_pos" id="kode_pos" maxlength="6" value="<?=isset($_POST['kode_pos']) ? $_POST['kode_pos'] : ""?>"></td></tr>
    <tr><th style="text-align: right;">Telepon HP</th><td><input type="tel" maxlength="20" name="telepon_hp" id="telepon_hp" value="<?=isset($_POST['telepon_hp']) ? $_POST['telepon_hp'] : ""?>"></td></tr>
    <tr><th style="text-align: right;">email</th><td><input type="email" name="email" value="<?=isset($_POST['email']) ? $_POST['email'] : ""?>"></td></tr>
    <tr><th><a href='?p=<?=$param?>'>batal</a></th><td><input type="submit" name="simpan" value="simpan"></td></tr>
    </table></form>
        <script>
          	setPhoneInputOnly('nik');
          	setPhoneInputOnly('rt');
          	setPhoneInputOnly('rw');
          	setPhoneInputOnly('kode_pos');
          	setPhoneInputOnly('telepon_hp');
        </script>
    <?php
}
if (isset($_GET['d'])) {
    $id = $_GET['d'];
    $result_string = wsREF($param."/".$id);
    $detil = json_decode($result_string, true);
    echo "<a href='?p=$param'>kembali</a> | <a href='?p=$param&t'>+ data</a><br><br>Detil <b>$param</b><pre>";
    print_r(json_encode($detil, JSON_PRETTY_PRINT));
    echo "</pre>";
} elseif (isset($_GET['h'])) {
    $id = $_GET['h'];
    $result_string = wsDEL($param,$id);
    $detil = json_decode($result_string, true);
    echo "<a href='?p=$param'>kembali</a> | <a href='?p=$param&t'>+ data</a><br><br>Detil <b>$param</b><pre>";
    print_r(json_encode($detil, JSON_PRETTY_PRINT));
    echo "</pre>";
} elseif (isset($_GET['p'])) {
    $param = $_GET['p'];
    
    $result = do_kolaborator_eksternal($keyword, "");
    if ($result!=null) {
        if ($result['kode']==401) {
            echo "<br>Token expired"; exit();
        }
        echo "<a href='?p=$param&t'>+ data</a><br>";
        echo "<br>Record <b>$param</b> [keyword : $keyword ]<br><br><table>";
        foreach ($result as $res) {
            if (is_array($res)) {
                echo "<tr><td><table>";
                foreach ($res as $kol => $isi) {
                    if ($kol=="id") {
                        $kol = "<a href='?p=$param&d=$isi' title='detil'>$kol</a>";         // link detil
                        $isi = "<a href='?p=$param&e=$isi' title='edit'>$isi</a></td><td>". // link edit
                            "<a href='?p=$param&h=$isi' title='HAPUS!!!' onclick=\"return confirm('Yakin Hapus ?')\">del!</a>";      // link hapus
                    }
                    if (is_array($isi)) {
                        echo "<tr><th style='text-align: right;'>$kol</th><td><table>";
                        foreach ($isi as $klm => $ici) {
                            echo "<tr><th style='text-align: right;'>$klm</th><td>$ici</td></tr>\n";
                        } echo "</table><br><br></td></tr>\n";
                    } else {
                        echo "<tr><th style='text-align: right;'>$kol</th><td style=\"font-family: 'Arial narrow'\">$isi</td></tr>\n";
                    }
                } echo "</table><br><br></td></tr>\n";
            }
        } echo "</table>";
    } else {
        echo "blom ada data <b>$param</b><br><br>";
    }
}
?><a href="?p=<?=$param?>&t">tambah data</a>
