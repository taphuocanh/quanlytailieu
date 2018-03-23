<?php
require 'src/fpdf/fpdf.php';
require 'src/FPDI/fpdi.php';

require 'configs.php';

$conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
mysqli_set_charset($conn,"utf8");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

class PDF_Rotate extends FPDI {
    var $angle = 0;
    function Rotate($angle, $x = -1, $y = -1) {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle*=M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }
    function _endpage() {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
}

$uploaded = 0;
$target_dir = "uploads/";
$msg = '';
$uploadOk = 1;	    

if(isset($_POST) && isset($_FILES['file'])) {
    // echo json_encode($_POST);
    // return;
    $duoi = explode('.', $_FILES['file']['name']); // tách chuỗi khi gặp dấu .
    $duoi = $duoi[(count($duoi)-1)];//lấy ra đuôi file
    //Kiểm tra xem có phải file pdf không
    if($duoi === 'pdf'){
        //tiến hành upload

        



        if(move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_POST['id'] . '.pdf')){

            $sql = "UPDATE document SET `path` = '" . $_POST['id'] . '.pdf' . "' WHERE id= " . $_POST['id'];

            if ($conn->query($sql) === TRUE) {
                $fullPathToFile = 'uploads/' . $_POST['id'] . '.pdf';

                class PDF extends PDF_Rotate {
                    var $_tplIdx;
                    
                    function Header() {
                        global $fullPathToFile;
                        
                        //Put the watermark
                        
                        $this->SetFont('Arial', 'B', 25);
                        $this->SetTextColor(255, 192, 203);
                        $this->RotatedText(20, 230, '', 45);
                        
                        
                        if (is_null($this->_tplIdx)) {
                            // THIS IS WHERE YOU GET THE NUMBER OF PAGES
                            $this->numPages = $this->setSourceFile($fullPathToFile);
                            $this->_tplIdx = $this->importPage(1);
                        }
                        $this->useTemplate($this->_tplIdx, 0, 0, 200);
                        $this->Image('WATERMARK.png', -1, 0, 200, 0, 'PNG');
                        
                        
                    }
                    function RotatedText($x, $y, $txt, $angle) {
                        //Text rotated around its origin
                        $this->Rotate($angle, $x, $y);
                        $this->Text($x, $y, $txt);
                        $this->Rotate(0);
                    }
                }
                # ==========================
                $pdf = new PDF();
                //$pdf = new FPDI();
                $pdf->AddPage();
                $pdf->SetFont('Arial', '', 12);
                /*$txt = "FPDF is a PHP class which allows to generate PDF files with pure PHP, that is to say " .
                        "without using the PDFlib library. F from FPDF stands for Free: you may use it for any " .
                        "kind of usage and modify it to suit your needs.\n\n";
                for ($i = 0; $i < 25; $i++) {
                    $pdf->MultiCell(0, 5, $txt, 0, 'J');
                }*/
                if($pdf->numPages>1) {
                    for($i=2;$i<=$pdf->numPages;$i++) {
                        //$pdf->endPage();
                        $pdf->_tplIdx = $pdf->importPage($i);
                        $pdf->AddPage();
                    }
                } //If you Leave blank then it should take default "I" i.e. Browser
                //$pdf->Output("sampleUpdated.pdf", 'D'); //Download the file. open dialogue window in browser to save, not open with PDF browser viewer
                $pdf->Output($fullPathToFile, 'F', true); //save to a local file with the name given by filename (may include a path)
                //$pdf->Output("sampleUpdated.pdf", 'I'); //I for "inline" to send the PDF to the browser
                //$pdf->Output("", 'S'); //return the document as a string. filename is ignored.
                //$pdf->Output();
                //Nếu thành công
                die('Upload thành công file: '. "<br>Tài liệu ". basename($_FILES["file"]["name"]). " đã được tải lên và đóng watermark theo bản quyền của trường. <a href=\"./uploads/". $_POST["id"]. ".pdf\" target=\"_blank\">Click vào đây</a> để xem tài liệu. <script>setTimeout(function(){window.open('./uploads/". $_POST["id"]. ".pdf', '_blank'); window.location = './uploads/". $_POST["id"]. ".pdf'; return false;}, 0);</script>"); //in ra thông báo + tên file
            } else {
                die("Error updating record: " . $conn->error);
            }

            
        } else{ //nếu k thành công
            die('Có lỗi!'); //in ra thông báo
        }
    } else{ //nếu k phải file ảnh
        die('Chỉ được upload file pdf'); //in ra thông báo
    }
} else {
    die('Lock'); // nếu không phải post method
}