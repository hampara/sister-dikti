<?php
$amenu = array("anggota_profesi","bahan_ajar","detasering","diklat","kekayaan_intelektual","jabatan_struktural","orasi_ilmiah","penelitian","pembicara","pengabdian","pengelola_jurnal","penghargaan","penunjang_lain","publikasi","tugas_tambahan","visiting_scientist");
$url = "https://sister-api.kemdiktisaintek.go.id/ws-sandbox.php/1.0/";
function wsGET($act, $data) {
    global $url;
    $ch = curl_init();

    $header = array();
    $header[] = "Content-Type: application/json";
    $header[] = "Authorization: ".$_SESSION['token_sister'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    
    $post = http_build_query($data);
    curl_setopt($ch, CURLOPT_URL, $url.$act.'?'.$post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

if (!isset($_SESSION['token_sister_mulai'])) {
    include 'authorize.php';
    $result = json_decode(do_authorize(), true);
    $_SESSION['token_sister'] = "Bearer ".$result['token'];
    $_SESSION['token_sister_mulai'] = date('H:i:s');
} else {
    $mulai = $_SESSION['token_sister_mulai'];
    $skrng = date("H:i:s");
    $diff  = date_diff(date_create($skrng), date_create($mulai));
    $jam = $diff->format("%h");
    $mnt = $diff->format("%i");
    
    if ( ($jam>0) || ($jam == 0 && $mnt>=58)) {
        include 'exec/authorize.php';
        $result = json_decode(do_authorize(), true);
        $_SESSION['token_sister'] = "Bearer ".$result['token'];
        $_SESSION['token_sister_mulai'] = date('H:i:s');
    }
}

$post  = [ "tipe" => 'list' ];
$result= json_decode(wsGET("referensi/kategori_kegiatan", $post));

if (is_array($result)) {
    $ds = "mulai";
    if (count($result)>0) {
        $ds = "<table style=\"font-family: 'Arial narrow'\"><caption style='padding: 4px; background: lime'>kategori_kegiatan</caption>\n<tr><th>ID</th><th>parent_id</th><th>nama</th></tr>";
        for ($i=0; $i<count($result); $i++) {
            $array = json_decode(json_encode($result[$i]), true);
            $id_i  = $array['id'];
            $id_p  = $array['parent_id'];
            $id_n  = $array['nama'];
            $ds .= "<tr><td>$id_i</td><td>$id_p</td><td>$id_n</td></tr>\n";
        } $ds .= "</table><br>\n";
    } else {
        $ds = "<pre>z0nk</pre>\n";
    } echo $ds;
}
?>
