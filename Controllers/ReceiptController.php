<?php

require_once '../../model/user.php';
require_once '../../model/Deposit.php';
require_once '../../model/Withdraw.php';
require_once '../../model/Transfer.php';
require_once '../../model/Receipt.php';
require_once '../../model/fpdf/fpdf.php';
require_once '../../Controllers/DBcontroller.php';
require_once '../../Controllers/InquiryController.php';
class ReceiptController
{
    protected $db;


    public function printReceipt(Transaction $transaction){
        session_start();
        $receipt = new Receipt();
        $receipt->transaction = $transaction;
        if (!$this->saveReceipt($receipt))
        {
            session_start();
            $_SESSION['errorMsg'] = 'Error in Saving Receipt';
            return;
        }
        ob_start();
        $pdf = new FPDF();
        //Add a new page
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','U',24);
        $pdf->MultiCell(200, 50, 'Receipt #'.$receipt->id, 0, 'L');
        $pdf->SetFont('Arial','U',20);
        $pdf->MultiCell(60, 16, "Receipt Details", 0, 'L');

        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(64, 8, "Account No: ");
        $pdf->SetFont('Arial','',18);
        $pdf->Cell(10, 8, $transaction->Account->id, 0, 1);


        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(64, 8, "Name: ");
        $pdf->SetFont('Arial','',18);
        $pdf->Cell(10, 8, $transaction->Account->Owner->name, 0, 1);
//        if (isset($_SESSION['receiver'])) {
//            if (!empty($_SESSION['receiver'])){
//                $pdf->SetFont('Arial','B',18);
//                $pdf->Cell(64, 8, "Receiver Acc No: ");
//                $pdf->SetFont('Arial','',18);
//                $pdf->Cell(10, 8, $_SESSION['receiver'], 0, 1);
//                $_SESSION['receiver']= "";
//            }
//        }

        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(64, 8, "Transaction No: ");
        $pdf->SetFont('Arial','',18);
        $pdf->Cell(10, 8, $transaction->id, 0, 1);

        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(64, 8, "Prev Balance: ");
        $pdf->SetFont('Arial','',18);
        $pdf->Cell(10, 8, $transaction->preBalance, 0, 1);

        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(64, 8, "Post Balance: ");
        $pdf->SetFont('Arial','',18);
        $pdf->Cell(10, 8, $transaction->postBalance, 0, 1);

        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(64, 8, "Transaction Type: ");
        $pdf->SetFont('Arial','',18);
        $pdf->Cell(10, 8, $transaction->type, 0, 1);

        $pdf->SetFont('Arial','B',18);
        $pdf->Cell(64, 8, "Date: ");
        $pdf->SetFont('Arial','',18);
        $pdf->Cell(10, 8, $transaction->date, 0, 1);

        $pdf->Output();
        ob_end_flush();

    }
    public function saveReceipt(Receipt $receipt) : bool{
        $this->db = new DBcontroller();

        if ($this->db->open_connection()) {
            $transactionID = $receipt->transaction->id;
            $accountID = $receipt->transaction->Account->id;
            $query = "insert into receipt ( transaction_id, account_id) value ('$transactionID','$accountID')";
            $result = $this->db->insert($query);
            if ($result === false) {
                $_SESSION['errorMsg'] = "Error in query";
                return false;
            } else {
                $receipt->id = $result;
                return true;
            }
        } else {
            echo "Error in connection";
            return false;
        }
    }


}