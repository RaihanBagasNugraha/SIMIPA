<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('FPDF_FONTPATH', 'font/');
require('fpdf/fpdf.php');
class Pdf extends FPDF
{
	
	/*function __construct()
	{
		parent::__construct();
		//$CI =& get_instance();
	} */
	
	var $numPage;
    var $qrCode;
    var $numCode;
    var $noHeader = array();
    var $footer = "";
    var $header = "";
    var $nama_jur_header = "";
    
    function set_header($mode) {
        $this->header = $mode;
    }
    
    function set_header_jur($nama) {
        $this->nama_jur_header = $nama;
    }
    
    function set_footer($mode) {
        $this->footer = $mode;
    }
    
    function setting_page_footers($numPage, $qrCode, $numCode)
    {
        $this->numPage = $numPage;
        $this->qrCode = $qrCode;
        $this->numCode = $numCode;
    }
    
    function setting_no_header($noHeader)
    {
        $this->noHeader = $noHeader;
    }
    
    // Page header
    function Header()
    {
        if(in_array($this->page, $this->noHeader))
        {

        }
        else 
        {
        
        $this->SetFont('Arial','',11);
        
        $this->Cell(287,5,'- '.$this->PageNo().' -',0,1,'C');
        
        }
        $this->Ln();
        $wt = array(12, 50, 30, 60, 40, 30, 35, 30);
        $title = array('NO.', 'IDENTITAS PELANGGAR', 'WAKTU DAN TEMPAT GAR', 'JENIS PELANGGARAN', 'JENIS HUKUMAN', 'NO. PUTUSAN HUKUMAN', 'BATAS WAKTU PELAKSANAAN HUKUMAN', 'KET');
        $ht = 20;
        $y = 20;
        $x = 15;
        $startX = 0;
        for($i=0; $i<sizeof($wt); $i++) {
            $this->SetXY($x+$startX, $y);
            $this->drawTextBox($title[$i], $wt[$i], $ht, 'C', 'M');
            $startX += $wt[$i];
        }
        
        $title2 = array('1', '2', '3', '4', '5', '6', '7', '8');
        $ht = 6;
        $y = 20 + 20;
        $x = 15;
        $startX = 0;
        for($i=0; $i<sizeof($wt); $i++) {
            $this->SetXY($x+$startX, $y);
            $this->drawTextBox($title2[$i], $wt[$i], $ht, 'C', 'M');
            $startX += $wt[$i];
        }
        $this->Ln(1);
    }
    
    // Page footer
    function Footer()
    {
        
        
        
        
    }
    
    // Wrapping Cell
    
    // 
    var $widths;
    var $aligns;
    var $spacing;
    var $bold_font;
    
    function SetSpacing($spc)
    {
        $this->spacing = $spc;
    }
    
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
    
    function SetBoldFont($a)
    {
        $this->bold_font = $a;
    }
    
    function TableTitle($data)
    {
        $this->SetFont('Times','B',14);
        $this->Cell(190, 5, $data, 0, 1, 'C');
        $this->Ln();
        $this->SetFont('Times','',11);
    }

    function TableHeader1($data)
    {
        $this->Cell(28, 8, 'Fakultas/Jurusan', 'LTB', 0, 'L', true);
        $this->Cell(3, 8, ':', 'TB', 0, 'L', true);
        $this->Cell(72, 8, $data[0], 'RTB', 0, 'L', true);
        $this->Cell(30, 8, 'Nama Mahasiswa', 'LTB', 0, 'L', true);
        $this->Cell(3, 8, ':', 'TB', 0, 'L', true);
        $this->Cell(54, 8, $data[1], 'RTB', 0, 'L', true);
        $this->Ln();
    }
    
    function TableHeader2($data)
    {
        $this->Cell(28, 8, 'Jenis Layanan', 'LTB', 0, 'L', true);
        $this->Cell(3, 8, ':', 'TB', 0, 'L', true);
        $this->SetFont('Times','B',11);
        $this->Cell(72, 8, $data[0], 'RTB', 0, 'L', true);
        $this->SetFont('Times','',11);
        $this->Cell(30, 8, 'NPM', 'LTB', 0, 'L', true);
        $this->Cell(3, 8, ':', 'TB', 0, 'L', true);
        $this->Cell(54, 8, $data[1], 'RTB', 0, 'L', true);
        $this->Ln();
    }
    
    function SubHeader($data)
    {
        for($i=0;$i<count($data);$i++)
        {
            $this->Cell($this->widths[$i], 8, $data[$i], 1, 0, 'C', true);
        }
        $this->Ln();
    }
    
    function SubHeaderNoBack($data)
    {
        $this->SetFont('','B');
        for($i=0;$i<count($data);$i++)
        {
            $this->Cell($this->widths[$i], 8, $data[$i], 1, 0, 'C', false);
        }
        $this->Ln();
    }
    
    function Row($data)
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $space = $a=isset($this->spacing) ? $this->spacing : 5;
        $h=$space*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $w=$this->widths[$i];
            $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            $style=isset($this->bold_font[$i]) ? $this->bold_font[$i] : '';
            //Save the current position
            $x=$this->GetX();
            $y=$this->GetY();
            //Draw the border
            $this->Rect($x,$y,$w,$h);
            //Print the text
            $this->SetFont('', $style);
        
            $this->MultiCell($w,$space,$data[$i],0,$a);
            //$this->drawTextBox($data[$i], $w, $space, $a, 'M', 0);
            //Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        //Go to the next line
        $this->Ln($h);
    }
    
    function RowNoBorder($data)
    {
        //Calculate the height of the row
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $space = $a=isset($this->spacing) ? $this->spacing : 5;
        $h=$space*$nb;
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
            //$this->Rect($x,$y,$w,$h);
            //Print the text
            $this->MultiCell($w,$space,$data[$i],0,$a);
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
    
    /**
     * Draws text within a box defined by width = w, height = h, and aligns
     * the text vertically within the box ($valign = M/B/T for middle, bottom, or top)
     * Also, aligns the text horizontally ($align = L/C/R/J for left, centered, right or justified)
     * drawTextBox uses drawRows
     *
     * This function is provided by TUFaT.com
     */
    function drawTextBox($strText, $w, $h, $align='L', $valign='T', $border=true)
    {
        $xi=$this->GetX();
        $yi=$this->GetY();
        
        $hrow=$this->FontSize;
        $textrows=$this->drawRows($w,$hrow,$strText,0,$align,0,0,0);
        $maxrows=floor($h/$this->FontSize);
        $rows=min($textrows,$maxrows);
    
        $dy=0;
        if (strtoupper($valign)=='M')
            $dy=($h-$rows*$this->FontSize)/2;
        if (strtoupper($valign)=='B')
            $dy=$h-$rows*$this->FontSize;
    
        $this->SetY($yi+$dy);
        $this->SetX($xi);
    
        $this->drawRows($w,$hrow,$strText,0,$align,false,$rows,1);
    
        if ($border)
            $this->Rect($xi,$yi,$w,$h);
    }
    
    function drawRows($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0, $prn=0)
    {
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $b=0;
        if($border)
        {
            if($border==1)
            {
                $border='LTRB';
                $b='LRT';
                $b2='LR';
            }
            else
            {
                $b2='';
                if(is_int(strpos($border,'L')))
                    $b2.='L';
                if(is_int(strpos($border,'R')))
                    $b2.='R';
                $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
            }
        }
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $ns=0;
        $nl=1;
        while($i<$nb)
        {
            //Get next character
            $c=$s[$i];
            if($c=="\n")
            {
                //Explicit line break
                if($this->ws>0)
                {
                    $this->ws=0;
                    if ($prn==1) $this->_out('0 Tw');
                }
                if ($prn==1) {
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                }
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if ( $maxline && $nl > $maxline )
                    return substr($s,$i);
                continue;
            }
            if($c==' ')
            {
                $sep=$i;
                $ls=$l;
                $ns++;
            }
            $l+=$cw[$c];
            if($l>$wmax)
            {
                //Automatic line break
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                    if($this->ws>0)
                    {
                        $this->ws=0;
                        if ($prn==1) $this->_out('0 Tw');
                    }
                    if ($prn==1) {
                        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                    }
                }
                else
                {
                    if($align=='J')
                    {
                        $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        if ($prn==1) $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                    }
                    if ($prn==1){
                        $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                    }
                    $i=$sep+1;
                }
                $sep=-1;
                $j=$i;
                $l=0;
                $ns=0;
                $nl++;
                if($border && $nl==2)
                    $b=$b2;
                if ( $maxline && $nl > $maxline )
                    return substr($s,$i);
            }
            else
                $i++;
        }
        //Last chunk
        if($this->ws>0)
        {
            $this->ws=0;
            if ($prn==1) $this->_out('0 Tw');
        }
        if($border && is_int(strpos($border,'B')))
            $b.='B';
        if ($prn==1) {
            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
        }
        $this->x=$this->lMargin;
        return $nl;
    }
    }

?>