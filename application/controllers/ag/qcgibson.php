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

        if (!$this->session->userdata('b_ag_checker_gibson')) {
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

    function masterdefect()
    {
        // Load all defect categories from model
        $aCategoryDefects = $this->Qc_gibson_model->get_all_category_defect();

        $aDisplay = array(
            'baseurl'          => base_url(),
            'basesiteurl'      => site_url(),
            'siteurl'          => site_url() . '/ag/qcgibson/',
            'PAGE_TITLE'       => 'SPMS-G. Master Code Defect',
            'sGlobalUserName'  => $this->sUsername,
            'sGlobalUserLevel' => $this->sLevel,
            'category_defects' => $aCategoryDefects  // pass to view
        );

        $this->parser->parse('header', $aDisplay);
        $this->parser->parse('ag/qcgibson/masterdefect', $aDisplay);
        $this->parser->parse('footer', $aDisplay);
    }

    function savedefect()
    {
        header('Content-Type: application/json');

        // Get input values from POST
        $categoryCode = trim($this->input->post('category_code', true));
        $defectCode   = trim($this->input->post('defect_code', true));
        $defectName   = trim($this->input->post('defect_name', true));

        $errorGroups = array();

        // Validate required fields
        if (empty($categoryCode)) $errorGroups[] = 'Category must be selected.';
        if (empty($defectCode))   $errorGroups[] = 'Defect code cannot be empty.';
        if (empty($defectName))   $errorGroups[] = 'Defect name cannot be empty.';

        if (!empty($errorGroups)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Please correct the following errors:',
                'errors'  => $errorGroups,
                'success' => array()
            ));
            exit();
        }

        // Prepare data array for insert/update
        $aData = array(
            'category_code' => $categoryCode,
            'defect_code'   => $defectCode,
            'defect_name'   => $defectName,
            // 'created_by'    => $this->sUsername,
            // 'created_at'    => date('Y-m-d H:i:s')
        );

        // Start database transaction
        $this->db->trans_start();

        // Check if defect code already exists
        $exists = $this->Qc_gibson_model->get_category_defect_by_code($defectCode);

        if ($exists) {
            $rSave = $this->Qc_gibson_model->update_defect($defectCode, $aData);
            $action = 'updated';
        } else {
            $rSave = $this->Qc_gibson_model->insert_defect($aData);
            $action = 'saved';
        }

        if (!$rSave) {
            $this->db->trans_rollback();
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Failed to ' . $action . ' defect code.',
                'errors'  => array(),
                'success' => array()
            ));
            exit();
        }

        // Commit transaction
        $this->db->trans_complete();

        // Return success response
        echo json_encode(array(
            'status'  => 'success',
            'message' => 'The defect code has been successfully ' . $action . '!',
            'errors'  => array(),
            'success' => array($defectCode)
        ));
        exit();
    }

    function scan()
    {
        $aDisplay = array(
            'baseurl'          => base_url(),
            'basesiteurl'      => site_url(),
            'siteurl'          => site_url() . '/ag/qcgibson/',
            'PAGE_TITLE'       => 'SPMS-G. Scan QC Gibson',
            'sGlobalUserName'  => $this->sUsername,
            'sGlobalUserLevel' => $this->sLevel,
        );

        // Parse the header, main view, and footer
        $this->parser->parse('header', $aDisplay);
        $this->parser->parse('ag/qcgibson/scan', $aDisplay);
        $this->parser->parse('footer', $aDisplay);
    }

   
    function savedefect()
    {
        header('Content-Type: application/json');

        // Get input values from POST
        $categoryCode = trim($this->input->post('category_code', true));
        $defectCode   = trim($this->input->post('defect_code', true));
        $defectName   = trim($this->input->post('defect_name', true));

        $errorGroups = array();

        // Validate required fields
        if (empty($categoryCode)) $errorGroups[] = 'Category must be selected.';
        if (empty($defectCode))   $errorGroups[] = 'Defect code cannot be empty.';
        if (empty($defectName))   $errorGroups[] = 'Defect name cannot be empty.';

        if (!empty($errorGroups)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Please correct the following errors:',
                'errors'  => $errorGroups,
                'success' => array()
            ));
            exit();
        }

        // Prepare data array for insert/update
        $aData = array(
            'category_code' => $categoryCode,
            'defect_code'   => $defectCode,
            'defect_name'   => $defectName,
            // 'created_by'    => $this->sUsername,
            // 'created_at'    => date('Y-m-d H:i:s')
        );

        // Start database transaction
        $this->db->trans_start();

        // Check if defect code already exists
        $exists = $this->Qc_gibson_model->get_category_defect_by_code($defectCode);

        if ($exists) {
            // --- INSERT OLD RECORD TO HISTORY FIRST ---
            // Try to get the full existing defect row (includes sysid)
            $old = $this->Qc_gibson_model->get_defect($defectCode);

            if ($old && is_array($old)) {
                // Prepare history array. Table: thst_gibson_defect_code
                // Columns assumed: main_sysid, defect_code, defect_name, category_code, changed_by, changed_at
                $aHist = array(
                    'main_sysid'    => isset($old['sysid']) ? $old['sysid'] : '',
                    'defect_code'   => isset($old['defect_code']) ? $old['defect_code'] : '',
                    'defect_name'   => isset($old['defect_name']) ? $old['defect_name'] : '',
                    'category_code' => isset($old['category_code']) ? $old['category_code'] : '',
                    // use controller username if available, else empty
                    'changed_by'    => isset($this->sUsername) ? $this->sUsername : ''
                );

                // Insert history using same style: set NULL if empty, else set value
                foreach ($aHist as $sField => $sValue) {
                    if ($sValue === '') {
                        $this->db->set($sField, 'NULL', FALSE);
                    } else {
                        $this->db->set($sField, $sValue);
                    }
                }
                // set changed_at server time
                $this->db->set('changed_at', 'NOW()', FALSE);

                $rHist = $this->db->insert('thst_gibson_defect_code');

                if ($rHist === FALSE) {
                    // failed to insert history -> rollback and return error
                    $this->db->trans_rollback();
                    echo json_encode(array(
                        'status' => 'error',
                        'message' => 'Failed to archive previous defect data before update.',
                        'errors'  => array(),
                        'success' => array()
                    ));
                    exit();
                }
            } else {
                // couldn't fetch old defect row (unexpected) -> rollback & error
                $this->db->trans_rollback();
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Existing defect found but failed to retrieve its current data.',
                    'errors'  => array(),
                    'success' => array()
                ));
                exit();
            }

            // After history insert succeed -> continue with update
            $rSave = $this->Qc_gibson_model->update_defect($defectCode, $aData);
            $action = 'updated';
        } else {
            $rSave = $this->Qc_gibson_model->insert_defect($aData);
            $action = 'saved';
        }

        if (!$rSave) {
            $this->db->trans_rollback();
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Failed to ' . $action . ' defect code.',
                'errors'  => array(),
                'success' => array()
            ));
            exit();
        }

        // Commit transaction
        $this->db->trans_complete();

        // Return success response
        echo json_encode(array(
            'status'  => 'success',
            'message' => 'The defect code has been successfully ' . $action . '!',
            'errors'  => array(),
            'success' => array($defectCode)
        ));
        exit();
    }

    function savedirect()
    {
        header('Content-Type: application/json');

        // Get input values from POST
        $serialNo   = trim($this->input->post('serial_no', true));
        $defectCode = trim($this->input->post('defect_code', true));
        $country    = trim($this->input->post('country', true));
        $judgmentPost = trim($this->input->post('judgement', true));
        $judgment = ($judgmentPost === "1") ? 'good' : 'nogood';

        // Frontend double validation in backend
        
        if ($judgmentPost === "0" && ($defectCode === "NULL" || empty($defectCode))) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'If Judgement is NOT PASS, please select a Defect Code.',
                'errors' => array()
            ));
            exit();
        }

        if ($judgmentPost === "1" && $defectCode !== "NULL" && !empty($defectCode)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'You selected a Defect Code. Please change Judgement to NOT PASS.',
                'errors' => array()
            ));
            exit();
        }

        // Validate required field
        if (empty($serialNo)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Serial number cannot be empty.',
                'errors' => array()
            ));
            exit();
        }

        // Set default values if empty
        if (empty($judgment)) $judgment = 'good';
        if (empty($country))  $country = NULL;
        if (empty($defectCode)) $defectCode = NULL;

        // Check if serial number exists in Gibson database
        $isGibson = $this->Qc_gibson_model->get_serials_gibson(array($serialNo));
        if (empty($isGibson)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'This serial number is not a Gibson product.',
                'errors' => array()
            ));
            exit();
        }

        // Prepare data array for insert/update
        $aData = array(
            'serial_no'   => $serialNo,
            'defect_code' => $defectCode,
            'country'     => $country,
            'judgment'    => $judgment,
            'guitar_type' => 'AG', // hardcode AG
            'uploaded_by' => $this->sUsername,
            'source'      => 'direct',
            'date'        => date('Y-m-d H:i:s')
        );

        // Start database transaction
        $this->db->trans_start();

        // Insert or update based on existence
        if ($this->Qc_gibson_model->is_exists($serialNo)) {
            $rSave = $this->Qc_gibson_model->update_data($serialNo, $aData);
            $action = 'updated';
        } else {
            $rSave = $this->Qc_gibson_model->insert_data($aData);
            $action = 'saved';
        }

        // Rollback if failed to save/update
        if (!$rSave) {
            $this->db->trans_rollback();
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Failed to ' . $action . ' the serial number.',
                'errors' => array()
            ));
            exit();
        }

        // Complete transaction
        $this->db->trans_complete();

        // Return success response
        echo json_encode(array(
            'status' => 'success',
            'message' => 'The serial number has been successfully ' . $action . '!'
        ));
        exit();
    }


    function doupload()
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);
        header('Content-Type: application/json');

        $sElementName = 'f_file_name';

        // Check if file was uploaded
        if (!isset($_FILES[$sElementName]['name']) || empty($_FILES[$sElementName]['name'])) {
            echo json_encode(array('status' => 'error', 'message' => 'No file was uploaded.', 'errors' => array()));
            exit();
        }

        // Clean the uploaded file name to remove unwanted characters
        $sUploadedFileName = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", $_FILES[$sElementName]['name']);
        $sTmpPath = $_FILES[$sElementName]['tmp_name'];

        // Only allow .txt files
        if (strtolower(pathinfo($sUploadedFileName, PATHINFO_EXTENSION)) != 'txt') {
            echo json_encode(array('status' => 'error', 'message' => 'Only .txt files are allowed.', 'errors' => array()));
            exit();
        }

        // Ensure the upload directory exists
        $this->_forcePath($this->sUploadPath);
        $sDestPath = $this->sUploadPath . '/' . $sUploadedFileName;

        // Move uploaded file to the designated upload folder
        if (!move_uploaded_file($sTmpPath, $sDestPath)) {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to move the file to the upload folder.', 'errors' => array()));
            exit();
        }

        // Read uploaded file line by line
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

            // Get judgment column from file, default to 'nogood' if empty/invalid
            $judgment = trim($parts[4]);
            // convert judgment ke lowercase dan validasi
            $judgmentLower = strtolower($judgment);
            if ($judgmentLower != 'good' && $judgmentLower != 'nogood') {
                $judgment = 'nogood';
            } else {
                $judgment = $judgmentLower;
            }

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

        // Get list of valid Gibson serial numbers from database
        $validSerials = $this->Qc_gibson_model->get_serials_gibson($allSerials);

        $notGibsonSerials = array(); // serial numbers not valid for Gibson
        $serialErrors = array();     // serials failed to insert/update

        // Start a single transaction for all serials
        $this->db->trans_start();

        foreach ($allSerials as $sn) {

            // Skip invalid Gibson serial numbers
            if (!in_array($sn, $validSerials)) {
                $notGibsonSerials[] = $sn;
                continue;
            }

            $aData = $dataToSave[$sn];

            // Insert or update
            if ($this->Qc_gibson_model->is_exists($sn)) {
                $rSave = $this->Qc_gibson_model->update_data($sn, $aData);
            } else {
                $rSave = $this->Qc_gibson_model->insert_data($aData);
            }

            if (!$rSave) {
                log_message('error', 'Failed to save serial: ' . $sn);
                $serialErrors[] = $sn;
            }
        }

        // If any serial failed to save, rollback all
        if (!empty($serialErrors) || !empty($notGibsonSerials)) {
            $this->db->trans_rollback(); // rollback everything
            $errorGroups = array();

            // Message for serials that are not Gibson products
            if (!empty($notGibsonSerials)) {
                $title = count($notGibsonSerials) == 1 ?
                    'The serial number is not a Gibson product' :
                    'Some serial numbers are not Gibson products';
                $errorGroups[] = array('title' => $title, 'items' => $notGibsonSerials);
            }

            // Message for failed insert/update
            if (!empty($serialErrors)) {
                $title = count($serialErrors) == 1 ?
                    'Failed to save or update the serial number' :
                    'Failed to save or update some serial numbers';
                $errorGroups[] = array('title' => $title, 'items' => $serialErrors);
            }


            echo json_encode(array(
                'status' => 'error',
                'message' => 'No data has been saved due to errors.',
                'errors' => $errorGroups,
                'success' => array()
            ));
        } else {
            // Commit transaction if all serials succeeded
            $this->db->trans_complete();
            $msg = count($allSerials) == 1 ?
                'The serial number has been successfully saved/updated!' :
                'All serial numbers have been successfully saved/updated!';
            echo json_encode(array(
                'status' => 'success',
                'message' => $msg,
                'file_name' => $sUploadedFileName,
                'success' => $allSerials
            ));
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
