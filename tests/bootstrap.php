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

namespace SwissPaymentSlip\SwissPaymentSlipFpdf\Tests;

use SwissPaymentSlip\SwissPaymentSlip\PaymentSlip;
use SwissPaymentSlip\SwissPaymentSlip\PaymentSlipData;

// Include Composer's autoloader
require __DIR__.'/../vendor/autoload.php';

/**
 * A wrapping class to allow testing the abstract class PaymentSlipData
 */
class TestablePaymentSlipData extends PaymentSlipData
{
}

/**
 * A wrapping class to allow testing the abstract class PaymentSlip
 */
class TestablePaymentSlip extends PaymentSlip
{
}
