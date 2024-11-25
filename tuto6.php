<?php
require('../fpdf.php');

class PDF extends FPDF
{
protected $B = 0;
protected $I = 0;
protected $U = 0;
protected $HREF = '';

function WriteHTML($html) {
    $html = str_replace("\n", ' ', $html);

    // Regular expression to match HTML tags and attributes
    $tagPattern = '/<(\/?)([^>]+)>/';

    foreach (preg_split($tagPattern, $html, -1, PREG_SPLIT_DELIM_CAPTURE) as $token) {
        if ($token[0] === '/') {
            // Closing tag
            $this->CloseTag(strtoupper($token[1]));
        } elseif ($token[0] === '<') {
            // Opening tag
            // Extract tag name and attributes
            preg_match('/^([^ ]+) *(.*)$/', $token[1], $matches);
            $tagName = strtoupper($matches[1]);
            $attributes = [];

            // Parse attributes
            if (isset($matches[2])) {
                preg_match_all('/([^ =]+)="([^"]*)"/', $matches[2], $attrMatches, PREG_SET_ORDER);
                foreach ($attrMatches as $attrMatch) {
                    $attributes[$attrMatch[1]] = $attrMatch[2];
                }
            }

            $this->OpenTag($tagName, $attributes);
        } else {
            // Text
            if ($this->HREF) {
                $this->PutLink($this->HREF, $token);
            } else {
                $this->Write(5, $token);
            }
        }
    }
}
		}
	}
}

function OpenTag($tag, $attr)
{
	// Etiqueta de apertura
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,true);
	if($tag=='A')
		$this->HREF = $attr['HREF'];
	if($tag=='BR')
		$this->Ln(5);
}

function CloseTag($tag)
{
	// Etiqueta de cierre
	if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF = '';
}

function SetStyle($tag, $enable)
{
	// Modificar estilo y escoger la fuente correspondiente
	$this->$tag += ($enable ? 1 : -1);
	$style = '';
	foreach(array('B', 'I', 'U') as $s)
	{
		if($this->$s>0)
			$style .= $s;
	}
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	// Escribir un hiper-enlace
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}
}

$html = 'Ahora puede imprimir fácilmente texto mezclando diferentes estilos: <b>negrita</b>, <i>itálica</i>,
<u>subrayado</u>, o ¡ <b><i><u>todos a la vez</u></i></b>!<br><br>También puede incluir enlaces en el
texto, como <a href="http://www.fpdf.org">www.fpdf.org</a>, o en una imagen: pulse en el logotipo.';

$pdf = new PDF();
// Primera página
$pdf->AddPage();
$pdf->SetFont('Arial','',20);
$pdf->Write(5,'Para saber qué hay de nuevo en este tutorial, pulse ');
$pdf->SetFont('','U');
$link = $pdf->AddLink();
$pdf->Write(5,'aquí',$link);
$pdf->SetFont('');
// Segunda página
$pdf->AddPage();
$pdf->SetLink($link);
$pdf->Image('logo.png',10,12,30,0,'','http://www.fpdf.org');
$pdf->SetLeftMargin(45);
$pdf->SetFontSize(14);
$pdf->WriteHTML($html);
$pdf->Output();
?>
