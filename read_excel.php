<?php
require 'vendor/autoload.php';
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('storage/app/private/test_export.xlsx');
$sheet = $spreadsheet->getActiveSheet();
foreach ($sheet->getRowIterator(1, 4) as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE);
    $rowData = [];
    foreach ($cellIterator as $cell) {
        $val = $cell->getFormattedValue();
        $merge = $cell->getMergeRange();
        $rowData[] = $val . ($merge ? ' (MERGE: ' . $merge . ')' : '');
    }
    echo "Row " . $row->getRowIndex() . ": " . implode('|', $rowData) . "\n";
}
?>
