<!--
// =====================
// referensi
// =====================
-->
<?php
function wsRefferensi($act) {
	$ch = curl_init();
	
	$header = array();
	$header[] = "Content-Type: application/json";
	$header[] = "Authorization: ".$_SESSION['token_sister'];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	
	$url = "https://sister-api.kemdiktisaintek.go.id/ws-sandbox.php/1.0/referensi/";
	curl_setopt($ch, CURLOPT_URL, $url.$act);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($ch);
	curl_close($ch);
	
	return $result;
}
// ==============================
// mulai dr sini
// ==============================
//echo "<h3 style='color:blue'>Data refferensi</h3><hr>";

$reff = $_GET['p'];
if (strpos($reff, "/")>0) {
    $mulai = strpos($reff, "/")+1;
    $param = substr($reff, $mulai);
    
    include 'ws.php';
    if ($param=='unit_kerja') {
    	$post = [ "id_perguruan_tinggi" => "412a29ff-2e1e-4075-adf3-6aae6477d940" ];
    	$result = json_decode(wsGET("referensi/unit_kerja", $post));
    } elseif ($param=='detail_unit_kerja') {
        $post = [ "id_unit_kerja" => "8516ffbe-f6aa-4e95-b15f-28a7f9660977" ];
        $result = json_decode(wsGET("referensi/unit_kerja", $post));
        echo "<table><tr><td><pre><b>$param</b><br>";
        print_r($result);
        echo "</pre></td></tr></table><hr>";
        $post = [ "id_unit_kerja" => "044fea70-3033-47e8-9c97-0806516a9321" ];
        $result = json_decode(wsGET("referensi/unit_kerja", $post));
    } elseif ($param=='mahasiswa_pddikti') {
        $post = [ "id_perguruan_tinggi" => "412a29ff-2e1e-4075-adf3-6aae6477d940", "id_program_studi" => "8516ffbe-f6aa-4e95-b15f-28a7f9660977" ];
        $result = json_decode(wsGET("referensi/unit_kerja", $post));
        echo "<table><tr><td><pre><b>$param</b><br>";
        print_r($result);
        $post = [ "id_perguruan_tinggi" => "412a29ff-2e1e-4075-adf3-6aae6477d940", "id_program_studi" => "044fea70-3033-47e8-9c97-0806516a9321" ];
        $result = json_decode(wsGET("referensi/unit_kerja", $post));
        print_r($result);
        echo "</pre></td></tr></table><hr>";
    } elseif ($param=='wilayah') {
    	$post = [ "id_level_wilayah" => 0 ];
    	$result = json_decode(wsGET("referensi/wilayah", $post));
        echo "<table><tr><td><pre><b>$param negara</b><br>";
        print_r($result);
        echo "</pre></td></tr></table>";
        $post = [ "id_level_wilayah" => 1 ];
        $result = json_decode(wsGET("referensi/wilayah", $post));
        echo "<table><tr><td><pre><b>$param provinsi</b><br>";
        print_r($result);
        echo "</pre></td></tr></table>";
        $post = [ "id_level_wilayah" => 2 ];
        $result = json_decode(wsGET("referensi/wilayah", $post));
        echo "<table><tr><td><pre><b>$param kabupaten</b><br>";
        print_r($result);
        echo "</pre></td></tr></table>";
        $post = [ "id_level_wilayah" => 3 ];
        $result = json_decode(wsGET("referensi/wilayah", $post));
    } elseif ($param=='kategori_kegiatan') {
    	$post = [ "tipe" => 'list' ];
    	$result = json_decode(wsGET("referensi/kategori_kegiatan", $post));
    } elseif ($param=='kelompok_bidang') {
    	$post = [ "iptek" => false ];
    	$result = json_decode(wsGET("referensi/kelompok_bidang", $post));
    } else {
        $result = json_decode(wsRefferensi($param));
    }
    echo "<table><tr><td><pre><b>$param</b><br>";
    print_r($result);
    echo "</pre></td></tr></table>";
//} else {
    //$result = json_decode(wsRefferensi($param));
} ?>
