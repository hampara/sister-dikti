<?php
include '../exec/ws.php';
include '../koneksi.php';
$url = "http://localhost:8100/ws/live2.php";

$user_pdpt= "username-pdpt-mu";
$pass_pdpt= "password-pdpt-mu";

function runWS($data) {
    global $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    
    $header = array();
    $header[] = "Content-Type: application/json";
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    if ($data) {
        $data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

$token = $_SESSION['token_neo'] ?? "";
$error = null;
if (!isset($_SESSION['token_neo'])) {
    $act   = "GetToken";
    $data  = array('act'=>$act, 'username'=>$user_pdpt, 'password'=>$pass_pdpt);
    $result= json_decode(runWS($data), true);
    if (isset($result['data']['token'])) {
        $token = $_SESSION['token_neo'] = $result['data']['token'];
    } else {
        if (isset($result['error_desc'])) {
            $error = $result['error_desc'];
        } else {
            $error = "Null";
        }
    }
}
if ($error==null) {
    $filter= '';
    $order = "CAST(coalesce(id_pangkat_golongan, '0') AS integer)";
    $limit = 0;
    $offset= 0;
    $data  = array('act'=>'GetPangkatGolongan', 'token'=>$token, 'filter'=>$filter, 'order'=>$order, 'limit'=>$limit, 'offset'=>$offset );
    $result = json_decode(runWS($data), true);
    if (count($result)>0) {
        $i=0;
        foreach ($result['data'] as $baris) {
            $i++;
            $id   = $baris['id_pangkat_golongan'];
            $nama = $baris['nama_pangkat'];
            $ket  = $baris['kode_golongan'];
            $pkt  = $ket." ".$nama;
            $prep = " sister set menu='golongan_pangkat', id='$id', nama='$pkt'";
            $qcek = mysqli_query($link, "select idx from sister where menu='golongan_pangkat' and id='$id'");
            if (mysqli_num_rows($qcek)>0) {
                $prep = "update $prep where idx ='".mysqli_fetch_row($qcek)[0]."'";
            } else {
                $prep = "insert into ".$prep;
            }
            if (mysqli_query($link, $prep)) {
                echo $id." => ".$pkt." OK<br>";
            } else {
                echo mysqli_error($link)." :: ".$prep."<br>";
            }
        }
    }
} else {
    echo $error;
}