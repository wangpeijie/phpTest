<?php

namespace Multiple\Backend\Controllers;

class ExportController extends ControllerBase
{

        public function initialize()
        {
                
        }

        /**
         * 导入excel文件
         */
        public function exportInAction()
        {
                require_once($this->config->application->pluginsDir . 'PHPExcel.php');
                if (!empty($_FILES['file']['name']))
                {
                        $tmp_file = $_FILES['file']['tmp_name'];
                        $file_types = explode(".", $_FILES['file']['name']);
                        $file_type = strtolower($file_types[count($file_types) - 1]);

                        if (!in_array($file_type, ['xls', 'xlsx', 'csv']))
                        {
                                exit('不是Excel文件，重新上传');
                        }

                        $savePath = __DIR__ . '/../../public/web/excel/';

                        $str = date('Ymdhis') . rand(1000, 9999);
                        $file_name = $str . "." . $file_type;

                        $type = $_FILES['file']['type'];
                        if ($type == 'application/vnd.ms-excel')
                        {
                                // Excel 97 extension
                                $ext = 'Excel5';
                        } else if ($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $type == 'application/octet-stream')
                        {
                                //'application/octet-stream' linux系统的$type
                                // Excel 2007 and 2010 extension
                                $ext = 'Excel2007';
                        } else
                        {
                                // Excel CSV extension
                                $ext = 'CSV';
                        }
                        $objReader = \PHPExcel_IOFactory::createReader($ext);
//                        if (file_exists($tmp_file))
//                        {
//                                echo 'yes';
//                        }
                        if (!copy($tmp_file, $savePath . $file_name))
                        {
                                exit('上传失败');
                        }
                }
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($savePath . $file_name);

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
                $url = $this->request->get('url');
                curlPost($url, $excelData);
//                print_r($excelData);
                unlink($savePath . $file_name);
        }

        /**
         * 导出excel文件
         */
        public function exportOutAction()
        {
                include_once $this->config->application->pluginsDir . 'PHPExcel.php';
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                // 设置行高度    
                $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22); //第一行行高
                $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20); //第二行行高
                // 字体和样式  
                $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10); //字体大小
                $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setBold(true); //第二行字体加粗
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true); //第一行字体加粗

                $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                // 设置水平居中    
                $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //  合并  
                $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
                // 表头  
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', $data['title'] . '-' . '报名管理')
                        ->setCellValue('A2', '姓名')
                        ->setCellValue('B2', '公司')
                        ->setCellValue('C2', '职位')
                        ->setCellValue('D2', '联系方式')
                        ->setCellValue('E2', '报名时间')
                        ->setCellValue('F2', '是否通过审核')
                        ->setCellValue('G2', '邮箱')
                        ->setCellValue('H2', '助理姓名')
                        ->setCellValue('I2', '助理电话');
                // 内容  
                for ($i = 0, $len = count($list); $i < $len; $i++)
                {
                        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), '姓名');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), '公司');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), '职位');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), '联系方式');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), '报名时间');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), '是否通过审核');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('G' . ($i + 3), '邮箱');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('H' . ($i + 3), '助理姓名');
                        $objPHPExcel->getActiveSheet(0)->setCellValue('I' . ($i + 3), '助理电话');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':I' . ($i + 3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); //居中对齐
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':I' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16); //从三行开始以后的行高
                }

                // Rename sheet    
                $objPHPExcel->getActiveSheet()->setTitle($data['title'] . '-' . '报名管理'); //表名
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
                $objPHPExcel->setActiveSheetIndex(0); //设置默认哪张表
                // 输出  
                ob_end_clean(); //清除缓冲区,避免乱码
                header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
//                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $data['title'] . '-' . '报名管理' . '.xlsx"'); //文件名
                header('Cache-Control: max-age=0');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); //或者Excel5
                $objWriter->save('php://output');
        }

}
