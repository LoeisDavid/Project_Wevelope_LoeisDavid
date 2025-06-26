<?php
require_once __DIR__ . '/../vendor/autoload.php';// Pastikan path sesuai

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Fungsi untuk generate PDF dari file HTML.
 *
 * @param string $htmlFile  Nama file HTML (contoh: 'invoice_print.php')
 * @param string $outputName Nama file PDF hasil (tanpa .pdf)
 * @param bool   $download   True = download, False = tampil di browser
 */
function generatePDF($htmlFile, $outputName = "invoice", $download = true)
{
    // Siapkan HTML dari file
    ob_start();
    include $htmlFile;
    $html = ob_get_clean();

    // Inisialisasi opsi Dompdf (jika diperlukan)
    $options = new Options();
    $options->set('isRemoteEnabled', true); // jika ada gambar eksternal
    $dompdf = new Dompdf($options);

    // Load HTML
    $dompdf->loadHtml($html);

    // Ukuran kertas dan orientasi
    $dompdf->setPaper('A4', 'portrait');

    // Render
    $dompdf->render();

    // Stream PDF ke browser
    $dompdf->stream($outputName . ".pdf", ["Attachment" => $download]);
}
