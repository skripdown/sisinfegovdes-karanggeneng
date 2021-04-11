<?php


namespace App\Http\back;


class _Report
{
    public static function print($fileTitle, $subtitle, $param):string {
        $docHead = '<head><meta charset="UTF-8"><title>'.$fileTitle.'</title><script src="'.asset(env('LIB_PATH').'extra/paged_js/paged.js').'"></script><script src="'.asset(env('LIB_PATH').'core/jquery/dist/jquery.min.js').'"></script><style>#header {margin-bottom: -0.25rem;}#logo-section img {display: block;margin: 0 auto 1rem;}#logo-section {display: inline-block;width: 20%;}#title-section {display: inline-block;width: 80%;}h5 {margin-top: -1rem;}h3 {text-transform:uppercase;}h2 {text-align: center;text-transform:uppercase;margin-bottom: -1rem;font-weight: bolder;}.break {page-break-after: always;}.rp-table-container {text-align: center;}.top {margin-top: 0.75em;margin-bottom: 0.75em;page-break-after: avoid;}.table-id {margin-bottom: 0.5em;}.rp-table {display: inline-table;border-collapse: collapse;width: 100%;}.rp-table>thead{font-weight: bolder;}.rp-table>tbody tr td:nth-child(2),.rp-table>tbody tr td:nth-child(3){text-align: left;}thead td {background-color: rgba(244, 208, 63,0.9);padding: 0.5em 0;}.danger {color : red;}.peserta-data {page-break-after: always;}.rp-table * {border: 1px solid black;}@page {size: A4;margin: 2cm 2cm 2cm 3cm;}</style></head>';
        $script  = '<script>const read = () => {window.print();};setTimeout(read, 1000);</script>';
        $conHead = '<div id="header"><h2>'.$fileTitle.'</h2><div id="logo-section"><img src="'.asset(env('ICON_PATH')).'" alt="" width="80"></div><div id="title-section"><h3>'.$subtitle.' '.env('APP_DESCRIPTION').'</h3><h5>'.env('OFFICE_ADDRESS').' '.env('OFFICE_POST_AREA').' - '.env('OFFICE_REGION').'. Telp '.env('OFFICE_TELP').', '.env('OFFICE_MAIL').'</h5></div></div><hr>';
        $colHead = $param['head'];
        $colData = $param['data'];

        return self::doc($conHead . self::compactTable(self::setTableHead($colHead), self::setTableBody($colHead, $colData)) ,$docHead, $script);
    }

    private static function doc($content, $meta, $script):string {
        return '<!DOCTYPE html><html lang="en">' . $meta . '<body><div class="break">' . $content . '</div>' . $script . '</body></html>';
    }

    private static function compactTable($head, $body):string {
        return '<div class="peserta-data"><div class="rp-table-container"><table class="rp-table">' . $head . $body . '</table></div></div>';
    }

    private static function setTableHead($colHead):string {
        $render = '<td>No. </td>';
        foreach ($colHead as $item) {
            $render.=('<td>'.$item['label'].'</td>');
        }

        return '<thead><tr>'.$render.'</tr></thead>';
    }

    private static function setTableBody($colHead, $colBody):string {
        $render = '';
        $index  = 1;
        foreach ($colBody as $item) {
            $render.='<tr>';
            $render.=('<td>' . $index . '</td>');
            for ($i = 0; $i < count($colHead); $i++) {
                $render.=('<td>'.$colHead[$i]['data']($item).'</td>');
            }
            $render.='</tr>';
            $index++;
        }

        return '<tbody>'.$render.'</tbody>';
    }
}
