<?php

class QcGibson extends Controller
{
    var $sUsername;
    var $sLevel;
    var $nRowsPerPage = 50;
    var $sUploadPath = '';
    var $sErrorMessage = '';
    var $aDivision = array();

    const DIVISION = 'AG';

    function QcGibson()
    {
        parent::Controller();

        $this->load->library('session');

        if (!$this->session->userdata('b_ag_cheker_gibson')) {
            show_error('Access Denied');
        }

        $this->sUsername = $this->session->userdata('s_username');
        $this->sLevel    = $this->session->userdata('s_level');

        $this->load->model('Qc_gibson_model');
        $this->load->model('Util_model');

        $this->load->helper('url');
        $this->load->library('parser');
        $this->load->library('form');
        $this->load->library('validation');
        $this->load->library('validatejs');

        $this->validation->set_error_delimiters('<div class="message_error">', '</div>');

        // $this->sUploadPath = './docs/' . date('Ymd');
        $this->sUploadPath = APPPATH . 'docs/' . date('Ymd') . '/';
        $this->aDivision   = $this->config->item('division');
    }

    function index($sMessage = '')
    {
        $messages = array('1' => 'Data berhasil disimpan.', '2' => 'Upload file gagal.', '3' => 'Data berhasil dihapus.');
        $aDisplay = array(
            'baseurl'          => base_url(),
            'basesiteurl'      => site_url(),
            'siteurl'          => site_url() . '/ag/qcgibson/',
            'PAGE_TITLE'       => 'SPMS-G. QC Gibson Report',
            'sGlobalUserName'  => $this->sUsername,
            'sGlobalUserLevel' => $this->sLevel,
            'MESSAGES'         => isset($messages[$sMessage]) ? $messages[$sMessage] : ''
        );

        $this->parser->parse('header', $aDisplay);
        $this->parser->parse('ag/qcgibson/index', $aDisplay);
        $this->parser->parse('footer', $aDisplay);
    }

    function scan()
    {
        $aDisplay = array(
            'baseurl'          => base_url(),
            'basesiteurl'      => site_url(),
            'siteurl'          => site_url() . '/ag/qcgibson/',
            'PAGE_TITLE'       => 'SPMS-G. Scan QC Gibson',
            'sGlobalUserName'  => $this->sUsername,
            'sGlobalUserLevel' => $this->sLevel
        );

        $this->parser->parse('header', $aDisplay);
        $this->parser->parse('ag/qcgibson/scan', $aDisplay);
        $this->parser->parse('footer', $aDisplay);
    }

    function direct()
    {
        $aDisplay = array(
            'baseurl'          => base_url(),
            'basesiteurl'      => site_url(),
            'siteurl'          => site_url() . '/ag/qcgibson/',
            'PAGE_TITLE'       => 'SPMS-G. Direct Scan Serial Gibson',
            'sGlobalUserName'  => $this->sUsername,
            'sGlobalUserLevel' => $this->sLevel
        );

        $this->parser->parse('header', $aDisplay);
        $this->parser->parse('ag/qcgibson/direct', $aDisplay);
        $this->parser->parse('footer', $aDisplay);
    }

    function savedirect()
    {
        header('Content-Type: application/json');

        $serialNo  = trim($this->input->post('serial_no', true));
        $scanDate  = trim($this->input->post('date', true));
        $userScan  = trim($this->input->post('user_scan', true));
        $location  = trim($this->input->post('location', true));
        $judgment  = trim($this->input->post('judgment', true));

        if (empty($serialNo)) {
            echo json_encode(array('status' => 'error', 'message' => 'Serial number cannot be empty.', 'errors' => array()));
            exit();
        }

        if (empty($scanDate))  $scanDate  = date('Y-m-d H:i:s');
        if (empty($userScan))  $userScan  = $this->sUsername;
        if (empty($location))  $location  = 'C';
        if (empty($judgment))  $judgment  = 'good';

        $isGibson = $this->Qc_gibson_model->get_serials_gibson(array($serialNo));
        if (empty($isGibson)) {
            echo json_encode(array('status' => 'error', 'message' => 'This serial number is not a Gibson product.', 'errors' => array()));
            exit();
        }

        $isPending = $this->Qc_gibson_model->get_pending_assembly_ii(array($serialNo));
        if (!empty($isPending)) {
            echo json_encode(array('status' => 'error', 'message' => 'This serial number has not completed the Assembly-II process.', 'errors' => array()));
            exit();
            // update message update
        }

        $aData = array(
            'serial_no'   => $serialNo,
            'date'        => $scanDate,
            'user_scan'   => $userScan,
            'location'    => $location,
            'judgment'    => $judgment,
            'uploaded_by' => $this->sUsername,
            'source'      => 'direct'
        );

        $this->db->trans_start();

        if ($this->Qc_gibson_model->is_exists($serialNo)) {
            $rSave = $this->Qc_gibson_model->update_data($serialNo, $aData);
        } else {
            $rSave = $this->Qc_gibson_model->insert_data($aData);
        }

        if (!$rSave) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 'error', 'message' => 'Failed to save the serial number to the database.', 'errors' => array()));
            exit();
        }

        $this->db->trans_complete();

        echo json_encode(array('status' => 'success', 'message' => 'The serial number has been successfully saved!'));
        exit();
    }

    function doupload()
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);
        header('Content-Type: application/json');

        $sElementName = 'f_file_name';

        if (!isset($_FILES[$sElementName]['name']) || empty($_FILES[$sElementName]['name'])) {
            echo json_encode(array('status' => 'error', 'message' => 'No file was uploaded.', 'errors' => array()));
            exit();
        }

        $sUploadedFileName = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", $_FILES[$sElementName]['name']);
        $sTmpPath = $_FILES[$sElementName]['tmp_name'];

        // pastikan file .txt
        if (strtolower(pathinfo($sUploadedFileName, PATHINFO_EXTENSION)) != 'txt') {
            echo json_encode(array('status' => 'error', 'message' => 'Only .txt files are allowed.', 'errors' => array()));
            exit();
        }

        $this->_forcePath($this->sUploadPath);
        $sDestPath = $this->sUploadPath . '/' . $sUploadedFileName;

        if (!move_uploaded_file($sTmpPath, $sDestPath)) {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to move the file to the upload folder.', 'errors' => array()));
            exit();
        }

        $lines = $this->_readfile($sUploadedFileName);
        $allSerials  = array();
        $dataToSave  = array();

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line == '') continue;

            $parts = explode(';', $line);
            if (count($parts) < 5) {
                echo json_encode(array('status' => 'error', 'message' => 'Invalid file format (missing columns).', 'errors' => array()));
                exit();
            }

            $serialNo = trim($parts[0]);
            $scanDate = trim($parts[1]);
            $userScan = trim($parts[2]);
            $location = trim($parts[3]);
            $judgment = trim($parts[4]);

            $allSerials[] = $serialNo;
            $dataToSave[$serialNo] = array(
                'serial_no'   => $serialNo,
                'date'        => $scanDate,
                'user_scan'   => $userScan,
                'location'    => $location,
                'judgment'    => $judgment,
                'uploaded_by' => $this->sUsername,
                'source'      => $sUploadedFileName
            );
        }

        $validSerials   = $this->Qc_gibson_model->get_serials_gibson($allSerials);
        $pendingSerials = $this->Qc_gibson_model->get_pending_assembly_ii($allSerials);

        $notGibsonSerials = array();
        $assyIIError      = array();
        $serialErrors     = array();
        $successSerials   = array();

        foreach ($allSerials as $sn) {

            // Skip invalid serial
            if (!in_array($sn, $validSerials)) {
                $notGibsonSerials[] = $sn;
                continue;
            }

            if (in_array($sn, $pendingSerials)) {
                $assyIIError[] = $sn;
                continue;
            }

            $aData = $dataToSave[$sn];
            $this->db->trans_start();

            if ($this->Qc_gibson_model->is_exists($sn)) {
                $rSave = $this->Qc_gibson_model->update_data($sn, $aData);
            } else {
                $rSave = $this->Qc_gibson_model->insert_data($aData);
            }

            if (!$rSave) {
                $this->db->trans_rollback();
                log_message('error', 'Failed to save serial: ' . $sn);
                $serialErrors[] = $sn;
            } else {
                $this->db->trans_complete();
                $successSerials[] = $sn;
            }
        }

        $errorGroups = array();
        if (!empty($notGibsonSerials)) $errorGroups[] = array('title' => 'Some serial numbers are not Gibson products', 'items' => $notGibsonSerials);
        if (!empty($assyIIError)) $errorGroups[] = array('title' => 'Some serial numbers have not completed Assembly-II', 'items' => $assyIIError);
        if (!empty($serialErrors)) $errorGroups[] = array('title' => 'Failed to save or update some serial numbers', 'items' => $serialErrors);

        if (!empty($errorGroups)) {
            $msg = !empty($successSerials) ? 'Some serials saved, some failed.' : 'All entries failed.';
            echo json_encode(array('status' => 'error', 'message' => $msg, 'errors' => $errorGroups, 'success' => $successSerials));
        } else {
            echo json_encode(array('status' => 'success', 'message' => 'All serial numbers have been successfully saved!', 'file_name' => $sUploadedFileName, 'success' => $successSerials));
        }

        exit();
    }

    function _readfile($sFileName)
    {
        $aData = array();
        $path = $this->sUploadPath . '/' . $sFileName;
        $fSource = @fopen($path, 'r');
        if (!$fSource) {
            $this->sErrorMessage .= "Failed to read file: " . $path;
            return array();
        }
        while (!feof($fSource)) {
            $aData[] = fgets($fSource);
        }
        fclose($fSource);
        return $aData;
    }

    function _forcePath($sPath)
    {
        if (!is_dir($sPath)) {
            mkdir($sPath, 0777, TRUE);
        }
    }
}
