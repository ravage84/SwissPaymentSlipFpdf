<?php
/**
 * Swiss Payment Slip TCPDF
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright 2012-2015 Some nice Swiss guys
 * @author Marc Würth <ravage@bluewin.ch>
 * @author Manuel Reinhard <manu@sprain.ch>
 * @author Peter Siska <pesche@gridonic.ch>
 * @link https://github.com/ravage84/SwissPaymentSlipFpdf
 */

namespace SwissPaymentSlip\SwissPaymentSlipFpdf;

use SwissPaymentSlip\SwissPaymentSlipPdf\PaymentSlipPdf;
use fpdf\FPDF;

/**
 * Responsible for generating standard Swiss payment Slips using FPDF as engine.
 * Layouting done by utilizing SwissPaymentSlip
 * Data organisation through SwissPaymentSlipData
 */
class PaymentSlipFpdf extends PaymentSlipPdf
{
	protected $rgbColors = array();

	/**
	 * The PDF engine object to generate the PDF output
	 *
	 * @var null|FPDF The PDF engine object
	 */
	protected $pdfEngine = null;

	/**
	 * @var string;
	 */
	protected  $lastFontFamily = '';

	/**
	 * @var int | double;
	 */
	protected  $lastFontSize = '';

	/**
	 * @var string;
	 */
	protected  $lastFontColor = '';

	/**
	 * @param $background
	 * @return mixed|void
	 */
	protected function displayImage($background) {
		// TODO check if slipBackground is a color or a path to a file

		$this->pdfEngine->Image($background,
			$this->paymentSlip->getSlipPosX(),
			$this->paymentSlip->getSlipPosY(),
			$this->paymentSlip->getSlipWidth(),
			$this->paymentSlip->getSlipHeight(),
			strtoupper(substr($background, -3, 3)));
	}

	/**
	 * @param $fontFamily
	 * @param $fontSize
	 * @param $fontColor
	 * @return mixed|void
	 */
	protected function setFont($fontFamily, $fontSize, $fontColor) {
		if ($fontColor) {
			if ($this->lastFontColor != $fontColor) {
				$this->lastFontColor = $fontColor;

				$rgbArray = $this->convertColor2Rgb($fontColor);
				$this->pdfEngine->SetTextColor($rgbArray['red'], $rgbArray['green'], $rgbArray['blue']);
			}
		}
		if ($this->lastFontFamily != $fontFamily || $this->lastFontSize != $fontSize) {
			$this->lastFontFamily = $fontFamily;
			$this->lastFontSize = $fontSize;

			$this->pdfEngine->SetFont($fontFamily, '', $fontSize);
		}
	}

	/**
	 * @param $background
	 * @return mixed|void
	 */
	protected function setBackground($background) {
		// TODO check if it's a path to a file
		// TODO else it should be a color
		$rgbArray = $this->convertColor2Rgb($background);
		$this->pdfEngine->SetFillColor($rgbArray['red'], $rgbArray['green'], $rgbArray['blue']);
	}

	/**
	 * @param $posX
	 * @param $posY
	 * @return mixed|void
	 */
	protected function setPosition($posX, $posY) {
		$this->pdfEngine->SetXY($posX, $posY);
	}

	/**
	 * @param $width
	 * @param $height
	 * @param $line
	 * @param $textAlign
	 * @param $fill
	 * @return mixed|void
	 */
	protected function createCell($width, $height, $line, $textAlign, $fill) {
		$this->pdfEngine->Cell($width, $height, utf8_decode($line), 0, 0, $textAlign, $fill);
	}

	/**
	 * @param $color
	 * @return mixed
	 */
	protected function convertColor2Rgb($color) {
		if (isset($this->rgbColors[$color])) {
			return $this->rgbColors[$color];
		}
		$this->rgbColors[$color] = $this->hex2RGB($color);
		return $this->rgbColors[$color];
	}

	/**
	 * Convert hexadecimal values into an array of RGB
	 *
	 * @param $hexStr
	 * @param bool $returnAsString
	 * @param string $separator
	 * @return array|bool|string
	 *
	 * @copyright 2010 hafees at msn dot com
	 * @link http://www.php.net/manual/en/function.hexdec.php#99478
	 */
	private function hex2RGB($hexStr, $returnAsString = false, $separator = ',') {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		return $returnAsString ? implode($separator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
	}
}
