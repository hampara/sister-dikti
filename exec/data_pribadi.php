<!--
// =====================
// data_pribadi-profil
// =====================
-->
<h2 style='color: orange'>perubahan pada: poto, keluarga, alamat, lain, bidang ilmu</h2>
<?php
echo "id_sdm = ".$id_sdm."<br><br>";
include 'ws.php';
function do_data_pribadi($id_sdm) {
    $post = ["id_sdm" => $id_sdm ];
    
    $result_string = wsREF("data_pribadi/foto/{".$id_sdm."}");
    $result= json_decode($result_string, true);
    if ($result['kode']==401) {
        echo "<br>Token expired"; exit();
    }
    echo ($result != null) ? $result : "poto zonk";
    
    $result_string = wsREF("data_pribadi/profil/{$id_sdm}");
	$result= json_decode($result_string, true);
	echo "<table><tr><td><tr><td><pre><b>profil</b><br>";
	print_r($result);
	echo "</pre></td></tr>";

	$result_string = wsGET("data_pribadi/kependudukan/{$id_sdm}", $post);
	$result= json_decode($result_string, true);
	echo "<tr><td><pre><b>kependudukan</b><br>";
	print_r($result);
	echo "</pre></td></tr>";
	
	$result_string = wsGET("data_pribadi/keluarga/{$id_sdm}", $post);
	$result= json_decode($result_string, true);
	echo "<tr><td><pre><b>keluarga</b><br>";
	print_r($result);
	echo "</pre></td></tr>";
	
	$result_string = wsGET("data_pribadi/alamat/{$id_sdm}", $post);
	$result= json_decode($result_string, true);
	echo "<tr><td><pre><b>alamat</b><br>";
	print_r($result);
	echo "</pre></td></tr>";
	
	$result_string = wsGET("data_pribadi/kepegawaian/{$id_sdm}", $post);
	$result= json_decode($result_string, true);
	echo "<tr><td><pre><b>kepegawaian</b><br>";
	print_r($result);
	echo "</pre></td></tr>";
	
	$result_string = wsGET("data_pribadi/lain/{$id_sdm}", $post);
	$result= json_decode($result_string, true);
	echo "<tr><td><pre><b>lain</b><br>";
	print_r($result);
	echo "</pre></td></tr>";
	
	$result_string = wsGET("data_pribadi/bidang_ilmu/{$id_sdm}", $post);
	$result= json_decode($result_string, true);
	echo "<tr><td><pre><b>bidang_ilmu</b><br>";
	print_r($result);
	echo "</pre></td></tr></table>";
} do_data_pribadi($id_sdm);

?>