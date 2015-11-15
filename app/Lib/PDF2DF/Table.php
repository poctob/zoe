<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Zoe\Lib\PDF2DF;

/**
 * Table object representation.
 * @author Alex Pavlunenko <alexp@xpresstek.net>
 */
class Table {

    /**
     * Table header.
     */
    private $header;

    /**
     * Table footer.
     */
    private $footer;

    /**
     * Table columns.
     */
    private $columns;

    /**
     * Excel writer object.
     */
    private $xwriter;

    /**
     * List of claims.
     */
    private $claims;

    /**
     * Column map.
     */
    private $col_map;

    /**
     * Alertable object to propagate exceptions.
     */
    private $alert;

    /**
     * Default constructor.
     * @param \Zoe\Lib\PDF2DF\iExcel $excel Output file name
     * @param \Zoe\Lib\PDF2DF\iAlert $alert iAlert object
     */
    public function __construct(iExcel $excel, iAlert $alert) {
        $this->header = 'None';
        $this->footer = 'None';
        $this->columns = array();
        $this->xwriter = $excel;
        $this->claims = array();
        $this->col_map = config('zoe.columns');
        $this->alert = $alert;

        if (is_null($this->col_map) || count($this->col_map) == 0) {
            if (isset($this->alert)) {
                $this->alert->showAlert('Unable to initialize properties file!',
                        'Converter Error', 'ERROR_MESSAGE');
            }
        }
    }

    /**
     * getter
     * @return string
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * getter
     * @return string
     */
    public function getFooter() {
        return $this->footer;
    }

    /**
     * getter
     * @return array
     */
    public function getColumns() {
        return $this->columns;
    }

    /**
     * setter
     * @param string $header
     */
    public function setHeader($header) {
        $this->header = $header;
    }

    /**
     * setter
     * @param string $footer
     */
    public function setFooter($footer) {
        $this->footer = $footer;
    }

    /**
     * Setter
     * @param array $columns
     */
    public function setColumns() {
        foreach ($this->col_map as
                $column) {
            if ($column['WIDTH'] > 0) {
                $col = new Column();
                $col->setWidth($column['WIDTH']);
                $col->setHeader($column['TITLE']);
                $this->addColumn($col);
            }
        }
    }

    /**
     * Adds a single column to the array
     * @param \Zoe\Lib\PDF2DF\Column $column
     */
    public function addColumn(Column $column) {
        if (isset($column)) {
            $this->columns[] = $column;
        }
    }

    /**
     * Add column to the list.
     * @param int Column width.
     * @param string Column header.
     */
    public function addColumnByWidthAndHeader($width, $header) {
        $column = new Column();
        $column->setHeader($header);
        $column->setWidth($width);
        $this->addColumn($column);
    }

    /**
     * Adds row to the table.
     * @param array row List of cell values for the row.
     */
    public function addRow($row) {

        $index = $this->col_map['MAX']['POSITION'];
        if (isset($row) && count($row) == $index) {
            $index = $this->col_map['RECIPIENT_ID']['POSITION'];
            $recipientId = $row[$index];

            if (isset($recipientId)) {
                $rid = trim($recipientId);

                if (strlen($rid) > 0) {
                    $this->addClaim($row);
                } else {

                    $this->addSubClaim($row);
                }
            }
        }
    }

    /**
     * Adds a claim to the list.
     * @param array Row from which the claim will be extracted.
     */
    private function addClaim($row) {
        $claim = new Claim();

        $index = $this->col_map['PROVIDER_REFERENCE']['POSITION'];
        $data = $row[$index];
        $claim->setProviderReference($this->checkAndTrim($data));

        $index = $this->col_map['CLAIM_REFERENCE']['POSITION'];
        $data = $row[$index];
        $claim->setClaimReference($this->checkAndTrim($data));

        $index = $this->col_map['AMOUNT_BILLED']['POSITION'];
        $data = $row[$index];
        $amount = \Zoe\Lib\util\Converter::convertCurrency($this->checkAndTrim($data));
        $claim->setAmountBilled($amount);

        $index = $this->col_map['TITLE19_PAYMENT_MA']['POSITION'];
        $data = $row[$index];
        $amount = \Zoe\Lib\util\Converter::convertCurrency($this->checkAndTrim($data));
        $claim->setTitle19Payment($amount);

        $index = $this->col_map['STS']['POSITION'];
        $data = $row[$index];
        $claim->setSTS($this->checkAndTrim($data));

        $index = $this->col_map['RECIPIENT_ID']['POSITION'];
        $data = $row[$index];
        $claim->setRecipientID($this->checkAndTrim($data));

        $index = $this->col_map['RECIPIENT_NAME']['POSITION'];
        $data = $row[$index];
        $claim->setRecipient($this->checkAndTrim($data));

        $this->claims[] = $claim;
    }

    /**
     * Adds subclaim to the claim.
     * @param array Row with subclaim data.
     */
    private function addSubClaim($row) {

        $claims_size = count($this->claims);

        if ($claims_size == 0) {
            return;
        }

        $claim = $this->claims[$claims_size - 1];
        $sc = new SubClaim();

        $index = $this->col_map['CLAIM_REFERENCE']['POSITION'];
        $data = $row[$index];

        if (strlen($this->checkAndTrim($data)) == 0) {
            return;
        }

        $sc->setRefNumber($claim->getClaimReference() . ' ' . $this->checkAndTrim($data));

        $index = $this->col_map['SERVICE_DATE']['POSITION'];
        $data = $row[$index];
        $sc->setServiceDate(\Zoe\Lib\util\Converter::ConvertDate($this->checkAndTrim($data)));

        if (is_null($sc->getServiceDate())) {
            return;
        }

        $index = $this->col_map['RENDERED_PROC']['POSITION'];
        $data = $row[$index];
        $sc->setProcedure($this->checkAndTrim($data));

        $index = $this->col_map['AMOUNT_BILLED']['POSITION'];
        $data = $row[$index];
        $sc->setBilledAmount(\Zoe\Lib\util\Converter::convertCurrency($this->checkAndTrim($data)));

        $index = $this->col_map['TITLE19_PAYMENT_MA']['POSITION'];
        $data = $row[$index];
        $sc->setMaPayment(\Zoe\Lib\util\Converter::convertCurrency($this->checkAndTrim($data)));

        $index = $this->col_map['STS']['POSITION'];
        $data = $row[$index];
        $sc->setSTS($this->checkAndTrim($data));

        $index = $this->col_map['MOD']['POSITION'];
        $data = $row[$index];
        $sc->setMOD($this->checkAndTrim($data));

        $index = $this->col_map['TITLE18_ALLOWED_CHARGES']['POSITION'];
        $data = $row[$index];
        $sc->setAllowedCharges(\Zoe\Lib\util\Converter::convertCurrency($this->checkAndTrim($data)));

        $index = $this->col_map['COPAY_AMT']['POSITION'];
        $data = $row[$index];
        $sc->setCopayAmount(\Zoe\Lib\util\Converter::convertCurrency($this->checkAndTrim($data)));

        $index = $this->col_map['TITLE_18_PAYMENT']['POSITION'];
        $data = $row[$index];
        $sc->setTitle18Payment(\Zoe\Lib\util\Converter::convertCurrency($this->checkAndTrim($data)));

        $claim->addSubclaim($sc);
    }

    /**
     * Utility method to check and trim a string.
     * @param string String to trim.
     * @return Resulting string.
     */
    private function checkAndTrim($st) {
        $ret = '';
        if (isset($st) && is_string($st)) {
            $ret = trim($st);
        }
        return $ret;
    }

    /**
     * Compares two Claims
     * @param \Zoe\Lib\PDF2DF\Claim $a LHS
     * @param \Zoe\Lib\PDF2DF\Claim $b RHS
     * @return -1 if a is smaller than b, 0 if equal, 1 if a is larger than b
     */
    public function cmp(Claim $a, Claim $b) {
        $result = strcmp($a->getLastName(), $b->getLastName());
        if($result == 0)
        {
            $result = strcmp($a->getRecipientID(), $b->getRecipientID());            
        }
        return $result;
    }       

    /**
     * Exports table to excel.
     */
    public function exportToXLS() {

        if (isset($this->xwriter)) {

            usort($this->claims, array($this, "cmp"));

            foreach ($this->columns as
                    $c) {
                $this->xwriter->addHeader($c->getHeader(), $c->getWidth() + 5);
            }

            $rows = 2;
            foreach ($this->claims as
                    $claim) {
                                           
                $claim->sortSubClaimsByDate();                
                $rows += $this->exportClaim($claim, $rows);
            }

            $this->xwriter->writeToDisk();
        }
    }

    /**
     * Add a claim to the excel spreadsheet.
     * @param Claim Claim to export.
     * @param int Row id.
     * @return Number of rows used by the claim.
     */
    private function exportClaim(Claim $claim, $row) {

        $rows_used = 0;
        $current_row = $row;
        if (isset($this->xwriter) && isset($claim)) {

            foreach ($claim->getSubclaims() as
                    $sc) {
                if (isset($sc)) {

                    $this->xwriter->addString(
                            $claim->getProviderReference(), $current_row,
                            $this->col_map['PROVIDER_REFERENCE']['POSITION']);

                    $this->xwriter->addString(
                            $sc->getRefNumber(), $current_row,
                            $this->col_map['CLAIM_REFERENCE']['POSITION']);

                    $this->xwriter->addDate(
                            $sc->getServiceDate(), $current_row,
                            $this->col_map['SERVICE_DATE']['POSITION']);

                    $this->xwriter->addString(
                            $sc->getProcedure(), $current_row,
                            $this->col_map['RENDERED_PROC']['POSITION']);

                    $this->xwriter->addCurrency(
                            $sc->getBilledAmount(), $current_row,
                            $this->col_map['AMOUNT_BILLED']['POSITION']);

                    $this->xwriter->addCurrency(
                            $sc->getMaPayment(), $current_row,
                            $this->col_map['TITLE19_PAYMENT_MA']['POSITION']);

                    $this->xwriter->addString(
                            $sc->getSTS(), $current_row,
                            $this->col_map['STS']['POSITION']);

                    $this->xwriter->addString(
                            $claim->getRecipientID(), $current_row,
                            $this->col_map['RECIPIENT_ID']['POSITION']);

                    $this->xwriter->addString(
                            $claim->getRecipient(), $current_row,
                            $this->col_map['RECIPIENT_NAME']['POSITION']);

                    $this->xwriter->addString(
                            $sc->getMOD(), $current_row,
                            $this->col_map['MOD']['POSITION']);

                    $this->xwriter->addCurrency(
                            $sc->getAllowedCharges(), $current_row,
                            $this->col_map['TITLE18_ALLOWED_CHARGES']['POSITION']);

                    $this->xwriter->addCurrency(
                            $sc->getCopayAmount(), $current_row,
                            $this->col_map['COPAY_AMT']['POSITION']);

                    $this->xwriter->addCurrency(
                            $sc->getTitle18Payment(), $current_row,
                            $this->col_map['TITLE_18_PAYMENT']['POSITION']);

                    $rows_used++;
                    $current_row++;
                }
            }
        }
        return $rows_used;
    }

}
