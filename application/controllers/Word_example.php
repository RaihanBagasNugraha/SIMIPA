<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Word_example extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('personil_model');
		$this->load->model('pelanggaran_model');
		$this->load->library('word');
	}

	function index()
	{
		echo "Test PHPWord";
	}
	
	function print_word()
	{
	    $PHPWord = $this->word; // New Word Document
        $section = $PHPWord->createSection(array(
            'orientation' => 'landscape',
            'marginLeft' => 1133.86,
            'marginRight' => 1133.86,
            'marginTop' => 1133.86,
            'marginBottom' => 1133.86,
            'paperSize' => 'A4'
        )); // New landscape section
        
        // Define table style arrays
        $styleTable = array('borderSize'=>1, 'borderColor'=>'000000', 'cellMargin'=>100);
        //$styleFirstRow = array('borderBottomSize'=>18, 'borderBottomColor'=>'0000FF', 'bgColor'=>'66BBFF');
        
        // Define cell style arrays
        $styleCell = array('valign'=>'center');
        $styleCellBTLR = array('valign'=>'center', 'textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);
        
        // Define Row Span
        $cellRowSpan = array('vMerge' => 'restart');
        $cellRowContinue = array('vMerge' => 'continue');
        
        // Define font style for first row
        $fontStyle = array('bold'=>false);
        
        // Add table style
        $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
        
        // Add table
        $table = $section->addTable('myOwnTableStyle');
        
        // Add row
        $table->addRow(1000);
        
        // Add cells
        $table->addCell(680.31, $styleCell)->addText('NO.', $fontStyle, array('align' => 'center'));
        $table->addCell(2834.65, $styleCell)->addText('IDENTITAS PELANGGAR', $fontStyle, array('align' => 'center'));
        $table->addCell(1530.71, $styleCell)->addText('WAKTU DAN TEMPAT GAR', $fontStyle, array('align' => 'center'));
        $table->addCell(2834.65, $styleCell)->addText('JENIS PELANGGARAN', $fontStyle, array('align' => 'center'));
        $table->addCell(1984.25, $styleCell)->addText('JENIS HUKUMAN', $fontStyle, array('align' => 'center'));
        $table->addCell(1417.32, $styleCell)->addText('NO. PUTUSAN HUKUMAN', $fontStyle, array('align' => 'center'));
        $table->addCell(1700.79, $styleCell)->addText('BATAS WAKTU PELAKSANAAN HUKUMAN', $fontStyle, array('align' => 'center'));
        $table->addCell(1587.4, $styleCell)->addText('KET', $fontStyle, array('align' => 'center'));
        //$table->addCell(500, $styleCellBTLR)->addText('Row 5', $fontStyle);
        
        $table->addRow(400);
        $table->addCell(680.31, $styleCell)->addText('1', $fontStyle, array('align' => 'center'));
        $table->addCell(2834.65, $styleCell)->addText('2', $fontStyle, array('align' => 'center'));
        $table->addCell(1530.71, $styleCell)->addText('3', $fontStyle, array('align' => 'center'));
        $table->addCell(2834.65, $styleCell)->addText('4', $fontStyle, array('align' => 'center'));
        $table->addCell(1984.25, $styleCell)->addText('5', $fontStyle, array('align' => 'center'));
        $table->addCell(1417.32, $styleCell)->addText('6', $fontStyle, array('align' => 'center'));
        $table->addCell(1700.79, $styleCell)->addText('7', $fontStyle, array('align' => 'center'));
        $table->addCell(1587.4, $styleCell)->addText('8', $fontStyle, array('align' => 'center'));

        $no = 0;
        foreach($_POST['cekNrp'] as $row) {
            $no++;
	        $personil = $this->personil_model->select_by_nrp($row)->row();
	        $pelanggaran = $this->pelanggaran_model->select_by_nrp($row)->result();
	        
	        //print_r($personil);
	        //print_r($pelanggaran);
	        //echo "<br>";
	        if(!empty($pelanggaran)) {
	            $bio = $personil->nama."\n\n".$personil->pangkat." / ".$personil->nrp."\n\n".$personil->jabatan;
	            if(sizeof($pelanggaran) > 0) {
	                $idx = 1;
	                foreach($pelanggaran as $res) {
	                    $table->addRow();
	                    if($idx == 1) {
	                        $table->addCell(680.31, array('borderBottomSize' => 1))->addText($no, [], array('align' => 'center'));
	                        $table->addCell(2834.65, array('borderBottomSize' => 1))->addText($bio);
	                    } else {
	                        $table->addCell(680.31, array('borderBottomSize' => 1, 'borderTopSize' => 1));
	                        $table->addCell(2834.65, array('borderBottomSize' => 1, 'borderTopSize' => 1));
	                    }
	                    $table->addCell(1530.71)->addText($res->tempat.", tanggal ".$res->waktu);
                        $table->addCell(2834.65)->addText($res->jenis_pelanggaran);
                        $table->addCell(1984.25)->addText($res->jenis_hukuman);
                        $table->addCell(1417.32)->addText("Putusan sidang KKEP nomor:\n(".$res->no_putusan.")");
                        $table->addCell(1700.79)->addText($res->batas_waktu);
                        $table->addCell(1587.4)->addText($res->keterangan);
	                    
	                    $idx++;
	                }
	            } else {
	                $table->addRow();
                    $table->addCell(680.31)->addText($no, [], array('align' => 'center'));
                    $table->addCell(2834.65)->addText($bio);
                    $table->addCell(1530.71)->addText($res->tempat.", tanggal ".$res->waktu);
                    $table->addCell(2834.65)->addText($res->jenis_pelanggaran);
                    $table->addCell(1984.25)->addText($res->jenis_hukuman);
                    $table->addCell(1417.32)->addText("Putusan sidang KKEP nomor:\n(".$res->no_putusan.")");
                    $table->addCell(1700.79)->addText($res->batas_waktu);
                    $table->addCell(1587.4)->addText($res->keterangan);
	            }
	            
	            /*
	            $idx = 1;
	            foreach($pelanggaran as $res) {
	                if($idx == 1) {
	                    $number = $no.".";
	                    $bio = $personil->nama."\n\n".$personil->pangkat." / ".$personil->nrp."\n\n".$personil->jabatan;
	                } else {
	                    $number = "";
	                    $bio = "";
	                }
	                
	                $table->addRow();
                    $table->addCell(680.31)->addText($number, [], array('align' => 'center'));
                    $table->addCell(2834.65)->addText($bio);
                    $table->addCell(1530.71)->addText($res->tempat.", tanggal ".$res->waktu);
                    $table->addCell(2834.65)->addText($res->jenis_pelanggaran);
                    $table->addCell(1984.25)->addText($res->jenis_hukuman);
                    $table->addCell(1417.32)->addText("Putusan sidang KKEP nomor:\n(".$res->no_putusan.")");
                    $table->addCell(1700.79)->addText($res->batas_waktu);
                    $table->addCell(1587.4)->addText($res->keterangan);
	                
	                
    	           $idx++;
	            }
	            */
	            
	        } else {
	            $table->addRow();
                    $table->addCell(680.31)->addText($no.".", [], array('align' => 'center'));
                    $table->addCell(2834.65)->addText($personil->nama."\n\n".$personil->pangkat." / ".$personil->nrp."\n\n".$personil->jabatan);
                    $table->addCell(1530.71, $styleCell)->addText("NIHIL", array('align' => 'center'));
                    $table->addCell(2834.65, $styleCell)->addText("NIHIL", array('align' => 'center'));
                    $table->addCell(1984.25, $styleCell)->addText("NIHIL", array('align' => 'center'));
                    $table->addCell(1417.32, $styleCell)->addText("NIHIL", array('align' => 'center'));
                    $table->addCell(1700.79, $styleCell)->addText("NIHIL", array('align' => 'center'));
                    $table->addCell(1587.4, $styleCell)->addText("NIHIL", array('align' => 'center'));
	            
	        }
	    }
        

// Add more rows / cells
/*for($i = 1; $i <= 100; $i++) {
	$table->addRow();
	$table->addCell(2000)->addText("Cell $i");
	$table->addCell(2000)->addText("Cell $i");
	$table->addCell(2000)->addText("Cell $i");
	$table->addCell(2000)->addText("Cell $i");
	
	$text = ($i % 2 == 0) ? 'X' : '';
	$table->addCell(500)->addText($text);
}*/
        
        // Add text elements
        //$section->addText('Hello World!');
        //$section->addTextBreak(2);
        //$section->addText('Mohammad Rifqi Sucahyo.', array('name'=>'Verdana', 'color'=>'006699'));
        //$section->addTextBreak(2);
        //$PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
        //$PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
        // Save File / Download (Download dialog, prompt user to save or simply open it)
		//$section->addText('Ini Adalah Demo PHPWord untuk CI', 'rStyle', 'pStyle');
		
        $filename='just_some_random_name.docx'; //save our document as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save('php://output');
	}
	
	
}

?>