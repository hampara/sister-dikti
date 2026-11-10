<?php
//include 'konfig.php';
function menu_baru($param) {
    global $link;

    $id_utama = $isi_utama = array();
    $qutama = mysqli_query($link, "select menu,id,nama from sister where menu='$param' and parent_id=0 and id<>0 group by id order by id");
    while ($rutama = mysqli_fetch_assoc($qutama)) {
        $id_utama[] = $rutama['id'];
        $isi_utama[]= $rutama['nama'];
    } mysqli_free_result($qutama);
    
    $idkeg = $id_utama[0];
    $res   = "";
    $res .= "<select name='utama' onchange='this.form.submit()'>";
    for($i=0; $i<count($id_utama); $i++) {
        $sel = "";
        if (isset($_POST['utama'])) {
            if ($_POST['utama'] == $id_utama[$i]) {
                $sel = "selected = 'selected'";
                $idkeg = $id_utama[$i];
            }
        } $res .= "<option value='$id_utama[$i]' $sel>$isi_utama[$i]</option>";
    }
    $res .= "</select><br>\n";
    $qmenu1 = mysqli_query($link, "select menu,id,nama from sister where menu='$param' and parent_id='$idkeg' order by id");
    if (mysqli_num_rows($qmenu1)>0) {
        $id_menu1 = $isi_menu1 = array();
        while ($rmenu = mysqli_fetch_assoc($qmenu1)) {
            $id_menu1[] = $rmenu['id'];
            $isi_menu1[]= $rmenu['nama'];
        }
        $idkeg = $id_menu1[0];
        $res .= "<select name='menu1' onchange='this.form.submit()'>";
        for($i=0; $i<count($id_menu1); $i++) {
            $sel = "";
            if (isset($_POST['menu1'])) {
                if ($_POST['menu1'] == $id_menu1[$i]) {
                    $sel = "selected = 'selected'";
                    $idkeg = $id_menu1[$i];
                }
            } $res .= "<option value='$id_menu1[$i]' $sel>$isi_menu1[$i]</option>";
        } $res .= "</select><br>\n";
        
        $qmenu2 = mysqli_query($link, "select menu,id,nama from sister where menu='$param' and parent_id='$idkeg' order by id");
        if (mysqli_num_rows($qmenu2)>0) {
            $id_menu2 = $isi_menu2 = array();
            while ($rmenu = mysqli_fetch_assoc($qmenu2)) {
                $id_menu2[] = $rmenu['id'];
                $isi_menu2[]= $rmenu['nama'];
            }
            $idkeg = $id_menu2[0];
            $res .= "<select name='menu2' onchange='this.form.submit()'>";
            for($i=0; $i<count($id_menu2); $i++) {
                $sel = "";
                if (isset($_POST['menu2'])) {
                    if ($_POST['menu2'] == $id_menu2[$i]) {
                        $sel = "selected = 'selected'";
                        $idkeg = $id_menu2[$i];
                    }
                } $res .= "<option value='$id_menu2[$i]' $sel>$isi_menu2[$i]</option>";
            } $res .= "</select><br>\n";
            
            $qmenu3 = mysqli_query($link, "select menu,id,nama from sister where menu='$param' and parent_id='$idkeg' order by id");
            if (mysqli_num_rows($qmenu3)>0) {
                $id_menu3 = $isi_menu3 = array();
                while ($rmenu = mysqli_fetch_assoc($qmenu3)) {
                    $id_menu3[] = $rmenu['id'];
                    $isi_menu3[]= $rmenu['nama'];
                }
                $idkeg = $id_menu3[0];
                $res .= "<select name='menu3' onchange='this.form.submit()'>";
                for($i=0; $i<count($id_menu3); $i++) {
                    $sel = "";
                    if (isset($_POST['menu3'])) {
                        if ($_POST['menu3'] == $id_menu3[$i]) {
                            $sel = "selected = 'selected'";
                            $idkeg = $id_menu3[$i];
                        }
                    } $res .= "<option value='$id_menu3[$i]' $sel>$isi_menu3[$i]</option>";
                } $res .= "</select><br>\n";
            } mysqli_free_result($qmenu3);
        } mysqli_free_result($qmenu2);
    } mysqli_free_result($qmenu1);
    $res .= $idkeg;
    return $res;
}

function menu_edit($param, $id) {
    global $link;
    
    $id_utama = $isi_utama = array();
    $qutama = mysqli_query($link, "select menu,id,nama from sister where menu='$param' and parent_id=0 and id<>0 group by id order by id");
    while ($rutama = mysqli_fetch_assoc($qutama)) {
        $id_utama[] = $rutama['id'];
        $isi_utama[]= $rutama['nama'];
    } mysqli_free_result($qutama);
    
    $idkeg = $id_utama[0];
    $res   = "";
    $res .= "<select name='utama' onchange='this.form.submit()'>";
    $id1 = substr($id, 0,2)."0000";
    for($i=0; $i<count($id_utama); $i++) {
        $sel = "";
        if (isset($_POST['utama'])) {
            if ($_POST['utama'] == $id_utama[$i]) {
                $sel = "selected = 'selected'";
                $idkeg = $id_utama[$i];
            }
        } elseif ($id_utama[$i] == $id1) {
            $idkeg = $id_utama[$i];
            $sel = "selected = 'selected'";
        } $res .= "<option value='$id_utama[$i]' $sel>$isi_utama[$i]</option>";
    } $res .= "</select><br>\n";
    
    $qmenu1 = mysqli_query($link, "select menu,id,nama from sister where menu='$param' and parent_id='$idkeg' order by id");
    if (mysqli_num_rows($qmenu1)>0) {
        $id_menu1 = $isi_menu1 = array();
        while ($rmenu = mysqli_fetch_assoc($qmenu1)) {
            $id_menu1[] = $rmenu['id'];
            $isi_menu1[]= $rmenu['nama'];
        }
        $idtmp = $id_menu1[0];
        $id1 = substr($id, 0,4)."00";
        $res .= "<select name='menu1' onchange='this.form.submit()'>";
        for($i=0; $i<count($id_menu1); $i++) {
            $sel = "";
            if (isset($_POST['menu1'])) {
                if ($_POST['menu1'] == $id_menu1[$i]) {
                    $sel = "selected = 'selected'";
                    $idtmp= $id_menu1[$i];
                }
            } elseif($id1==$id_menu1[$i]) {
                $idtmp = $id_menu1[$i];
                $sel = "selected = 'selected'";
            } $res .= "<option value='$id_menu1[$i]' $sel>$isi_menu1[$i]</option>";
        } $res .= "</select><br>\n";
        
        if ($idkeg!=$idtmp) {
            $idkeg =$idtmp;
            $qmenu2 = mysqli_query($link, "select menu,id,nama from sister where menu='$param' and parent_id='$idkeg' order by id");
            if (mysqli_num_rows($qmenu2)>0) {
                $id_menu2 = $isi_menu2 = array();
                while ($rmenu = mysqli_fetch_assoc($qmenu2)) {
                    $id_menu2[] = $rmenu['id'];
                    $isi_menu2[]= $rmenu['nama'];
                }
                $idtmp = $id_menu2[0];
                $res .= "<select name='menu2' onchange='this.form.submit()'>";
                for($i=0; $i<count($id_menu2); $i++) {
                    $sel = "";
                    if (isset($_POST['menu2'])) {
                        if ($_POST['menu2'] == $id_menu2[$i]) {
                            $sel = "selected = 'selected'";
                            $idtmp= $id_menu2[$i];
                        }
                    } elseif($id==$id_menu2[$i]) {
                        $idtmp = $id_menu2[$i];
                        $sel = "selected = 'selected'";
                    } $res .= "<option value='$id_menu2[$i]' $sel>$isi_menu2[$i]</option>";
                } $res .= "</select><br>\n";
                
                if ($idkeg!=$idtmp) {
                    $idkeg =$idtmp;
                    $qmenu3 = mysqli_query($link, "select menu,id,nama from sister where menu='$param' and parent_id='$idkeg' order by id");
                    if (mysqli_num_rows($qmenu3)>0) {
                        $id_menu3 = $isi_menu3 = array();
                        while ($rmenu = mysqli_fetch_assoc($qmenu3)) {
                            $id_menu3[] = $rmenu['id'];
                            $isi_menu3[]= $rmenu['nama'];
                        }
                        $idtmp = $id_menu3[0];
                        $res .= "<select name='menu3' onchange='this.form.submit()'>";
                        for($i=0; $i<count($id_menu3); $i++) {
                            $sel = "";
                            if (isset($_POST['menu3'])) {
                                if ($_POST['menu3'] == $id_menu3[$i]) {
                                    $sel = "selected = 'selected'";
                                    $idkeg = $id_menu3[$i];
                                }
                            } elseif($id==$id_menu3[$i]) {
                                $idkeg = $id_menu3[$i];
                                $sel = "selected = 'selected'";
                            } $res .= "<option value='$id_menu3[$i]' $sel>$isi_menu3[$i]</option>";
                        } $res .= "</select><br>\n";
                    } mysqli_free_result($qmenu3);
                }
            } mysqli_free_result($qmenu2);
        }
    } mysqli_free_result($qmenu1);
    $res .= $idkeg;
    return $res;
}

function berkas_dok($link, $dokumen) {
    $dok = "";
    if (is_array($dokumen)) {
        for ($i=0; $i<count($dokumen); $i++) {
            $dok.= "<table><tr><th>Berkas</th><td><input type='file' name='file[]'></td></tr>\n";
            $tmp = $_POST['id_dok'][$i] ?? $dokumen[$i]['id'];
            $dok.= "<tr><th>ID dok</th><td><input type='text' name='id_dok[]' value='$tmp' style='width: 45ch'></td></tr>\n";
            $dok.= "<tr><th>Jenis dok</th><td><select name='jenis_dok[]'>\n";
                $qjd = mysqli_query($link, "select id,nama from sister where menu='jenis_dokumen' and id<>0 order by id");
                if (mysqli_num_rows($qjd)>0) {
                    while ($rjd = mysqli_fetch_assoc($qjd)) {
                        $sel = "";
                        if (isset($_POST['jenis_dok'])) {
                            if ($_POST['jenis_dok'][$i]==$rjd['nama']) {
                                $sel = "selected='selected'";
                            }
                        } elseif ($dokumen[$i]['jenis_dokumen'] == $rjd['nama']) {
                            $sel = "selected='selected'";
                        } $dok.= "<option value='".$rjd['id']."' $sel>".$rjd['nama']."</option>\n";
                    }
                } mysqli_free_result($qjd);
            $dok.= "</select></td></tr>\n";
            $tmp = $_POST['nama_dok'][$i] ?? $dokumen[$i]['nama'];
            $dok.= "<tr><th>Nama dok</th><td><input type='text' name='nama_dok[]' value='$tmp' style='width: 66ch'></td></tr>\n";
            $tmp = $_POST['tautan_dok'][$i] ?? $dokumen[$i]['tautan'];
            $dok.= "<tr><th>Link</th><td><input type='text' name='tautan_dok[]' placeholder='link ke file dokumen' value='$tmp' style='width: 66ch'></td></tr>\n";
            $tmp = $_POST['keterangan_dok'][$i] ?? $dokumen[$i]['keterangan'];
            $dok.= "<tr><th>Keterangan</th><td><input type='text' name='keterangan_dok[]' value='$tmp' style='width: 66ch'></td></tr>\n";
            $dok.= "<tr><td colspan='2'><hr></td></tr></table>";
        }
    } else {
        $dok.= "<table><tr><th>Berkas</th><td><input type='file' name='file'></td></tr>\n";
        $dok.= "<tr><th>ID sdm</th><td><input type='text' name='id_dok' value='$dokumen' readonly='readonly' style='width: 45ch'></td></tr>\n";
        $dok.= "<tr><th>Jenis dok</th><td><select name='jenis_dok'>\n";
        $qjd = mysqli_query($link, "select id,nama from sister where menu='jenis_dokumen' and id<>0 order by id");
        if (mysqli_num_rows($qjd)>0) {
            while ($rjd = mysqli_fetch_assoc($qjd)) {
                $sel = "";
                if (isset($_POST['jenis_dok'])) {
                    if ($_POST['jenis_dok']==$rjd['nama']) {
                        $sel = "selected='selected'";
                    }
                } $dok.= "<option value='".$rjd['id']."' $sel>".$rjd['nama']."</option>\n";
            }
        } mysqli_free_result($qjd);
        $dok.= "</select></td></tr>\n";
        $tmp = $_POST['nama_dok'] ?? "dokumen tester";
        $dok.= "<tr><th>Nama dok</th><td><input type='text' name='nama_dok' value='$tmp' style='width: 66ch'></td></tr>\n";
        $tmp = $_POST['tautan_dok'] ?? "https://sister-api.kemdiktisaintek.go.id/logo.png";
        $dok.= "<tr><th>Link</th><td><input type='text' name='tautan_dok' placeholder='link ke file dokumen' value='$tmp' style='width: 66ch'></td></tr>\n";
        $tmp = $_POST['keterangan_dok'] ?? "keterangan (tester)";
        $dok.= "<tr><th>Keterangan</th><td><input type='text' name='keterangan_dok' value='$tmp' style='width: 66ch'></td></tr>\n";
        $dok.= "<tr><td colspan='2'><hr></td></tr></table>";
    } return $dok;
}

if (isset($_GET['api'])) {
    include '../../koneksi.php';//global $link;
    if ($_GET['api']=="peran") {
        $qapi = mysqli_query($link, "select char(id) as pid,nama from sister where menu='peran' order by id");
        if (mysqli_num_rows($qapi)>0) {
            $data = [];
            while ($rapi=mysqli_fetch_assoc($qapi)) {
                $data[] = $rapi;
            }
        } mysqli_free_result($qapi); 
        echo json_encode($data);
    }
}

//echo menu('anggota_profesi');