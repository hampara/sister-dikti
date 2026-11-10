<?php 
session_start();
// =========
// authorize
// =========
function do_authorize() {
    //$url = "https://sister-api.kemdiktisaintek.go.id/ws.php/1.0/authorize";
    $url = "https://sister-api.kemdiktisaintek.go.id/ws-sandbox.php/1.0/authorize";
    
    $username_sister = "Bearer xxxx";
    $password_sister = "password-mu";
    $id_user_sister  = "00000000-0000-0000-0000-000000000000";
    
    $post = [
        "username"   => $username_sister,
        "password"   => $password_sister,
        "id_pengguna"=> $id_user_sister
    ]; $data = json_encode($post);
    
    $ch = curl_init();
    
    $header = array();
    $header[] = "Content-Type: application/json";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

if (isset($_POST['todo']) && $_POST['todo'] == 'otorisasi') {
    $result = json_decode(do_authorize(), true);
    $token  = $_SESSION['token_sister'] = "Bearer ".$result['token'];
    echo json_encode(['pesan' => $token]);
} elseif (isset($_POST['sesi']) && $_POST['sesi'] == 'unset') {
    //unset($_SESSION['token_sister']);
    session_unset();
    session_destroy();
    echo json_encode(['pesan' => "token reset"]);;
}
?>