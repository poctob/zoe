<?php

namespace Zoe;

namespace Zoe\Lib\PDF2DF;

/**
 * Entity for subclaim, all fields correspond to those in the report.
 * 
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class SubClaim {

    private $refNumber;
    private $serviceDate;
    private $procedure;
    private $billedAmount;
    private $maPayment;
    private $MOD;
    private $allowedCharges;
    private $copayAmount;
    private $title18Payment;
    private $STS;

    public function __construct($args = NULL) {

        $this->refNumber = '';
        $this->serviceDate = NULL;
        $this->procedure = '';
        $this->billedAmount = 0.0;
        $this->maPayment = 0.0;
        $this->MOD = '';
        $this->allowedCharges = 0.0;
        $this->copayAmount = 0.0;
        $this->title18Payment = 0.0;
        $this->STS = '';

        if (isset($args) && is_array($args)) {
            $param = $args['refNumber'];
            if (isset($param) && is_string($param))
                $this->refNumber = $param;

            $param = $args['serviceDate'];
            if (isset($param)) {
                $d = DateTime::createFromFormat('Y-m-d H:i:s', $param);
                if ($d && $d->format('Y-m-d H:i:s')) {
                    $this->serviceDate = new DateTime($param);
                }
            }

            $param = $args['procedure'];
            if (isset($param) && is_string($param))
                $this->procedure = $param;

            $param = $args['billedAmount'];
            if (isset($param) && is_float($param))
                $this->billedAmount = $param;

            $param = $args['maPayment'];
            if (isset($param) && is_float($param))
                $this->maPayment = $param;

            $param = $args['MOD'];
            if (isset($param) && is_string($param))
                $this->MOD = $param;

            $param = $args['allowedCharges'];
            if (isset($param) && is_float($param))
                $this->allowedCharges = $param;

            $param = $args['copayAmount'];
            if (isset($param) && is_float($param))
                $this->copayAmount = $param;

            $param = $args['title18Payment'];
            if (isset($param) && is_float($param))
                $this->title18Payment = $param;

            $param = $args['STS'];
            if (isset($param) && is_string($param))
                $this->STS = $param;
        }
    }

    public function getRefNumber() {
        return $this->refNumber;
    }

    public function getServiceDate() {
        return $this->serviceDate;
    }

    public function getProcedure() {
        return $this->procedure;
    }

    public function getBilledAmount() {
        return $this->billedAmount;
    }

    public function getMaPayment() {
        return $this->maPayment;
    }

    public function getMOD() {
        return $this->MOD;
    }

    public function getAllowedCharges() {
        return $this->allowedCharges;
    }

    public function getCopayAmount() {
        return $this->copayAmount;
    }

    public function getTitle18Payment() {
        return $this->title18Payment;
    }

    public function getSTS() {
        return $this->STS;
    }

    public function setRefNumber($refNumber) {
        $this->refNumber = $refNumber;
    }

    public function setServiceDate($serviceDate) {

        $this->serviceDate = is_null($serviceDate) ? NULL : new \DateTime($serviceDate);
    }

    public function setProcedure($procedure) {
        $this->procedure = $procedure;
    }

    public function setBilledAmount($billedAmount) {
        $this->billedAmount = $billedAmount;
    }

    public function setMaPayment($maPayment) {
        $this->maPayment = $maPayment;
    }

    public function setMOD($MOD) {
        $this->MOD = $MOD;
    }

    public function setAllowedCharges($allowedCharges) {
        $this->allowedCharges = $allowedCharges;
    }

    public function setCopayAmount($copayAmount) {
        $this->copayAmount = $copayAmount;
    }

    public function setTitle18Payment($title18Payment) {
        $this->title18Payment = $title18Payment;
    }

    public function setSTS($STS) {
        $this->STS = $STS;
    }

  
}
