<?php

namespace Multiple\Backend\Controllers;

use Multiple\Backend\Models\Nav;
use Multiple\Backend\Models\BackNav;

class PreviewController extends ControllerBase
{

        private $res;
        private $point = 'preview';

        public function initialize()
        {
                parent::initialize();
                $this->view->setMainView('admin');
                $this->tag->setTitle('友好');
                $this->res = array('code' => 1, 'errorMessage' => '上传失败');
        }

        public function indexAction()
        {
                $nav = BackNav::findFirstByPoint($this->point);
                $data = $this->getTree($nav->id, 2);
                $this->view->setVar('data', $data);
        }

        public function pdfAction()
        {
                
        }

        public function prepdfAction()
        {
                require __DIR__ . '/../../plugins/fpdf.php';
                $pdf = new \FPDF();
                $books = array(
                        'The Sun Also Rises, by Ernest Hemingway',
                        'King Rat, by James Clavell',
                        'The Long Tail, by Chris Anderson'
                );
                $pdf->AddPage();
                $pdf->SetFont('Times', 'B', 16);
                $pdf->Cell(0, 10, 'My favorite books!', 0, 2, 'C');

                $pdf->SetFont('Times', '', 12);

                foreach ($books AS $book)
                {
                        $pdf->MultiCell(0, 20, $book, 0, 'L');
                }
                $pdf->Output();
                exit;
        }

        public function wordAction()
        {
                
        }

        public function excelAction()
        {
                
        }

        public function preexcelAction()
        {
                include_once __DIR__ . '/../../plugins/PHPExcel.php';
                $objPHPExcel = new \PHPExcel();
                // Set properties    
                $objPHPExcel->getProperties()->setCreator("ctos")
                        ->setLastModifiedBy("ctos")
                        ->setTitle("Office 2007 XLSX Test Document")
                        ->setSubject("Office 2007 XLSX Test Document")
                        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                        ->setKeywords("office 2007 openxml php")
                        ->setCategory("Test result file");
                // set width    
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                // 设置行高度    
                $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
                $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
                // 字体和样式  
                $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
                $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                // 设置水平居中    
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //  合并  
                $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
                // 表头  
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', '报名管理')
                        ->setCellValue('A2', '姓名')
                        ->setCellValue('B2', '公司')
                        ->setCellValue('C2', '职位');
                // 内容  
                for ($i = 0, $len = 3; $i < $len; $i++)
                {
                        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), '姓名');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), '公司');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), '职位');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':C' . ($i + 3))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER); //居中对齐
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':C' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                        $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16); //从三行开始以后的行高
                }
                // Rename sheet    
                $objPHPExcel->getActiveSheet()->setTitle('报名管理'); //表名
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
                $objPHPExcel->setActiveSheetIndex(0); //设置默认哪张表
                // 输出  
                ob_end_clean(); //清除缓冲区,避免乱码
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //或者Excel5
                $objWriter->save(__DIR__ . '/../../../public/web/excel/test.xls');
        }

        public function excelPhotoAction()
        {
                
        }

        public function preExcelPhotoAction()
        {
                require_once __DIR__ . '/../../plugins/PHPExcel.php';
                $excel = new \PHPExcel();
                /* 实例化excel图片处理类 */
//                $objDrawing = new \PHPExcel_Worksheet_Drawing();
                /* 设置文本对齐方式 */
                $excel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $excel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
                /* 设置表头数据 */
                $tableheader = array('姓名', '性别', '年龄', '班级', '头像');
                /* 填充表格表头 */
                for ($i = 0; $i < count($tableheader); $i++)
                {
                        $excel->setActiveSheetIndex(0)->setCellValue("$letter[$i]1", "$tableheader[$i]");
//                        $excel->getActiveSheet()->setCellValue("$letter[$i]1", "$tablehseader[$i]");
                }

                /* 设置表格数据 */
                $data = array(
                        array('小王', '男', '20', 'CS12', __DIR__ . '/../../../public/web/images/activity.png'),
                        array('小李', '女', '21', 'CS12', __DIR__ . '/../../../public/web/images/activity.png'),
                        array('小周', '男', '22', 'CS12', __DIR__ . '/../../../public/web/images/activity.png'),
                        array('小赵', '女', '23', 'CS12', __DIR__ . '/../../../public/web/images/activity.png'),
                        array('小张', '男', '24', 'CS12', __DIR__ . '/../../../public/web/images/activity.png')
                );
//                exportExcel($data, ['param' => $tableheader, 'examTitle' => 'title']);
//                exit;
                /* 填充表格内容 */
                for ($i = 0; $i < count($data); $i++)
                {
                        $j = $i + 2;
                        /* 设置表格宽度 */
                        $excel->getActiveSheet()->getColumnDimension("$letter[$i]")->setWidth(20);
                        /* 设置表格高度 */
                        $excel->getActiveSheet()->getRowDimension($j)->setRowHeight(100);
                        /* 向每行单元格插入数据 */
                        for ($row = 0; $row < count($data[$i]); $row++)
                        {
                                if ($row == (count($data[$i]) - 1 ))
                                {
                                        /* 实例化插入图片类 */
                                        $objDrawing = new \PHPExcel_Worksheet_Drawing();
                                        /* 设置图片路径 切记：只能是本地图片 */
                                        $objDrawing->setPath($data[$i][$row]);
                                        /* 设置图片高度 */
                                        $objDrawing->setHeight(100);
                                        $objDrawing->setWidth(20);
                                        /* 设置图片所在单元格的格式 */
//                                        $objDrawing->setOffsetX(100);
//                                        $objDrawing->setOffsetY(1);
//                                        $objDrawing->setRotation(20);
                                        $objDrawing->getShadow()->setVisible(true);
                                        $objDrawing->getShadow()->setDirection(50);
                                        /* 设置图片要插入的单元格 */
                                        $objDrawing->setCoordinates("$letter[$row]$j");
                                        $objDrawing->setWorksheet($excel->getActiveSheet());
                                        continue;
                                }
                                $excel->getActiveSheet()->setCellValue("$letter[$row]$j", '7777');
                        }
                }
                $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $objWriter->save(__DIR__ . '/../../../public/web/excel/photo.xlsx');
                exit;
//                //使用本地图片
//                $img = new \PHPExcel_Worksheet_Drawing();
//                $img->setPath($this->config->application->imageUri . 'activity.png'); //写入图片路径
//                $img->setHeight(100); //写入图片高度
//                $img->setWidth(100); //写入图片宽度
//                $img->setOffsetX(1); //写入图片在指定格中的X坐标值
//                $img->setOffsetY(1); //写入图片在指定格中的Y坐标值
//                $img->setRotation(1); //设置旋转角度
//                $img->getShadow()->setVisible(true); //
//                $img->getShadow()->setDirection(50); //
//                $img->setCoordinates('F2'); //设置图片所在表格位置
//                $img->setWorksheet($excel->getActiveSheet()); //把图片写到当前的表格中

                /* 实例化excel输入类并完成输出excel文件 */
//                $write = new \PHPExcel_Writer_Excel5($excel);
                ob_end_clean(); //清除缓冲区,避免乱码
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
//                header("Content-Type:application/force-download");
//                header("Content-Type:application/vnd.ms-execl");
                header("Content-Type:application/octet-stream");
//                header("Content-Type:application/application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
//                header("Content-Type:application/download");
                header('Content-Disposition:attachment;filename="测试文件.xlsx"');
                header("Content-Transfer-Encoding:binary");
                header('Cache-Control: max-age=0');
//                $write->save('php://output');
                $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
                $objWriter->save('php://output');
        }

        public function excelPhotoOutAction()
        {
                
        }

        public function getExcelPhotoAction()
        {
//                ini_set("display_errors", 1);
//
//                require_once __DIR__ . '/../../plugins/PHPExcel.php';
//                require_once __DIR__ . '/../../plugins/PHPExcel/IOFactory.php';
//
//                define('EXCEL_EXTENSION_2003', "xls");
//                define('EXCEL_EXTENSION_2007', "xlsx");
//
//
//                $fileName2003 = "Standard_Format_File1.xls";
//                $fileName2007 = __DIR__ . '/../../../public/web/excel/photo.xlsx';
//
//                $fileName = $fileName2007;
////$fileName = $fileName2007;  
//
//                if ($this->getExtendFileName($fileName) == EXCEL_EXTENSION_2003)
//                {
//                        $reader = \PHPExcel_IOFactory::createReader('Excel5');
//                } else if ($this->getExtendFileName($fileName) == EXCEL_EXTENSION_2007)
//                {
//                        $reader = new \PHPExcel_Reader_Excel2007();
//                }
//
//                $PHPExcel = $reader->load($fileName);
//                $worksheet = $PHPExcel->getActiveSheet();
//
//                $result = array();
//                $imageFileName = "";
//
//                foreach ($worksheet->getDrawingCollection() as $drawing)
//                {
//                        $xy = $drawing->getCoordinates();
//                        $path = __DIR__ . '/../../../public/web/excel/';
//                        // for xlsx  
//                        if ($drawing instanceof \PHPExcel_Worksheet_Drawing)
//                        {
//                                $filename = $drawing->getPath();
//                                $imageFileName = $drawing->getIndexedFilename();
//                                $path = $path . $drawing->getIndexedFilename();
//                                copy($filename, $path);
//                                $result[$xy] = $path;
//                                // for xls  
//                        } else if ($drawing instanceof \PHPExcel_Worksheet_MemoryDrawing)
//                        {
//                                $image = $drawing->getImageResource();
//                                $renderingFunction = $drawing->getRenderingFunction();
//                                switch ($renderingFunction)
//                                {
//                                        case \PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG:
//                                                $imageFileName = $drawing->getIndexedFilename();
//                                                $path = $path . $drawing->getIndexedFilename();
//                                                imagejpeg($image, $path);
//                                                break;
//                                        case \PHPExcel_Worksheet_MemoryDrawing::RENDERING_GIF:
//                                                $imageFileName = $drawing->getIndexedFilename();
//                                                $path = $path . $drawing->getIndexedFilename();
//                                                imagegif($image, $path);
//                                                break;
//                                        case \PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG:
//                                                $imageFileName = $drawing->getIndexedFilename();
//                                                $path = $path . $drawing->getIndexedFilename();
//                                                imagegif($image, $path);
//                                                break;
//                                        case \PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT:
//                                                $imageFileName = $drawing->getIndexedFilename();
//                                                $path = $path . $drawing->getIndexedFilename();
//                                                imagegif($image, $path);
//                                                break;
//                                }
//                                $result[$xy] = $imageFileName;
//                        }
//                }
//                print_r($result);
//                exit;


                require_once __DIR__ . '/../../plugins/PHPExcel.php';
                require_once __DIR__ . '/../../plugins/PHPExcel/IOFactory.php';
                $file = __DIR__ . '/../../../public/web/excel/photo.xlsx';
                if ($this->getExtendFileName($fileName) == 'xls')
                {
                        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                } else if ($this->getExtendFileName($fileName) == 'xlsx')
                {
                        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                }
                $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                // 设置载入含有贴图的 sheet
//                $objReader->setLoadSheetsOnly(array('sheet1'));
                $objPHPExcel = $objReader->load(__DIR__ . '/../../../public/web/excel/photo.xlsx');

                $drawing = new \PHPExcel_Writer_Excel2007_Drawing();

                $drawingHashTable = new \PHPExcel_HashTable();
                $drawingHashTable->addFromSource($drawing->allDrawings($objPHPExcel));
//                print_r($drawing->allDrawings($objPHPExcel));exit;
                for ($i = 0; $i < $drawingHashTable->count(); ++$i)
                {
                        $memoryDrawing = $drawingHashTable->getByIndex($i);
                        $path = __DIR__ . '/../../../public/web/excel/';
                        if ($memoryDrawing instanceof \PHPExcel_Worksheet_Drawing)
                        {
                                $filename = $memoryDrawing->getPath();
                                $imageFileName = $memoryDrawing->getIndexedFilename();
                                $path = $path . $memoryDrawing->getIndexedFilename();
                                copy($filename, $path);
                                // 获得该图片所在的单元格
                                $cell = $memoryDrawing->getWorksheet()->getCell($memoryDrawing->getCoordinates());
                                // 将该单元格的值设置为单元格的文本加上图片的 img 标签
                                $cell->setValue($imageFileName);
//                                $cell->setValue($cell->getValue() . '<img src="' . $imageFileName . '" />');
                        } elseif ($memoryDrawing instanceof \PHPExcel_Worksheet_MemoryDrawing)
                        {
                                $image = $memoryDrawing->getImageResource();
                                $renderingFunction = $memoryDrawing->getRenderingFunction();
                                switch ($renderingFunction)
                                {
                                        case \PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG:
                                                $imageFileName = $memoryDrawing->getIndexedFilename();
                                                $path = $path . $memoryDrawing->getIndexedFilename();
                                                imagejpeg($image, $path);
                                                break;
                                        case \PHPExcel_Worksheet_MemoryDrawing::RENDERING_GIF:
                                                $imageFileName = $memoryDrawing->getIndexedFilename();
                                                $path = $path . $memoryDrawing->getIndexedFilename();
                                                imagegif($image, $path);
                                                break;
                                        case \PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG:
                                                $imageFileName = $memoryDrawing->getIndexedFilename();
                                                $path = $path . $memoryDrawing->getIndexedFilename();
                                                imagepng($image, $path);
                                                break;
                                        case \PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT:
                                                $imageFileName = $memoryDrawing->getIndexedFilename();
                                                $path = $path . $memoryDrawing->getIndexedFilename();
                                                imagegif($image, $path);
                                                break;
                                }
                                $cell = $memoryDrawing->getWorksheet()->getCell($memoryDrawing->getCoordinates());
                                $cell->setValue($imageFileName);
//                                $cell->setValue($cell->getValue() . '<img src="' . $filename . '" />');
//                                $result[$xy] = $imageFileName;
//                                $filename = __DIR__ . '/../../../public/web/excel/' . $memoryDrawing->getCoordinates() . '_' . $memoryDrawing->getHashCode() . '.png';
//                                // 将图片存到指定的目录
//                                imagepng($memoryDrawing->getImageResource(), $filename);
//                                // 获得该图片所在的单元格
//                                $cell = $memoryDrawing->getWorksheet()->getCell($memoryDrawing->getCoordinates());
//                                // 将该单元格的值设置为单元格的文本加上图片的 img 标签
//                                $cell->setValue($cell->getValue() . '<img src="' . $filename . '" />');
                        }
                }
                $objWorksheet = $objPHPExcel->getActiveSheet();

                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                $excelData = array();
                for ($row = 1; $row <= $highestRow; $row++)
                {
                        for ($col = 0; $col < $highestColumnIndex; $col++)
                        {
                                $excelData[$row][] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                        }
                }
                print_r($excelData);
                exit;
                exit;
        }

        private function getExtendFileName($file_name)
        {

                $extend = pathinfo($file_name);
                $extend = strtolower($extend["extension"]);
                return $extend;
        }

        public function prewordAction()
        {
                require_once __DIR__ . '/../../plugins/PHPWord.php';
                // Create a new PHPWord Object
                $PHPWord = new \PHPWord();
                // Every element you want to append to the word document is placed in a section. So you need a section:
                $section = $PHPWord->createSection();
                // After creating a section, you can append elements:
                $section->addText('Hello world!');
                // You can directly style your text by giving the addText function an array:
                $section->addText('Hello world! I am formatted.', array('name' => 'Tahoma', 'size' => 16, 'bold' => true));

                // If you often need the same style again you can create a user defined style to the word document
                // and give the addText function the name of the style:
                $PHPWord->addFontStyle('myOwnStyle', array('name' => 'Verdana', 'size' => 14, 'color' => '1B2232'));
                $section->addText('Hello world! I am formatted by a user defined style', 'myOwnStyle');
                // You can also putthe appended element to local object an call functions like this:
//                $myTextElement = $section->addText('Hello World!');
//                $myTextElement->setBold();
//                $myTextElement->setName('Verdana');
//                $myTextElement->setSize(22);
                // At least write the document to webspace:
                $objWriter = \PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
                $objWriter->save(__DIR__ . '/../../../public/excel/test.php');
                exit;
        }

        public function addNavAction()
        {
                
        }

}
