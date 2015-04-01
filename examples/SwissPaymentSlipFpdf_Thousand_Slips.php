<?php
/**
 * Swiss Payment Slip FPDF
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright 2012-2015 Some nice Swiss guys
 * @author Marc Würth <ravage@bluewin.ch>
 * @author Manuel Reinhard <manu@sprain.ch>
 * @author Peter Siska <pesche@gridonic.ch>
 * @link https://github.com/ravage84/SwissPaymentSlipFpdf
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SwissPaymentSlipFpdf Example 03: Create one thousand orange payment slips</title>
</head>
<body>
<h1>SwissPaymentSlipFpdf Example 03: Create one thousand orange payment slips</h1>
<?php
// Measure script execution/generating time
$time_start = microtime(true);

// Make sure the classes get auto-loaded
require __DIR__ . '/../vendor/autoload.php';

// Import necessary classes
use SwissPaymentSlip\SwissPaymentSlip\OrangePaymentSlipData;
use SwissPaymentSlip\SwissPaymentSlip\OrangePaymentSlip;
use SwissPaymentSlip\SwissPaymentSlipFpdf\PaymentSlipFpdf;
use fpdf\FPDF;

// Make sure FPDF has access to the additional fonts
define('FPDF_FONTPATH', __DIR__ . '/../src/Resources/font');

// Create an instance of FPDF, setup default settings
$fPdf = new FPDF('P', 'mm', 'A4');

// Add OCRB font to FPDF
$fPdf->AddFont('OCRB10');

// create 1000 payment slips
for ($slipNr = 1; $slipNr <= 1000; $slipNr++) {
    // Add page, don't break page automatically
    $fPdf->AddPage();
    $fPdf->SetAutoPageBreak(false);

    // Insert a dummy invoice text, not part of the payment slip itself
    $fPdf->SetFont('Helvetica', '', 9);
    $fPdf->Cell(50, 4, "Just some dummy text.");

    // Create a payment slip data container (value object)
    $paymentSlipData = new OrangePaymentSlipData();

    // Fill the data container with your data
    $paymentSlipData->setBankData('Seldwyla Bank', '8001 Zürich');
    $paymentSlipData->setAccountNumber('01-145-6');
    $paymentSlipData->setRecipientData('H. Muster AG', 'Versandhaus', 'Industriestrasse 88', '8000 Zürich');
    $paymentSlipData->setPayerData('Rutschmann Pia', 'Marktgasse 28', '9400 Rorschach', 'Slip # ' . $slipNr);
    $paymentSlipData->setAmount(2830.50);
    $paymentSlipData->setReferenceNumber('7520033455900012');
    $paymentSlipData->setBankingCustomerId('215703');

    // Create a payment slip object, pass in the prepared data container
    $paymentSlip = new OrangePaymentSlip($paymentSlipData, 0, 191);

    // Create an instance of the FPDF implementation
    $paymentSlipFpdf = new PaymentSlipFpdf($fPdf, $paymentSlip);

    // "Print" the slip with its elements according to their attributes
    $paymentSlipFpdf->createPaymentSlip($paymentSlip);
}

// Output PDF named example_fpdf_thousand_slips.pdf to examples folder
$pdfName = 'example_fpdf_thousand_slips.pdf';
$pdfPath = __DIR__ . DIRECTORY_SEPARATOR . $pdfName;
$fPdf->Output($pdfPath, 'F');

echo sprintf('Payment slips created in <a href="%s">%s</a><br>', $pdfName, $pdfPath);

echo "<br>";

$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Generation took $time seconds <br>";
echo 'Peak memory usage: ' . memory_get_peak_usage() / 1024 / 1024;
?>
</body>
</html>
