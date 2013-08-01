<?php
class PdfAction extends Action {
	public function __construct(){
		parent::__construct();
		$this->is_login();
	}
	public function is_login(){
		if (!session("?loginuser")){
			$this->redirect("Login/show","",0,"");
		}
	}
	public function generatePdf(){
		$id = I("get.id");
		$strcontent = A("Warn")->getMailstr();
		vendor('tcpdf.tcpdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('北京天桥科技有限公司');
		$pdf->SetTitle('告警信息简报');
		$pdf->SetSubject('信息简报');
		$pdf->SetKeywords('北京');
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		$pdf->SetFont('stsongstdlight', '', 10);
		$pdf->AddPage();
		$html = <<<EOF
<style>
    p.first {
        color: #003300;
        font-size: 12pt;
    }
    p.first span {
        color: red;
        font-style: italic;
    }

</style>
<p class="first">
EOF;
		$pdf->writeHTML($html.$strcontent."</p>", true, false, true, false, '');
		$pdf->lastPage();
		$pdfTitle = 'report-'.date('YmdHis',time()).'.pdf';
		$pdfFile = C('FILE_TEMP').'/'.$pdfTitle;
		$loginuser=session("loginuser");
		$ary=array(
			'title'=>$pdfTitle,
			'path'=>$pdfFile,
			'operator'=>$loginuser["userName"],
			'createTime'=>date("Y-m-d H:i:s")
		);
		M("bulletins")->add($ary);
		$pdf->Output($pdfFile, 'FD');
		//$pdf->Output('report.pdf', 'D');
	}

	public function viewPdf(){
		$kdata = M("keyword_page");
		$id = trim(I("get.id"));
		$kp = $kdata->where("id=".$id)->find();

		$snapshort = file_get_contents($kp[snapPath]);
		$snapshort = highlight($snapshort,split("\\|", $kp[keyword]));
		return strip_tags($snapshort);
		$this->assign('snapshort',$snapshort);
		$this->assign('kp',$kp);

		$sitedata = M("site");
		$site = $sitedata->where("id=".$kp[siteId])->find();
		$this->assign('site',$site);
		return $this->fetch('./Tpl/Pdf/viewPdf.html');
	}
}