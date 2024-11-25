<?php
require'../fpdf.php';  // Only one line to require FPDF

class PDF extends FPDF {

    function Header() {
        global $title;

        $this->SetFont('Arial','B',15);
        $w = $this->GetStringWidth($title)+6;
        $this->SetX((210-$w)/2);

        $this->SetDrawColor(0,80,180);
        $this->SetFillColor(230,230,0);
        $this->SetTextColor(220,50,50);
        $this->SetLineWidth(1);

        $this->Cell($w,9,$title,1,1,'C',true);
        $this->Ln(10);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);
        $this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Arial','',12);
        $this->SetFillColor(200,220,255);
        $this->Cell(0,6,"Capítulo $num : $label",0,1,'L',true);
        $this->Ln(4);
    }

    function ChapterBody($file) {
        $txt = file_get_contents($file);
        $this->SetFont('Times','',12);
        $this->MultiCell(0,5,$txt);
        $this->Ln();
        $this->SetFont('','I');
        $this->Cell(0,5,'(fin del extracto)');
    }

    function PrintChapter($num, $title, $file) {
        $this->AddPage();
        $this->ChapterTitle($num,$title);
        $this->ChapterBody($file);
    }
}

$pdf = new PDF();
$title = '20000 Leguas de Viaje Submarino';
$pdf->SetTitle($title);
$pdf->SetAuthor('Julio Verne');
$pdf->PrintChapter(1,'UN RIZO DE HUIDA','20k_c1.txt');
$pdf->PrintChapter(2,'LOS PROS Y LOS CONTRAS','20k_c2.txt');
$pdf->Output();
?>
