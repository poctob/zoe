<?php

namespace Zoe;

namespace Zoe\Lib\PDF2DF;

/**
 *  Entity representing claim object
 * 
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class Claim {

    private $providerReference;
    private $claimReference;
    private $amountBilled;
    private $title19Payment;
    private $STS;
    private $recipientID;
    private $recipient;
    private $edits;
    private $subclaims;

    /**
     * Default constructor.
     */
    public function __construct($args = NULL) {

        $this->providerReference = '';
        $this->claimReference = '';
        $this->amountBilled = 0.0;
        $this->title19Payment = 0.0;
        $this->STS = '';
        $this->recipientID = '';
        $this->recipient = '';
        $this->edits = '';
        $this->subclaims = array();

        if (isset($args) && is_array($args)) {
            $param = $args['providerReference'];
            if (isset($param) && is_string($param))
                $this->providerReference = $param;

            $param = $args['claimReference'];
            if (isset($param) && is_string($param))
                $this->claimReference = $param;

            $param = $args['amountBilled'];
            if (isset($param) && is_float($param))
                $this->amountBilled = $param;

            $param = $args['title19Payment'];
            if (isset($param) && is_float($param))
                $this->title19Payment = $param;

            $param = $args['STS'];
            if (isset($param) && is_string($param))
                $this->STS = $param;

            $param = $args['recipientID'];
            if (isset($param) && is_string($param))
                $this->recipientID = $param;

            $param = $args['recipient'];
            if (isset($param) && is_string($param))
                $this->recipient = $param;

            $param = $args['edits'];
            if (isset($param) && is_string($param))
                $this->edits = $param;

            $param = $args['subclaims'];
            if (isset($param) && is_array($param))
                $this->subclaims = $param;
        }
    }

    public function getProviderReference() {
        return $this->providerReference;
    }

    public function getClaimReference() {
        return $this->claimReference;
    }

    public function getAmountBilled() {
        return $this->amountBilled;
    }

    public function getTitle19Payment() {
        return $this->title19Payment;
    }

    public function getSTS() {
        return $this->STS;
    }

    public function getRecipientID() {
        return $this->recipientID;
    }

    public function getRecipient() {
        return $this->recipient;
    }

    public function getEdits() {
        return $this->edits;
    }

    public function setProviderReference($providerReference) {
        $this->providerReference = $providerReference;
    }

    public function setClaimReference($claimReference) {
        $this->claimReference = $claimReference;
    }

    public function setAmountBilled($amountBilled) {
        $this->amountBilled = $amountBilled;
    }

    public function setTitle19Payment($title19Payment) {
        $this->title19Payment = $title19Payment;
    }

    public function setSTS($STS) {
        $this->STS = $STS;
    }

    public function setRecipientID($recipientID) {
        $this->recipientID = $recipientID;
    }

    public function setRecipient($recipient) {
        $this->recipient = $recipient;
    }

    public function setEdits($edits) {
        $this->edits = $edits;
    }

    public function getSubclaims() {
        return $this->subclaims;
    }

    public function setSubclaims($subclaims) {
        $this->subclaims = $subclaims;
    }

    /**
     * Adds subclaim to a list
     * @param \Zoe\Lib\PDF2DF\SubClaim $subclaim
     * @return boolean Returns true on success
     */
    public function addSubclaim($subclaim) {
        if (isset($subclaim) && ($subclaim instanceof \Zoe\Lib\PDF2DF\SubClaim)) {
            $this->subclaims[] = $subclaim;
            return true;
        }
        return false;
    }

    /**
     * Removes subclaim from the list
     * @param \Zoe\Lib\PDF2DF\SubClaim $subclaim
     * @return boolean Return true on success
     */
    public function removeSubclaim($subclaim) {
        if (isset($subclaim) && ($subclaim instanceof \Zoe\Lib\PDF2DF\SubClaim)) {
            if (($key = array_search($subclaim, $subclaims)) !== false) {
                unset($subclaims[$key]);
                return true;
            }
        }
        return false;
    }

    public function getLastName() {
        $location = strrpos($this->recipient, ' ');
        $lastname = substr($this->recipient, $location + 1);

        if ($lastname == 'JR' || $lastname == 'SR') {
            $location = strrpos($this->recipient, $lastname);
            $lastname = substr($this->recipient, 0, $location - 1);
            $location = strrpos(trim($lastname), ' ');
            $lastname = substr($lastname, $location + 1);
        }
        return trim($lastname);
    }

}
