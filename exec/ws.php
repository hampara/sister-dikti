<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
//$url = "https://sister-api.kemdiktisaintek.go.id/ws.php/1.0/";
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
	$chCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);
	
	$kode = ["kode"=>$chCode];
	$atmp = json_decode($result, true);
	$mergedArray = array_merge($kode, $atmp);
	
	return json_encode($mergedArray, JSON_PRETTY_PRINT);
}
function wsREF($act) {
    global $url;
    $ch = curl_init();
    
    $header = array();
    $header[] = "Content-Type: application/json";
    $header[] = "Authorization: ".$_SESSION['token_sister'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    curl_setopt($ch, CURLOPT_URL, $url.$act);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec($ch);
    $chCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    $kode = ["kode"=>$chCode];
    $atmp = json_decode($result, true);
    $mergedArray = array_merge($kode, $atmp);
    
    return json_encode($mergedArray, JSON_PRETTY_PRINT);
}
function wsPOST($act, $data) {
	global $url;
	$post = json_encode($data);
	
	$ch = curl_init($url.$act);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
	    "Accept: application/json",
	    "Content-Type: application/json",
	    "Authorization: ". $_SESSION['token_sister'],
	    "Content-Length: " . strlen($post)
	]);
	
	$result = curl_exec($ch);
	$chCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);
	
	$kode = ["kode"=>$chCode];
	$atmp = json_decode($result, true);
	$mergedArray = array_merge($kode, $atmp);
	
	return json_encode($mergedArray, JSON_PRETTY_PRINT);
}
function wsPUT_DOK($id, $data) {
    global $url;
    $post = json_encode($data);
    
    $ch = curl_init($url.'dokumen/'.$id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization: ". $_SESSION['token_sister'],
        "Content-Length: " . strlen($post)
    ]);
    
    $result = curl_exec($ch);
    $chCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    $kode = ["kode"=>$chCode];
    $atmp = json_decode($result, true);
    $mergedArray = array_merge($kode, $atmp);
    
    return json_encode($mergedArray, JSON_PRETTY_PRINT);
}
function wsPUT($act, $id, $data) {
    global $url;
    $put = json_encode($data);
    
    $ch = curl_init($url.$act."/{".$id."}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $put);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization: " . $_SESSION['token_sister'],
        "Content-Length: " . strlen($put)
    ]);
    
    $result = curl_exec($ch);
    $chCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    $kode = ["kode"=>$chCode];
    $atmp = json_decode($result, true);
    $mergedArray = array_merge($kode, $atmp);
    
    return json_encode($mergedArray, JSON_PRETTY_PRINT);
}
function wsDEL($act, $id) {
    global $url;
    
    $ch = curl_init($url.$act."/{".$id."}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization: " . $_SESSION['token_sister']
    ]);
    
    $result = curl_exec($ch);
    $chCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    $kode = ["kode"=>$chCode];
    $atmp = json_decode($result, true);
    $mergedArray = array_merge($kode, $atmp);
    
    return json_encode($mergedArray, JSON_PRETTY_PRINT);
}
?>