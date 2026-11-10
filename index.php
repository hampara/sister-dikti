<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<title>Dosen :: <?=$_SESSION['login_nama'] ?? ""?></title>
	<link href="sup/sister.css" rel="stylesheet" type="text/css">
	<link href="sup/twhg.png" rel="icon" type="image/png"/>
	<script>
        function copyToken() {
    		var copyText = document.getElementById("token");
    		copyText.select();
    		copyText.setSelectionRange(0, 99999); // For mobile devices
        
    		navigator.clipboard.writeText(copyText.value);
    		//alert("Copied the text:: token sister dikti");
        }
        
    	function doOtorisasi() {
            fetch('exec/authorize.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'todo=otorisasi'
            })
            .then(response => response.json())
            .then(data => {
            	document.getElementById('token').value = data.pesan;
            	document.getElementById('cc').disabled = false;
            	document.getElementById('xx').disabled = false;
            })
            .catch(error => console.error('Error:', error));
        }
    	function clearToken() {
            fetch('exec/authorize.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'sesi=unset'
            })
            .then(response => response.json())
            .then(data => {
            	document.getElementById('token').value = '';
            	document.getElementById('cc').disabled = true;
            	document.getElementById('xx').disabled = true;
            })
            .catch(error => console.error('Error:', error));
        }
        function refreshPage() {
        	location.reload();
        }
        function copyID() {
        	var range = document.createRange();
    		range.selectNode(document.getElementById("iddos"));
            window.getSelection().removeAllRanges();	// clear current selection
            window.getSelection().addRange(range);		// to select text
            document.execCommand("copy");
            window.getSelection().removeAllRanges();	// to deselect
        }
    </script>
</head>
<body><br><br>
<table style="margin: auto;"><tr><td style="padding: 2px 1px"><button id="otorisasi" title="get Token" onclick="doOtorisasi()">authorize</button>&nbsp;<input type="text" name="token" id="token" value="<?=$token ?? ""?>" readonly="readonly" disabled="disabled" style="width: 24ch">&nbsp;<button id="cc" onclick="copyToken()" title="copy Bearer ke clipboard" disabled="disabled">C</button>&nbsp;<button id="xx" onclick="clearToken()" title="clear Bearer" disabled="disabled">X</button>&nbsp;<button id="rr" onclick="refreshPage()" title="refresh">R</button></td></tr>
<?php 
if (isset($_SESSION['token_sister'])) {
    $token = $_SESSION['token_sister'];
    ?>
	<script>
		document.getElementById('token').value = "<?=$token?>";
	    document.getElementById('cc').disabled = false;
	    document.getElementById('xx').disabled = false;
	</script>
	<?php
	include 'exec/ws.php';
	$result = json_decode(wsREF("referensi/sdm"), true); 
	if (count($result)>0) {
	    ?>
	    <tr><td><form action="" method="post"><br><br><select name="main_dosen" onchange="this.form.submit()"><?php
	    $id_sdm = $nama_sdm = "";
	    $a_id_sdm = $a_nama_sdm = array();
	    foreach ($result as $baris) {
	        $a_id_sdm[]   = $baris['id_sdm'];
	        $a_nama_sdm[] = $baris['nama_sdm'];
	    }
        for ($i=0; $i<count($a_id_sdm); $i++) {
            $k = "";
            if (isset($_POST['main_dosen'])) {
                if ($_POST['main_dosen']== $a_id_sdm[$i]) {
                    $k = "selected = 'selected'";
                    $_SESSION['login_nama'] = $nama_sdm = $a_nama_sdm[$i];
                    $_SESSION['login_id']   = $id_sdm   = $a_id_sdm[$i];
                }
            } elseif (isset($_SESSION['login_id'])) {
                if ($_SESSION['login_id']== $a_id_sdm[$i]) {
                    $k = "selected = 'selected'";
                    $id_sdm = $_SESSION['login_id'];
                    $nama_sdm = $_SESSION['login_nama'];
                }
            } else {
                $_SESSION['login_nama'] = $nama_sdm = $a_nama_sdm[0];
                $_SESSION['login_id']   = $id_sdm   = $a_id_sdm[0];
            } echo "<option value='".$a_id_sdm[$i]."' $k>".$a_nama_sdm[$i]."</option>\n";
        }
	    ?></select></form></td></tr> <br><br>
        <tr><td><br><div id="iddos" onclick="copyID();" title="klik kiri copy id_sdm ini ke klipboard"><?=$id_sdm?></div><?=$nama_sdm?><br></td></tr>
	    <?php
	}
}
?>
<tr><td><br><a href="https://sister-api.kemdiktisaintek.go.id/ws.php/1.0#overview" target='blank' title="go-to sumber refferensi">source</a> &nbsp; <a href='sisdos.php' target='_blank'>sister</a> &nbsp; <a href='sisref.php' target='_blank'>refferensi</a></td></tr></table>
</body></html>
