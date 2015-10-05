<?php

// using the composer autoloader
require 'vendor/autoload.php';

// disabling DOMPDF's internal autoloader
define('DOMPDF_ENABLE_AUTOLOAD',false);

// including DOMPDF's default configuration
require_once 'vendor/dompdf/dompdf/dompdf_config.inc.php';

include('./data/data.php'); // include the data saved from the company


// check if the file of the data exists
if(file_exists('data/data.json')) 
{
    // obtain the info of the company
    $company = json_decode(file_get_contents('data/data.json'),true);
} else {
    $company =  null;
}

$section1 = $section2 = '';

$headers = '<style type="text/css">
                body {
                    font-family: Helvetica;
                    font-size: 12px;
                }
                @page { margin: 80px 50px; }
                #header { position: fixed; left: 0px; top: -80px; right: 0px; height: 80px; padding-top: 20px; text-align: center; }
                #header h1 { font-size: 13px; color: #778899; }
                #footer { position: fixed; left: 0px; bottom: -80px; border-top: 1px solid #ccc; right: 0px; height: 80px; }
                #footer .page{ padding: 10px; }
                #footer .page:after { content: counter(page, upper-roman); }
                h3 { color: #7A7A7A; margin-bottom:0; }
                td.company { color: #A8A8A8; text-align:right; }
            </style>
            <div id="header">
                <div style="margin-left:50px;width: 150px;">
                    <img src="img/logo.png" style="display:inline-block;width: 40px; padding-top: 10px; " />
                    <h1 style="display:inline-block;">Colin Klinkert <span style="font-size: 0.8em; display: block;">http://www.colinklinkert.com</span></h1>
                </div>
            </div>
            <div id="footer">
                <p class="page"><a href="ibmphp.blogspot.com"></a></p>
            </div>';

if($company)
{
    $section1 = '<div style="width: 100%; border-bottom: 2px solid #ededed;">
                <table width="100%" cellspacing="0" cellpadding="10">
                    <tr>
                        <td width="30%">
                            <img src="'. $company['logo'] .'" style="width:130px;">
                        </td>
                        <td width="70%" class="company">
                            <h3>'.$company['name'].'</h3>
                            <p>'.$company['address'].'<br/>
                            Email: '.$company['email'].'<br/>
                            Phone: '.$company['phone'].'<br/>
                            Website: '.$company['website'].'</p>
                        </td>
                    </tr>
                </table>
            </div>';
}

$section2 = '<div style="margin-top:20px; width:100%;">
                <table width="100%" cellspacing="0" cellpadding="4" border="1">
                    <thead>
                        <tr style="text-align:center;">
                            <th width="30%">Keyword</th>
                            <th width="15%">Avg. Monthly Searches</th>
                            <th width="15%">CPC</th>
                            <th width="15%">Traffic Value</th>
                            <th width="25%">PPC COMP</th>
                        </tr>
                    </thead>
                <tbody>';

foreach($data as $k => $v) {
    $ppc = $v['traffic_val'] / $v['avg_month'] * 100;
    $ppc = $ppc < 100 ? $ppc : 100;
    $section2 .= '<tr>
                    <td width="30%">'.$v['keyword'].'</td>
                    <td width="15%" style="text-align:center;">'.number_format($v['avg_month']).'</td>
                    <td width="15%" style="text-align:center;">$'.number_format($v['cpc'],2).'</td>
                    <td width="15%" style="text-align:center;">$'.number_format($v['traffic_val'],2).'</td>
                    <td width="25%" style="text-align:center;">
                        <div style="background-color:white; border: 1px solid #0081c4; height: 10px; padding:0; width:170px; text-align:left;">
                            <div style="background-color:#0081c4; height: 10px; width:'. $ppc .'%;"></div>
                        </div>
                    </td>
                </tr>';
}
$section2 .= '</tbody></table></div>';

$html = $headers . $section1 . $section2;

$dompdf = new DOMPDF();

$dompdf->load_html($html);
$dompdf->set_paper('letter','portrait');
$dompdf->render();

$dompdf->stream('pdf_out.pdf',array('Attachment' => false));

exit(0);