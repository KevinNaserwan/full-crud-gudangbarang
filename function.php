<?php 
session_start();
// membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stockbarang");

//Menambah barang baru
if(isset($_POST['addnewbarang'])) {
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "insert into stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
    if($addtotable) {
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
};

//menambah barang masuk
if(isset($_POST['barangmasuk'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstoksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildatanya['stock'];
    $tambah = $stoksekarang+$qty;

    $addtomasuk = mysqli_query($conn, "insert into masuk (idbarang, keterangan, qty) values ('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock = '$tambah' where idbarang = '$barangnya'");
    if($addtomasuk&&$updatestockmasuk) {
        header('location:masuk.php');
    } else {
        echo 'gagal';
        header('location:masuk.php');
    }
}

//menambah barang keluar
if(isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstoksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildatanya['stock'];
    $tambah = $stoksekarang-$qty;

    $addtomasuk = mysqli_query($conn, "insert into keluar (idbarang, penerima, qty) values ('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "update stock set stock = '$tambah' where idbarang = '$barangnya'");
    if($addtomasuk&&$updatestockmasuk) {
        header('location:keluar.php');
    } else {
        echo 'gagal';
        header('location:keluar.php');
    }
}


//update info barang
if(isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "update stock set namabarang = '$namabarang', deskripsi = '$deskripsi' where idbarang = '$idb'");
    if($update) {
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
}

//delete info barang
if(isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "delete from stock where idbarang = '$idb'");
    if($hapus) {
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
}

//mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang = '$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stoksekarang = $stocknya['stock'];
    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk = '$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangi = $stoksekarang + $selisih;
        $kurangstock = mysqli_query($conn, "update stock set stock = '$kurangi' where idbarang = '$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty = '$qty', keterangan = '$deskripsi' where idmasuk = '$idm'");
            if($kurangstock&&$updatenya) {
                header('location:masuk.php');
            } else {
                echo 'gagal';
                header('location:masuk.php');
            }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangi = $stoksekarang - $selisih;
        $kurangstock = mysqli_query($conn, "update stock set stock = '$kurangi' where idbarang = '$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty = '$qty', keterangan = '$deskripsi' where idmasuk = '$idm'");
            if($kurangstock&&$updatenya) {
                header('location:masuk.php');
            } else {
                echo 'gagal';
                header('location:masuk.php');
            }
    }
}

//menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang = '$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok-$qty;
    $update = mysqli_query($conn, "update stock set stock = '$selisih' where idbarang = '$idb'");
    $hapusdata = mysqli_query($conn, "delete from masuk where idmasuk = '$idm'");

    if($update&&$hapusdata) {
        header('location:masuk.php');
    } else {
        echo 'gagal';
        header('location:masuk.php');
    }
}

//mengubah data barang keluar 
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang = '$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stoksekarang = $stocknya['stock'];
    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar = '$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangi = $stoksekarang - $selisih;
        $kurangstock = mysqli_query($conn, "update stock set stock = '$kurangi' where idbarang = '$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty = '$qty', penerima = '$penerima' where idkeluar = '$idk'");
            if($kurangstock&&$updatenya) {
                header('location:keluar.php');
            } else {
                echo 'gagal';
                header('location:keluar.php');
            }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangi = $stoksekarang + $selisih;
        $kurangstock = mysqli_query($conn, "update stock set stock = '$kurangi' where idbarang = '$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty = '$qty', penerima = '$penerima' where idkeluar = '$idk'");
            if($kurangstock&&$updatenya) {
                header('location:keluar.php');
            } else {
                echo 'gagal';
                header('location:keluar.php');
            }
    }
}

//menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "select * from stock where idbarang = '$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok+$qty;
    $update = mysqli_query($conn, "update stock set stock = '$selisih' where idbarang = '$idb'");
    $hapusdata = mysqli_query($conn, "delete from keluar where idkeluar = '$idk'");

    if($update&&$hapusdata) {
        header('location:keluar.php');
    } else {
        echo 'gagal';
        header('location:keluar.php');
    }
}
?>