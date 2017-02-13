<?php
require('fpdf.php');

class PDF_MC_Table3 extends FPDF
{
	
var $widths;
var $aligns;
var $heights;
var $nitems;
var $observaciones;




function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function SetHeight($height)
{
	//Set the array of column height line
	$this->heights=$height;
}

function SetFooter($ni, $ob, $je, $ci)
{
	$this->nitems=$ni;
	$this->observaciones=$ob;
	$this->jefe=$je;
	$this->cedula=$ci;
}

function Header() {	}
function Footer() {
/*
	$this->SetY(-259);
	
	$this->Cell(15, 5); $this->Cell(120, 5, '', 0, 0, 'L'); 
	$this->SetFont('Arial', '', 10);
	$this->Cell(25, 5, 'Pagina: ', 0, 0, 'R'); 
	$this->SetFont('Arial', 'B', 10);
	//Número de página
	$this->Cell(35, 5, $this->PageNo(), 0, 0, 'L');
*/	
	$this->SetY(250);
	//
	
	$this->SetDrawColor(255, 255, 255); $this->SetFillColor(100, 100, 100); $this->SetTextColor(255, 255, 255);
	$this->Cell(200, 1, '', 1, 1, 'C', 1);
}




function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	//$h=5*$nb;
	$h=($this->heights[0])*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		$this->Rect($x,$y,$w,$h);
		//Print the text
		//$this->MultiCell($w,5,$data[$i],0,$a);
		$this->MultiCell($w,$this->heights[0],$data[$i], 0, $a, 1, 'J');
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
