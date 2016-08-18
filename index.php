<?php
/**
 * API test
 *
 * @category PHP
 * @author   Arno <arnoliu@tencent.com>
 */

require './Curl/autoload.php';
require './Classes/PHPExcel.php';

set_time_limit(0);

$objPHPExcel = new PHPExcel();
$objPHPExcel
        ->getProperties()
        ->setCreator("Arnoliu")
        ->setLastModifiedBy("Arnoliu")
        ->setTitle("地图 API 响应测试")
        ->setSubject("地图 API 响应测试")
        ->setDescription("每200次的响应时间，毫秒");

$curl = new Curl\CurlClient();

$url = "http://apis.map.qq.com/ws/geocoder/v1/?address=%E5%8C%97%E4%BA%AC%E5%B8%82%E6%B5%B7%E6%B7%80%E5%8C%BA%E5%BD%A9%E5%92%8C%E5%9D%8A%E8%B7%AF%E6%B5%B7%E6%B7%80%E8%A5%BF%E5%A4%A7%E8%A1%9774%E5%8F%B7&key=FRYBZ-3UNK3-46Z3D-3RASS-6TSOF-LDF5J";

$headers = array(
    'Host' => 'apis.map.qq.com'
);

$arr = array();
array_push($arr, "<?php\r\narray(\r\n");

for ($i = 0; $i < 10; $i++) { 
    $start = 0;
    $end = 0;
    $index_i = $i + 1;
    array_push($arr, '    "' . $index_i . "\" => array(\r\n");

    $start = microtime(TRUE);
    for ($j = 0; $j < 200; $j++) { 
        $s = microtime(TRUE);
        $curl->send($url, 'GET', array(), $headers);
        $e = microtime(TRUE);
        $r = $e - $s;

        $index_j = $j + 1;
        array_push($arr, '        "' . $index_j . '" => "' . $r . "\",\r\n");
    }
    $end = microtime(TRUE);

    $res = $end - $start;

    array_push($arr, '        "第' . $index_i . '次 200 个总时间" => "' . $r . "\",\r\n");
    array_push($arr, "    )\r\n");

    
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $index_i, '第'  . $index_i . '次')
                ->setCellValue('B' . $index_i, $res);
}

array_push($arr, ")\r\n");

// $objActSheet = $objPHPExcel->getActiveSheet();
// $objActSheet->setTitle('test');

// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// $objWriter->save('api-test.xlsx');

// $myfile = fopen("res.php", "w") or die("Unable to open file!");
// fwrite($myfile, implode($arr, ''));
// fclose($myfile);

// end of script
