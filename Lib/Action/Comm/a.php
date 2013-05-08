    public function export(){
        Vendor("PHPExcel.PHPExcel");
        Vendor("PHPExcel.PHPExcel.IOFactory");
        $Excel = new PHPExcel(); 
        $Excel->getProperties()
        ->setCreator("Marchsoft")
        ->setLastModifiedBy("Marchsoft")
        ->setTitle("Marchsoft")
        ->setSubject("Marchsoft")
        ->setDescription("Marchsoft")
        ->setKeywords("Marchsoft")
        ->setCategory("Marchsoft");
        $Excel->setActiveSheetIndex(0);
        $Sheet = $Excel->getActiveSheet();  
        $ary = array("", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        
        $Sheet->getDefaultColumnDimension()->setAutoSize(true);
        $Sheet->getDefaultStyle()->getFont()->setName('Arial');
        $Sheet->getDefaultStyle()->getFont()->setSize(13);

        $i=1;
        foreach ($columns as $key => $value) { 
            $Sheet->setCellValue($ary[$i/27].$ary[$i%27].'1', $value);
            $i=$i+1;
        }
        $M = M(MODULE_NAME);
        $result=$M->select();
        for($j = 0;$j<count($result) ; $j++){
            $i=2;
            for($k = 0;$k<count($result[j]) ; $k++,$i++)
                $Sheet->setCellValue($ary[$i/27].$ary[$i%27].$i, $result[$j][$k]);
        }

        date_default_timezone_set("Asia/Shanghai"); 
        $date=date("Y-m-d-H-i-s");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$date.'.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($Excel, 'Excel5'); 
        $objWriter->save('php://output'); 
    }
    private function import($info){
        
    }
    public function upload(){
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();
        $upload->maxSize  =  C('MAX_UPLOAD_FILE_SIZE');
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg','xls','doc');
        $upload->savePath =  './Public/Uploads/';
        if(!$upload->upload()) {
            $this->error($upload->getErrorMsg());
        }else{
            $info =  $upload->getUploadFileInfo();
            R('import',$info);
        }
    }