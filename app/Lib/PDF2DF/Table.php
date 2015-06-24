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
        if (is_null($this->col_map) || isEmpty($this->col_map)) {
            if (isset($this->alert)) {
                $this->alert->showAlert('Unable to initialize properties file!', 'Converter Error', 'ERROR_MESSAGE');
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
    public function setColumns($columns) {
        $this->columns = $columns;
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

        $index = $this->col_map['MAX'];
        if (isset($row) && count($row) == $index) {
            $index = $this->col_map['RECIPIENT_ID'];
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
        $claim = new \Zoe\Claim();

        $index = $this->col_map['PROVIDER_REFERENCE'];
        $data = $row[$index];
        $claim->providerReference = $this->checkAndTrim($data);

        $index = $this->col_map['CLAIM_REFERENCE'];
        $data = $row[$index];
        $claim->claimReference = $this->checkAndTrim($data);

        $index = $this->col_map['AMOUNT_BILLED'];
        $data = $row[$index];
        $amount = Converter::convertCurrency($this->checkAndTrim($data));
        $claim->amountBilled = $amount;

        $index = $this->col_map['TITLE19_PAYMENT_MA'];
        $data = $row[$index];
        $amount = Converter::convertCurrency($this->checkAndTrim($data));
        $claim->title19Payment = $amount;

        $index = $this->col_map['STS'];
        $data = $row[$index];
        $claim->STS = $this->checkAndTrim($data);

        $index = $this->col_map['RECIPIENT_ID'];
        $data = $row[$index];
        $claim->recipientID = $this->checkAndTrim($data);

        $index = $this->col_map['RECIPIENT_NAME'];
        $data = $row[$index];
        $claim->recipient = $this->checkAndTrim($data);

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
        $sc = new \Zoe\SubClaim();

        $index = $this->col_map['CLAIM_REFERENCE'];
        $data = $row[$index];

        if (strlen($this->checkAndTrim($data)) == 0) {
            return;
        }

        $sc->refNumber = $claim->claimReference . ' ' . $this->checkAndTrim($data);

        $index = $this->col_map['SERVICE_DATE'];
        $data = $row[$index];
        $sc->serviceDate = Converter::ConvertDate($this->checkAndTrim($data));

        $index = $this->col_map['RENDERED_PROC'];
        $data = $row[$index];
        $sc->procedure = $this->checkAndTrim($data);

        $index = $this->col_map['AMOUNT_BILLED'];
        $data = $row[$index];
        $sc->billedAmount = Converter::convertCurrency($this->checkAndTrim($data));

        $index = $this->col_map['TITLE19_PAYMENT_MA'];
        $data = $row[$index];
        $sc->maPayment = Converter::convertCurrency($this->checkAndTrim($data));

        $index = $this->col_map['STS'];
        $data = $row[$index];
        $sc->STS = $this->checkAndTrim($data);

        $index = $this->col_map['MOD'];
        $data = $row[$index];
        $sc->MOD = $this->checkAndTrim($data);

        $index = $this->col_map['TITLE18_ALLOWED_CHARGES'];
        $data = $row[$index];
        $sc->allowedCharges = Converter::convertCurrency($this->checkAndTrim($data));

        $index = $this->col_map['COPAY_AMT'];
        $data = $row[$index];
        $sc->copayAmount = Converter::convertCurrency($this->checkAndTrim($data));

        $index = $this->col_map['TITLE_18_PAYMENT'];
        $data = $row[$index];
        $sc->title18Payment = Converter::convertCurrency($this->checkAndTrim($data));

        $sc = $claim->subclaims()->save($sc);
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
     * Exports table to excel.
     */
    public function exportToXLS() {
        
        if (isset($this->xwriter)) {
            
            foreach ($this->columns as $c) {
                $this->xwriter->addHeader($c->getHeader());                
            }

            $rows = 1;
            foreach($this->claims as $claim) {
                
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
    private function exportClaim(\Zoe\Claim $claim, $row) {

        $rows_used = 0;
        $current_row = $row;
        if (isset($this->xwriter) && isset($claim)) {

            foreach ($claim->subclaims() as $sc) {
                if (isset($sc)) {
                    
                    $this->xwriter->addString(
                            $claim->providerReference,
                            $current_row,
                            $this->col_map['PROVIDER_REFERENCE']);
                    
                    $this->xwriter->addString(
                            $sc->refNumber,
                            $current_row,
                            $this->col_map['CLAIM_REFERENCE']);

                    $this->xwriter->addDate(
                            $sc->serviceDate,
                            $current_row,
                            $this->col_map['SERVICE_DATE']);

                    $this->xwriter->addString(
                            $sc->procedure,
                            $current_row,
                            $this->col_map['RENDERED_PROC']);

                    $this->xwriter->addCurrency(
                            $sc->billedAmount,
                            $current_row,
                            $this->col_map['AMOUNT_BILLED']);
                    
                    $this->xwriter->addCurrency(
                            $sc->maPayment,
                            $current_row,
                            $this->col_map['TITLE19_PAYMENT_MA']);
                    
                    $this->xwriter->addString(
                            $sc->STS,
                            $current_row,
                            $this->col_map['STS']);
                    
                    $this->xwriter->addString(
                            $claim->recipientID,
                            $current_row,
                            $this->col_map['RECIPIENT_ID']);

                    $this->xwriter->addString(
                            $claim->recipient,
                            $current_row,
                            $this->col_map['RECIPIENT_NAME']);
                    
                    $this->xwriter->addString(
                            $sc->MOD,
                            $current_row,
                            $this->col_map['MOD']);

                    $this->xwriter->addCurrency(
                            $sc->allowedCharges,
                            $current_row,
                            $this->col_map['TITLE18_ALLOWED_CHARGES']);
                    
                    $this->xwriter->addCurrency(
                            $sc->copayAmount,
                            $current_row,
                            $this->col_map['COPAY_AMT']);
                    
                    $this->xwriter->addCurrency(
                            $sc->title18Payment,
                            $current_row,
                            $this->col_map['TITLE_18_PAYMENT']);
                    
                    $rows_used++;
                    $current_row++;
                }
            }
        }
        return $rows_used;
    }

}
