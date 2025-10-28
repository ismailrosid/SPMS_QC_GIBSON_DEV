<?php

class QcGibson extends Controller
{
    var $sUsername;
    var $sLevel;
    var $nRowsPerPage = 50;

    // Properti Upload
    var $sUploadPath = '';
    var $sErrorMessage = '';
    var $aDivision = array();
    
    // Properti Konfigurasi Data
    const DIVISION = 'AG'; // Tetapkan Divisi QC ini

    function QcGibson()
    {
        parent::Controller();
        $this->load->library('session');

        if (!$this->session->userdata('b_ag_cheker_gibson')) show_error('Access Denied');

        $this->sUsername = $this->session->userdata('s_username');
        $this->sLevel    = $this->session->userdata('s_level');

        // MEMUAT MODEL QC GIBSON YANG BARU
        $this->load->model('Qc_gibson_model');
        // $this->load->model('Setup_model'); // Tidak diperlukan lagi karena tidak ada pemetaan fasa

        $this->load->model('Util_model');
        $this->load->helper('url');
        $this->load->library('parser');
        $this->load->library('form');
        $this->load->library('validation');
        $this->validation->set_error_delimiters('<div class="message_error">', '</div>');
        $this->load->library('validatejs');
        
        // Inisialisasi properti Upload (Folder berdasarkan tanggal hari ini)
        $this->sUploadPath = './docs/' . date('Ymd');
        $this->aDivision = $this->config->item('division'); 
    }

// -----------------------------------------------------------------------
// UTAMA & SCAN
// -----------------------------------------------------------------------

    function index($sMessage = '')
    {
        $sDisplayMessage = '';
        if ($sMessage == '1') $sDisplayMessage = 'Data berhasil disimpan.';
        if ($sMessage == '2') $sDisplayMessage = 'Upload file gagal.';
        if ($sMessage == '3') $sDisplayMessage = 'Data berhasil dihapus.';

        $aDisplay = array(
            'baseurl'          => base_url(),
            'basesiteurl'      => site_url(),
            'siteurl'          => site_url() . '/ag/qcgibson/',
            'PAGE_TITLE'       => 'SPMS-G. QC Gibson Report',
            'sGlobalUserName'  => $this->sUsername,
            'sGlobalUserLevel' => $this->sLevel,
            'MESSAGES'         => $sDisplayMessage,
        );

        $this->parser->parse('header', $aDisplay);
        $this->parser->parse('ag/qcgibson/index', $aDisplay);
        $this->parser->parse('footer', $aDisplay);
    }

// ... Kode sebelumnya di Controller QcGibson ...

    function scan()
    {
        $aDisplay = array(
            'baseurl'          => base_url(),
            'basesiteurl'      => site_url(),
            // PERBAIKAN: Pastikan siteurl konsisten
            'siteurl'          => site_url() . '/ag/qcgibson/',
            'PAGE_TITLE'       => 'SPMS-G. Scan QC Gibson',
            'sGlobalUserName'  => $this->sUsername,
            'sGlobalUserLevel' => $this->sLevel,
        );
        $this->parser->parse('header', $aDisplay);
        // PERBAIKAN: Path view harus 'ag/qc_gibson/scan'
        $this->parser->parse('ag/qcgibson/scan', $aDisplay); 
        $this->parser->parse('footer', $aDisplay);
    }

// -----------------------------------------------------------------------
// FUNGSI UPLOAD
// -----------------------------------------------------------------------

    /**
     * Menjalankan proses upload file TXT ke server.
     */

// FUNGSI UPLOAD & SAVE (GABUNGAN)
// -----------------------------------------------------------------------

    /**
     * Menjalankan proses upload file TXT, memproses isinya, dan langsung menyimpannya ke database.
     */
    function doupload()
    {
        $sElementName = 'f_file_name';
        $sAllowedType = 'txt';
        
        // --- PERBAIKAN FATAL ERROR: Simpan ke variabel sementara ---
        $sUploadedFileName = '';
        if (isset($_FILES[$sElementName]['name'])) {
            $sUploadedFileName = $_FILES[$sElementName]['name'];
        }

        if (empty($sUploadedFileName)) {
            $this->sErrorMessage = 'Tidak ada file yang dipilih.';
            return redirect("ag/qc_gibson/index/2");
        }
        // --- AKHIR PERBAIKAN FATAL ERROR ---

        $sFileName = $sUploadedFileName; // Gunakan variabel yang sudah diamankan

        $this->_forcePath($this->sUploadPath);

        $aConfig = array();
        $aConfig['upload_path'] = $this->sUploadPath;
        $aConfig['allowed_types'] = $sAllowedType;
        $aConfig['overwrite'] = TRUE;
        $aConfig['file_name'] = $sFileName;
        $this->load->library('upload', $aConfig);
        
        // --- 1. PROSES UPLOAD FILE ---
        if (!$this->upload->do_upload($sElementName)) {
            $this->sErrorMessage = $this->upload->display_errors();
        }
        
        // File berhasil diupload
        $aFileInfo = $this->upload->data();
        $sSavedFileName = $aFileInfo['file_name'];
        $aSerialError = array();
        $nRowsProcessed = 0;
        
        // --- 2. PROSES BACA DAN SIMPAN DATA ---
        
        $aFileData = $this->_readfile($sSavedFileName);
        
        foreach ($aFileData as $sFileData) {
        
            
            // Format file TXT: serial_no;unknown;date;user_scan;location;[judgment]
            $aExData = explode(';', trim($sFileData));

            $aData = array();
            

            $aData['judgment'] = 'good';
            
            $aData['uploaded_by'] = $this->sUsername;
            
            // Lakukan INSERT atau UPDATE
            $is_exists = $this->Qc_gibson_model->is_exists($aData['serial_no']); // cek data sudah ada atau belum di tt_checker gibson
            
            if ($is_exists) {
                $rSave = $this->Qc_gibson_model->update_data($aData['serial_no'], $aData);
            } else {
                $rSave = $this->Qc_gibson_model->insert_data($aData);
            }
            
            if ($rSave == FALSE) {
                $aSerialError[] = $aData['serial_no'];
            }
            $nRowsProcessed++;
        }
        

        // 1. insert nama file ke tt_checker_gibson
        // 2. Validasi nomor 1:
        // query validasi didalam loop baris txt saat mau di input 
        // select s_serial_no from public.tt_production where s_serial_no = '27012300002' and s_buyer in 
        // (select s_code from tv_customer_gibson) r ma

        // paham ga maksud query ini ?
        // jadi query ini memastikan bahwa serial ini punya gibson , nah itu kan mail punya function is_exist 
        // kurang lebih eksekusi nya kaya gitu, 
        // kalo punya mail kan utnuk nentuin dia insert/update
        // berarti mail bikin lagi yang seperti is_exist untuk ngecek 
        // is_serial_gibson misal, kalo dia false , rollback terus kasih response bahwa serial 'xxxxxxx' bukan punya gibson 

        // paham ???? pahaam pak

        // isi response error kondisi 2: dalam bahasa inggris
        //     "Serial number yang anda sematkan bukan milik gibson : 
        //         27012300002,
        //         27012300003,
        //         27012300004,
        //         27012300005,
        //     form upload hanya mengizinkan serial number milik buyer gibson !"

        // 3. validasi nomor 2
        // Assembly-II sudah terisi tanggal d_process_9 tt_production
        // $RowToValidate_Assy_II = select d_process_9 from tt_production where s_serial_no = '27012300002';
        // if(empty($RowToValidate_Assy_II) || empty($RowToValidate_Assy_II->d_process_9)){
        //     return error;
        // }

        // isi response error konsisi 3, dalam bahasa inggris
        // "serial number gibson belum dinyatakan melewati Assembly-II : 
        //         27012300002,
        //         27012300003,
        //         27012300004,
        //         27012300005"
        // harap hubungi staff Assembly-II untuk melakukan konfirmasi status WIP.

        // ======================================================
        // 1. 





    }

//     /**
//      * Menampilkan data yang telah diupload (untuk preview dan filtering).
//      */


//     /**
//      * Menyimpan data yang diupload ke database tt_checker_gibson.
//      */
    function saveupload()
    {

    }

// // -----------------------------------------------------------------------
// // HELPER METHOD
// // -----------------------------------------------------------------------

//     /**
//      * Membaca konten dari file TXT yang diupload.
//      */
    function _readfile($sFileName)
    {
        $aData = array();
        $fSource = @fopen($this->sUploadPath . '/' . $sFileName, 'r');
        if (!$fSource) {
            $this->sErrorMessage .= "Gagal membaca file: $sFileName. Pastikan file ada di path ".$this->sUploadPath;
            return array();
        }
        while (!feof($fSource)) {
            $aData[] = fgets($fSource);
        }
        fclose($fSource);
        return $aData;
    }

    /**
     * Memastikan direktori upload ada.
     */
    function _forcePath($sPath)
    {
        // Fungsi ini membuat path direktori secara rekursif jika belum ada.
        if (!is_dir($sPath)) {
            mkdir($sPath, 0777, TRUE); // TRUE untuk rekursif
        }
    }
}