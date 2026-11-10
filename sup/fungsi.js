var d = <?=$jum_dok?>;
function add_doks() {
	var itemlist = document.getElementById('wadah_dok');
	
	// membuat element
    var tabel = document.createElement('table');
    
    // baris 1
    var baris1 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Berkas";
	baris1.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var berkas_dok = document.createElement('input');
    berkas_dok.setAttribute('name', 'file[' + d + ']');
    kolom2.appendChild(berkas_dok);
    baris1.appendChild(kolom2);
	tabel.appendChild(baris1);
	
    // baris 2
    var baris2 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "ID";
	baris2.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var id_dok_input = document.createElement('input');
    id_dok_input.setAttribute('name', 'id_dok[' + d + ']');
    id_dok_input.style.width = "66ch";
    id_dok_input.value = "";
    kolom2.appendChild(id_dok_input);
    baris2.appendChild(kolom2);
	tabel.appendChild(baris2);
	
    // baris 3
    var baris3 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Nama";
	baris3.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var id_penulis_input = document.createElement('input');
    id_penulis_input.setAttribute('name', 'nama_dok[' + d + ']');
    id_penulis_input.style.width = "66ch";
    kolom2.appendChild(id_penulis_input);
    baris3.appendChild(kolom2);
    tabel.appendChild(baris3);
	
    // baris 4
    var baris4 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Tautan";
	baris4.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var tautan_input = document.createElement('input');
    tautan_input.setAttribute('name', 'tautan_dok[' + d + ']');
    tautan_input.style.width = "66ch";
    kolom2.appendChild(tautan_input);
    baris4.appendChild(kolom2);
	tabel.appendChild(baris4);
	
    // baris 5
    var baris5 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Keterangan";
	baris5.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var keterangan_input = document.createElement('input');
    keterangan_input.setAttribute('name', 'keterangan_dok[' + d + ']');
    keterangan_input.style.width = "66ch";
    kolom2.appendChild(keterangan_input);
    baris5.appendChild(kolom2);
	tabel.appendChild(baris5);
	
    // baris 6
    var baris6 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Jenis dok";
	baris6.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var jenis_dok_select = document.createElement('select');
    jenis_dok_select.setAttribute('name', 'jenis_dok[' + d + ']');

	fetch('menu.php?api=jenis_dokumen')
        .then(response => response.json())
        .then(data => {
            // Loop data dari database dan buat tag <option> baru
            data.forEach(item => {
                let option = document.createElement("option");
                option.value = item.id;   // Nilai dari kolom ID di SQL
                option.textContent = item.nama; // Teks yang tampil dari kolom nama di SQL
                peran_input.appendChild(option); // Masukkan ke dalam select
            });
        })
        .catch(error => {
            //console.error("Gagal mengambil data:", error);
            jenis_dok_select.innerHTML = '<option value="">Gagal memuat data</option>';
        });

    kolom2.appendChild(jenis_dok_select);
    baris6.appendChild(kolom2);
	
	var aksi = document.createElement('td');
    baris6.appendChild(aksi);
	tabel.appendChild(baris6);
	
    // baris spasi
    var baris0 = document.createElement('tr');
    var kolom0 = document.createElement("td");
	kolom0.setAttribute('colspan', '3');
	kolom0.innerHTML = "<hr>";
	baris0.appendChild(kolom0);
	tabel.appendChild(baris0);

	itemlist.appendChild(tabel);

    var hapus = document.createElement('span');
	aksi.appendChild(hapus);
	
    hapus.innerHTML = '<button><b>-</b></button>';
	hapus.onclick = function () {
        tabel.parentNode.removeChild(tabel);
    };

    i++;
}

var i = <?=$jumpenulis?>;
function additem() {
    var itemlist = document.getElementById('wadah');
    
	// membuat element
    var tabel = document.createElement('table');
    
    // baris 1
    var baris1 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Jenis";
	baris1.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var jenis_penulis_select = document.createElement('select');
    jenis_penulis_select.setAttribute('name', 'jenis_penulis[' + i + ']');
    
    const jp = ["Dosen","Mahasiswa","Profesional/Mitra"];
    jp.forEach((item, index) => {
    	const option = document.createElement("option");
    	option.value = item;
    	option.textContent = item;
    	jenis_penulis_select.appendChild(option);
	});
    
    kolom2.appendChild(jenis_penulis_select);
    baris1.appendChild(kolom2);
	tabel.appendChild(baris1);
	
    // baris 2
    var baris2 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Nama";
	baris2.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var nama_penulis_input = document.createElement('input');
    nama_penulis_input.setAttribute('name', 'nama_penulis[' + i + ']');
    nama_penulis_input.style.width = "66ch";
    nama_penulis_input.value = "";
    kolom2.appendChild(nama_penulis_input);
    baris2.appendChild(kolom2);
	tabel.appendChild(baris2);
	
    // baris 3
    var baris3 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "ID penulis";
	baris3.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var id_penulis_input = document.createElement('input');
    id_penulis_input.setAttribute('name', 'id_sdm[' + i + ']');
    id_penulis_input.style.width = "66ch";
    kolom2.appendChild(id_penulis_input);
    baris3.appendChild(kolom2);
    tabel.appendChild(baris3);
	
    // baris 4
    var baris4 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Urutan";
	baris4.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var urutan_input = document.createElement('input');
    urutan_input.setAttribute('name', 'no_urut[' + i + ']');
    urutan_input.type = "number";
    urutan_input.min = "1";
    urutan_input.max = "99";
    urutan_input.value = i+1;
    kolom2.appendChild(urutan_input);
    baris4.appendChild(kolom2);
	tabel.appendChild(baris4);
	
    // baris 5
    var baris5 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Afiliasi";
	baris5.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var afiliasi_input = document.createElement('input');
    afiliasi_input.setAttribute('name', 'afiliasi[' + i + ']');
    afiliasi_input.style.width = "66ch";
    kolom2.appendChild(afiliasi_input);
    baris5.appendChild(kolom2);
	tabel.appendChild(baris5);
	
    // baris 6
    var baris6 = document.createElement('tr');
    var kolom1 = document.createElement("th");
	kolom1.textContent = "Peran";
	baris6.appendChild(kolom1);
	
	var kolom2 = document.createElement("td");
    var peran_input = document.createElement('select');
    peran_input.setAttribute('name', 'peran[' + i + ']');

	fetch('menu.php?api=peran')
        .then(response => response.json())
        .then(data => {
            // Loop data dari database dan buat tag <option> baru
            data.forEach(item => {
                let option = document.createElement("option");
                option.value = item.pid;   // Nilai dari kolom ID di SQL
                option.textContent = item.nama; // Teks yang tampil dari kolom nama di SQL
                peran_input.appendChild(option); // Masukkan ke dalam select
            });
        })
        .catch(error => {
            //console.error("Gagal mengambil data:", error);
            peran_input.innerHTML = '<option value="">Gagal memuat data</option>';
        });

    kolom2.appendChild(peran_input);
    baris6.appendChild(kolom2);
	
	var aksi = document.createElement('td');
    baris6.appendChild(aksi);
	tabel.appendChild(baris6);
	
    // baris spasi
    var baris0 = document.createElement('tr');
    var kolom0 = document.createElement("td");
	kolom0.setAttribute('colspan', '3');
	kolom0.innerHTML = "<hr>";
	baris0.appendChild(kolom0);
	tabel.appendChild(baris0);

	itemlist.appendChild(tabel);

    var hapus = document.createElement('span');
	aksi.appendChild(hapus);
	
    hapus.innerHTML = '<button><b>-</b></button>';
	hapus.onclick = function () {
        tabel.parentNode.removeChild(tabel);
    };

    i++;
}
