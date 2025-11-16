<?php

class qcGibsonReport extends Controller
{
    var $sUsername;
    var $sLevel;

    var $nRowsPerPage = 100;

    var $_bModal = false;
    var $_sModalTarget = '';

    function qcGibsonReport()
    {
        parent::Controller();
        $this->load->library('session');

        if (!$this->session->userdata('b_eg_checker_gibson')) {
            show_error('Access Denied');
        }

        $this->sUsername = $this->session->userdata('s_username');
        $this->sLevel = $this->session->userdata('s_level');

        $this->load->model('eg/Report2_model');
        $this->load->model('eg/reportQcGibson_model');
        $this->load->model('Util_model');

        $this->load->helper('url');
        $this->load->library('form');

        $this->load->library('parser');

        $this->load->library('validation');
        $this->validation->set_error_delimiters('<div class="message_error">', '</div>');
        $this->load->library('validatejs');
    }

    function index($sMessage = '')
    {
        $nOffset = 0;
        $nTotalRows = 0;

        $aDisplay = array(
            'baseurl'            => base_url(),
            'basesiteurl'        => site_url(),
            'siteurl'            => site_url() . '/eg/qcgibson/report/model/',
            'MESSAGES'           => '',
            'PAGE_TITLE'         => 'SPMS-G. Electric Guitar/Report List',
            'sGlobalUserName'    => $this->sUsername,
            'sGlobalUserLevel'   => $this->sLevel,
            'nRowsPerPage'       => $this->nRowsPerPage,
            'nTotalRows'         => $nTotalRows,
            'nCurrOffset'        => $nOffset
        );

        $this->parser->parse('header', $aDisplay);
        $this->parser->parse('eg/reportlist', $aDisplay);
        $this->parser->parse('footer', $aDisplay);
    }

    function group($sViewReport, $sMessage = '')
    {
        $sCriteria = '';
        /* -- Searching -- */
        $aCriteria = array();
        $aSessionSearch = $this->session->userdata($sViewReport . '_search');
        $aSearchForm = array(
            's_color_filter'                => '',
            's_lot_no_filter'               => '',
            'd_production_date_month_filter' => '',
            'd_production_date_year_filter'  => '',
            'd_production_date_month_filter2' => '',
            'd_production_date_year_filter2' => '',
            's_po_no_filter'                => '',
            's_po_filter'                   => '',
            's_buyer_filter'                => '',
            's_model_filter'                => '',
            's_status_filter'               => '',
            's_location_filter'             => '',
            'sSort'                         => 'd_production_date',
            'sSortMethod'                   => 'ASC'
        );
        $aSessionSearchForm = $this->session->userdata($sViewReport . '_search_form');

        /* -- Sorting -- */
        $aSort = array($aSearchForm['sSort'] => $aSearchForm['sSortMethod']);
        $aSessionSort = $this->session->userdata($sViewReport . '_sort');

        if (isset($_POST['nOffset'])) {
            /* -- Searching -- */
            if (!empty($aSessionSearch)) {
                $this->session->unset_userdata($sViewReport . '_search');
                $this->session->unset_userdata($sViewReport . '_search_form');
            }

            if ($this->input->post('s_color_filter')) {
                $s_color_filter = $this->input->post('s_color_filter');
                $aCriteria[] = "ttp.s_color_name ILIKE '%$s_color_filter%'";
            }
            if ($this->input->post('s_lot_no_filter')) {
                $s_lot_no_filter = $this->input->post('s_lot_no_filter');
                $aCriteria[] = "ttp.s_lot_no ILIKE '%$s_lot_no_filter%'";
            }
            if ($this->input->post('d_production_date_month_filter') && $this->input->post('d_production_date_year_filter')) {
                $d_production_date_month_filter = sprintf('%02d', $this->input->post('d_production_date_month_filter'));
                $d_production_date_year_filter = $this->input->post('d_production_date_year_filter');
                $d_production_date_year_month_filter = $d_production_date_year_filter . $d_production_date_month_filter;
                $aCriteria[] = "TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
            }
            if ($this->input->post('d_production_date_month_filter2') && $this->input->post('d_production_date_year_filter2')) {
                $d_production_date_month_filter = sprintf('%02d', $this->input->post('d_production_date_month_filter2'));
                $d_production_date_year_filter = $this->input->post('d_production_date_year_filter2');
                $d_production_date_year_month_filter = $d_production_date_year_filter . $d_production_date_month_filter;
                $aCriteria[] = "TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
            }
            if ($this->input->post('s_po_no_filter')) {
                $s_po_no_filter = $this->input->post('s_po_no_filter');
                $aCriteria[] = "ttp.s_po_no ILIKE '%$s_po_no_filter%'";
            }
            if ($this->input->post('s_po_filter')) {
                $s_po_filter = $this->input->post('s_po_filter');
                $aCriteria[] = "ttp.s_po ILIKE '%$s_po_filter%'";
            }
            if ($this->input->post('s_buyer_filter')) {
                $s_buyer_filter = $this->input->post('s_buyer_filter');
                $aCriteria[] = "ttp.s_buyer = '$s_buyer_filter'";
            }
            if ($this->input->post('s_model_filter')) {
                $s_model_filter = $this->input->post('s_model_filter');
                $aCriteria[] = "ttp.s_model_name ILIKE '%$s_model_filter%'";
            }
            if ($this->input->post('s_location_filter')) {
                $s_location_filter = $this->input->post('s_location_filter');
                $aCriteria[] = "ttp.s_location ILIKE '%$s_location_filter%'";
            }
            if ($this->input->post('s_status_filter')) {
                $s_status_filter = $this->input->post('s_status_filter');
                if ($s_status_filter == 'export') {
                    $aCriteria[] = "ttp.n_process_14 >";
                } else {
                    $aCriteria[] = "ttp.n_process_14 = 0";
                }
            }
            foreach ($aSearchForm as $sField => $sValue)
                $aSearchForm[$sField] = $this->input->post($sField);
            if (!empty($aCriteria)) {
                $this->session->set_userdata(array($sViewReport . '_search' => $aCriteria));
                $this->session->set_userdata(array($sViewReport . '_search_form' => $aSearchForm));
            }

            /* -- Sorting -- */
            if (!empty($aSessionSort)) $this->session->unset_userdata($sViewReport . '_sort');
            if ($this->input->post('sSort') && $this->input->post('sSortMethod')) {
                $sSort = $this->input->post('sSort');
                $sSortMethod = $this->input->post('sSortMethod');
                if ($this->input->post('bSortAction')) {
                    $sSortMethod = ($this->input->post('sSortMethod') == 'ASC' ? 'DESC' : 'ASC');
                    $aSearchForm['sSortMethod'] = $sSortMethod;
                }
                $aSort = array($sSort => $sSortMethod);
            }
            if (!empty($aSort)) $this->session->set_userdata(array($sViewReport . '_sort' => $aSort));
        } else {
            /* -- Default View -- */
            if (empty($aSessionSearch['d_production_date_month_filter']) && empty($aSessionSearch['d_production_date_year_filter'])) {
                $d_production_date_month_filter = date('m');
                $d_production_date_year_filter = date('Y');
                $d_production_date_year_month_filter = $d_production_date_year_filter . $d_production_date_month_filter;
                $sPartCriteria = "TO_CHAR(ttp.d_production_date, 'YYYYMM')>='$d_production_date_year_month_filter'";
                $aCriteria[] = $sPartCriteria;
                $aSearch['d_production_date_month_filter'] = $sPartCriteria;
                $aSearch['d_production_date_year_filter'] = $sPartCriteria;
                $aSearchForm['d_production_date_month_filter'] = $d_production_date_month_filter;
                $aSearchForm['d_production_date_year_filter'] = $d_production_date_year_filter;
            }
            if (empty($aSessionSearch['d_production_date_month_filter2']) && empty($aSessionSearch['d_production_date_year_filter2'])) {
                $d_production_date_month_filter = date('m');
                $d_production_date_year_filter = date('Y');
                $d_production_date_year_month_filter = $d_production_date_year_filter . $d_production_date_month_filter;
                $sPartCriteria = "TO_CHAR(ttp.d_production_date, 'YYYYMM')<='$d_production_date_year_month_filter'";
                $aCriteria[] = $sPartCriteria;
                $aSearch['d_production_date_month_filter2'] = $sPartCriteria;
                $aSearch['d_production_date_year_filter2'] = $sPartCriteria;
                $aSearchForm['d_production_date_month_filter2'] = $d_production_date_month_filter;
                $aSearchForm['d_production_date_year_filter2'] = $d_production_date_year_filter;
            }
            $this->session->set_userdata(array($sViewReport . '_search' => $aCriteria));

            if (!empty($aSessionSearch)) {
                if ($sMessage != '') {
                    $aCriteria = $aSessionSearch;
                    $aSearchForm = $aSessionSearchForm;
                } else {
                    $this->session->unset_userdata($sViewReport . '_search');
                    $this->session->unset_userdata($sViewReport . '_search_form');
                }
            }

            if (!empty($aSessionSort)) {
                if ($sMessage != '')
                    $aSort = $aSessionSort;
                else
                    $this->session->unset_userdata($sViewReport . '_sort');
            }
        }

        $sCriteria = implode(' AND ', $aCriteria);

        $aDataProduct = array();
        $aTotalData = array();
        $aTotalDatas = array();
        $n_t_qty = 0;
        $n_t_process_1 = 0;
        $n_t_process_2 = 0;
        $n_t_process_3 = 0;
        $n_t_process_4 = 0;
        $n_t_process_5 = 0;
        $n_t_process_6 = 0;
        $n_t_process_7 = 0;
        $n_t_process_8 = 0;
        $n_t_warehouse = 0;
        $n_t_process_9 = 0;
        $n_t_process_gibson = 0;
        $n_t_process_10 = 0;
        $n_t_process_14 = 0;

        if (trim($sViewReport) == 'po') {
            $aDataProduct = $this->Report2_model->getListPo($sCriteria, 0, 0, $aSort);
        } elseif (trim($sViewReport) == 'lot') {
            $aDataProduct = $this->Report2_model->getListLot($sCriteria, 0, 0, $aSort);
        } elseif (trim($sViewReport) == 'buyer') {
            $aDataProduct = $this->Report2_model->getListBuyer($sCriteria, 0, 0, $aSort);
        } elseif (trim($sViewReport) == 'model') {
            $aDataProduct = $this->reportQcGibson_model->getListModel($sCriteria, 0, 0, $aSort);
        } elseif (trim($sViewReport) == 'color') {
            $aDataProduct = $this->reportQcGibson_model->getListColor($sCriteria, 0, 0, $aSort);
        }

        $nTotalRows = count($aDataProduct);

        foreach ($aDataProduct as $nRow => $aEachDataProduct) {
            $n_t_qty += $aEachDataProduct['n_qty'];
            $n_t_process_1 += $aEachDataProduct['n_process_1'];
            $n_t_process_2 += $aEachDataProduct['n_process_2'];
            $n_t_process_3 += $aEachDataProduct['n_process_3'];
            $n_t_process_4 += $aEachDataProduct['n_process_4'];
            $n_t_process_5 += $aEachDataProduct['n_process_5'];
            $n_t_process_6 += $aEachDataProduct['n_process_6'];
            $n_t_process_7 += $aEachDataProduct['n_process_7'];
            $n_t_process_8 += $aEachDataProduct['n_process_8'];
            $n_t_process_9 += $aEachDataProduct['n_process_9'];
            $n_t_process_gibson += $aEachDataProduct['n_process_gibson'];
            $n_t_process_10 += $aEachDataProduct['n_process_10'];
            $n_t_warehouse += 1;
            $n_t_process_14 += $aEachDataProduct['n_process_14'];
        }

        $aTotalData['n_t_qty'] = $n_t_qty;
        $aTotalData['n_t_process_1'] = $n_t_process_1;
        $aTotalData['n_t_process_2'] = $n_t_process_2;
        $aTotalData['n_t_process_3'] = $n_t_process_3;
        $aTotalData['n_t_process_4'] = $n_t_process_4;
        $aTotalData['n_t_process_5'] = $n_t_process_5;
        $aTotalData['n_t_process_6'] = $n_t_process_6;
        $aTotalData['n_t_process_7'] = $n_t_process_7;
        $aTotalData['n_t_process_8'] = $n_t_process_8;
        $aTotalData['n_t_process_9'] = $n_t_process_9;
        $aTotalData['n_t_process_gibson'] = $n_t_process_gibson;
        $aTotalData['n_t_process_10'] = $n_t_process_10;
        $aTotalData['n_t_warehouse'] = number_format($n_t_warehouse, 0, ',', '.');
        $aTotalData['n_t_process_14'] = $n_t_process_14;

        $aTotalDatas[] = $aTotalData;

        $aDisplay = array(
            'baseurl'            => base_url(),
            'basesiteurl'        => site_url(),
            'siteurl'            => site_url() . '/eg/qcgibsonreport/group/' . $sViewReport . '/',
            'MESSAGES'           => '',
            'filterCaption'      => 'EG Group Filter/Search',
            'sViewReport'        => $sViewReport,
            'sGlobalUserName'    => $this->sUsername,
            'sGlobalUserLevel'   => $this->sLevel,
            'nTotalRows'         => $nTotalRows,
            'tt_report_stock'    => $aDataProduct,
            'tt_report_stock_total' => $aTotalDatas
        );

        if (trim($sViewReport) == 'po') {
            $aDisplay['PAGE_TITLE'] = 'SPMS-G. Electric Guitar/Group By PI Number (Stock)';
        } elseif (trim($sViewReport) == 'lot') {
            $aDisplay['PAGE_TITLE'] = 'SPMS-G. Electric Guitar/Group By LOT Number (Stock)';
        } elseif (trim($sViewReport) == 'buyer') {
            $aDisplay['PAGE_TITLE'] = 'SPMS-G. Electric Guitar/Group By Buyer (Stock)';
        } elseif (trim($sViewReport) == 'model') {
            $aDisplay['PAGE_TITLE'] = 'SPMS-G. Electric Guitar/Group By Model (Stock)';
        } elseif (trim($sViewReport) == 'color') {
            $aDisplay['PAGE_TITLE'] = 'SPMS-G. Electric Guitar/Group By Color (Stock)';
        }

        $aDisplay = array_merge($aDisplay, $aSearchForm);
        $aDisplay['d_production_date_month_filter'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter']);
        $aDisplay['d_production_date_month_filter2'] = $this->form->selectboxarray($this->config->item('list_month'), $aSearchForm['d_production_date_month_filter2']);
        $aDisplay['s_buyer_filter'] = $this->form->selectboxquery("SELECT s_code, s_code || ' - ' || s_name AS s_name FROM tm_customer WHERE s_division='EG' AND UPPER(s_name::text) LIKE '%GIBSON%' ORDER BY s_name ASC", 's_code', 's_name', $aDisplay['s_buyer_filter']);
        $aDisplay['s_status_filter'] = $this->form->selectboxarray($this->config->item('status_export'), $aSearchForm['s_status_filter']);
        $aDisplay['s_location_filter'] = $this->form->selectboxarray($this->config->item('product_location'), $aSearchForm['s_location_filter']);

        $aDisplay['viewFilter'] = $this->load->view('eg/qcgibson/report/' . $sViewReport . '_filter', $aDisplay, TRUE);

        $this->parser->parse('header', $aDisplay);
        $this->parser->parse('eg/qcgibson/report/' . $sViewReport, $aDisplay);
        $this->parser->parse('footer', $aDisplay);
    }

    function groupexcel($sViewReport)
	{

        echo "Hallo Test";
        die;
		$this->load->helper('excel');
		$aSessionSearch = $this->session->userdata($sViewReport . '_search');
		$aSort = $this->session->userdata($sViewReport . '_sort');

		$sCriteria = (!empty($aSessionSearch) ? implode(' AND ', $aSessionSearch) : '');

		//set view report
		$aTotalData = array();
		$aTotalDatas = array();
		//declare variable temporary
		$n_t_qty = 0;
		$n_t_process_1s = 0;
		$n_t_process_2s = 0;
		$n_t_process_1 = 0;
		$n_t_process_2 = 0;
		$n_t_process_3 = 0;
		$n_t_process_4 = 0;
		$n_t_process_5 = 0;
		$n_t_process_6 = 0;
		$n_t_process_1_2 = 0;
		$n_t_process_2_2 = 0;
		$n_t_process_7 = 0;
		$n_t_process_8 = 0;
		$n_t_process_9 = 0;
		$n_t_process_10 = 0;
		$n_t_warehouse = 0;
		$n_t_process_14 = 0;
		if (trim($sViewReport) == 'po') {
			$aDataProduct = $this->Report_model->getListPo($sCriteria, 0, 0, $aSort);
		} elseif (trim($sViewReport) == 'lot') {
			$aDataProduct = $this->Report_model->getListLot($sCriteria, 0, 0, $aSort);
		} elseif (trim($sViewReport) == 'buyer') {
			$aDataProduct = $this->Report_model->getListBuyer($sCriteria, 0, 0, $aSort);
		} elseif (trim($sViewReport) == 'model') {
			$aDataProduct = $this->Report_model->getListModel($sCriteria, 0, 0, $aSort);
		} elseif (trim($sViewReport) == 'color') {
			$aDataProduct = $this->Report_model->getListColor($sCriteria, 0, 0, $aSort);
		}
		$aHeader = array(
			'd_production_date' => 'Month',
			'd_plan_date' => 'Production Plan Date (Input)',
			'd_delivery_date' => 'Production Plan Date (Output)',
			'd_target_date' => 'Export Plan Date',
			's_buyer_name' => 'Buyer',
			's_po_no' => 'PI Number',
			's_po' => 'PO',
			's_lot_no' => 'Lot No',
			's_model' => 'Model',
			's_model_name' => 'Model Name',
			's_color_name' => 'Color',
			's_smodel' => 'Item Code',
			's_location' => 'Location',
			'n_qty' => 'Qty',
			's_cancel_qty' => 'Cancel Qty',
			'n_process_1' => 'WK-I Center Input',
			'n_process_1_2' => 'Body - Center Input',
			'n_process_2' => 'WK-I Center Output',
			'n_process_3' => 'WK-II',
			'n_process_4' => 'WK-II Control Center',
			'n_process_5' => 'Coating-I',
			'n_process_6' => 'Coating-IIA',
			'n_process_7' => 'Coating-IIB',
			'n_process_8' => 'Assembly-I_Control Center',
			'n_process_9' => 'Assembly-II',
			'n_process_10' => 'Packing',
			'n_warehouse' => 'Warehouse Incoming',
			'n_process_14' => 'Warehouse Outgoing'
		);
		$aDatas = array();
		foreach ($aDataProduct as $nRow => $aEachDataProduct) {
			$n_t_qty += $aEachDataProduct['n_qty'];
			$n_t_process_1s += $aEachDataProduct['n_process_1s'];
			$n_t_process_1 += $aEachDataProduct['n_process_1'];
			$n_t_process_1_2 += $aEachDataProduct['n_process_1_2'];
			$n_t_process_2s += $aEachDataProduct['n_process_2s'];
			$n_t_process_2 += $aEachDataProduct['n_process_2'];
			$n_t_process_2_2 += $aEachDataProduct['n_process_2_2'];
			$n_t_process_3 += $aEachDataProduct['n_process_3'];
			$n_t_process_4 += $aEachDataProduct['n_process_4'];
			$n_t_process_5 += $aEachDataProduct['n_process_5'];
			$n_t_process_6 += $aEachDataProduct['n_process_6'];
			$n_t_process_7 += $aEachDataProduct['n_process_7'];
			$n_t_process_8 += $aEachDataProduct['n_process_8'];
			$n_t_process_9 += $aEachDataProduct['n_process_9'];
			$n_t_process_10 += $aEachDataProduct['n_process_10'];
			$n_t_warehouse += $aEachDataProduct['n_warehouse'];
			$n_t_process_14 += $aEachDataProduct['n_process_14'];

			$aData = array();
			foreach ($aEachDataProduct as $sField => $sValue) {
				if ($sViewReport == 'color') {
					if ($sField == 'd_plan_date' || $sField == 'd_delivery_date') {
						continue;
					}
				}
				if (isset($aHeader[$sField])) {
					$aData[$aHeader[$sField]] = $sValue;
				} else {
					$aData[$sField] = $sValue;
				}
			}
			$aDatas[] = $aData;
		}
		if (count($aDataProduct) > 0) {
			foreach ($aDataProduct[0] as $sField => $sValue) {
				if ($sViewReport == 'color') {
					if ($sField == 'd_plan_date' || $sField == 'd_delivery_date') {
						continue;
					}
				}
				if ($sField == 'n_process_1s') break;
				$aTotalData[$sField] = ' ';
			}
		}
		$aTotalData['n_t_process_1s'] = $n_t_process_1s;
		$aTotalData['n_t_process_1'] = $n_t_process_1;
		$aTotalData['n_t_process_1_2'] = $n_t_process_1_2;
		$aTotalData['n_t_process_2s'] = $n_t_process_2s;
		$aTotalData['n_t_process_2'] = $n_t_process_2;
		$aTotalData['n_t_process_2_2'] = $n_t_process_2_2;
		$aTotalData['n_t_process_3'] = $n_t_process_3;
		$aTotalData['n_t_process_4'] = $n_t_process_4;
		$aTotalData['n_t_process_5'] = $n_t_process_5;
		$aTotalData['n_t_process_6'] = $n_t_process_6;
		$aTotalData['n_t_process_7'] = $n_t_process_7;
		$aTotalData['n_t_process_8'] = $n_t_process_8;
		$aTotalData['n_t_process_9'] = $n_t_process_9;
		$aTotalData['n_t_process_10'] = $n_t_process_10;
		$aTotalData['n_t_warehouse'] = $n_t_warehouse;
		$aTotalData['n_t_process_14'] = $n_t_process_14;
		$aTotalData['n_t_qty'] = $n_t_qty;

		$aDatas[] = $aTotalData;

		to_excel_array($aDatas, 'report_group_by_' . $sViewReport);
	}
}
