<!--
// =====================
// bimbing_dosen
// =====================
-->
<?php 
include 'ws.php';
function do_get_bimbing_dosen($id_sdm) {
	$post = ["id_sdm" => $id_sdm ];
	$result_string = wsGET("bimbing_dosen", $post);
	return json_decode($result_string, true);
}
// ==============================
// mulai dr sini
// ==============================
//echo "<h2 style='color:red'>Data bersumber dari PDDIKTI dan tidak dapat diubah di SISTER</h2>";
//include 'koneksi.php';
?><table><tr><td style="vertical-align: top;">
<?php
$qpen = mysqli_query($link, "select nim,namamhs,kodebim,tglmulai from bimbingan where kodedos='".$_SESSION['kddos']."' and $whereTASmt");
if (mysqli_num_rows($qpen)>0) {
	$i = 0;
	?><table><tr><td style="vertical-align: top;">
	<table><caption style="padding: 4px; background: lime">Tabel&nbsp;SIAA</caption>
	<tr><td>No</td><th>NIM</th><th>Nama Mhs</th><td>Bim.#</td><th>Mulai</th></tr>
	<?php 
	while ($rpen = mysqli_fetch_row($qpen)) {
		$i++;
		echo "<tr><td>$i</td><td>$rpen[0]</td><td>$rpen[1]</td><th>$rpen[2]</th><td>$rpen[3]</td></tr>\n";
	}?></table></td>
	<td style="background: yellow;"></td><td style="vertical-align: top;"><table><caption style="padding: 4px; background: aqua;">Tabel&nbsp;PDPT</caption>
	<tr><td>
	<?php 
	if (isset($_GET['p'])) {
	    $param = $_GET['p'];
	    
	    $result = do_get_bimbing_dosen($id_sdm);
	    if ($result!=null) {
	        if ($result['kode']==401) {
	            echo "<br>Token expired"; exit();
	        }
	        echo "<br>Record <b>$param</b><br><br><table>";
	        foreach ($result as $res) {
	            if (is_array($res)) {
	                echo "<tr><td><table>";
	                foreach ($res as $kol => $isi) {
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
	} ?></td></tr></table></td></tr></table>
	<?php
} else {
    echo "<tr><td>lom ada catatan bimbing_dosen di smt ini</td></tr>";
} mysqli_free_result($qpen);
?>