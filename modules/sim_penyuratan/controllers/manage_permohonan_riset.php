<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Manage_Permohonan_Riset
 *
 * @author No-CMS Module Generator
 */
class Manage_Permohonan_Riset extends CMS_Priv_Strict_Controller {

    protected $URL_MAP = array();

    public function index(){
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // initialize groceryCRUD
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud = $this->new_crud();
        //$crud->set_theme('datatables');
        // this is just for code completion
        if (FALSE) $crud = new Extended_Grocery_CRUD();

        // check state & get primary_key
        $state = $crud->getState();
        $state = $crud->getState();
        $state_info = $crud->getStateInfo();
        $primary_key = isset($state_info->primary_key)? $state_info->primary_key : NULL;
        switch($state){
            case 'unknown': break;
            case 'list' : break;
            case 'add' : break;
            case 'edit' : break;
            case 'delete' : break;
            case 'insert' : break;
            case 'update' : break;
            case 'ajax_list' : break;
            case 'ajax_list_info': break;
            case 'insert_validation': break;
            case 'update_validation': break;
            case 'upload_file': break;
            case 'delete_file': break;
            case 'ajax_relation': break;
            case 'ajax_relation_n_n': break;
            case 'success': break;
            case 'export': break;
            case 'print': break;
        }
        
        // unset things 
        $crud->unset_jquery();
        $crud->unset_read();
        // $crud->unset_add();
        // $crud->unset_edit();
        // $crud->unset_list();
        // $crud->unset_back_to_list();
        $crud->unset_print();
        $crud->unset_export();

        $username = $this->cms_user_name();
        $group = $this->db->select('group_name')
                          ->join('main_group_user', 'main_group.group_id = main_group_user.group_id')
                          ->where('user_id',$this->cms_user_id())
                          ->get('main_group')->result();
        foreach ($group as $q){
            $gn = $q->group_name;
        }
        if(isset($gn) && $gn=='Mahasiswa'){
            $crud->unset_edit();
            $crud->unset_delete();
            $crud->where('npm',$username);
        }

        // set model
        // $crud->set_model($this->cms_module_path().'/grocerycrud_permohonan_riset_model');

        // adjust groceryCRUD's language to No-CMS's language
        $crud->set_language($this->cms_language());

        // table name
        $crud->set_table($this->cms_complete_table_name('permohonan_riset'));

        // set subject
        $crud->set_subject('Surat Permohonan Riset');

        // displayed columns on list
        $crud->columns('nomor_surat','nama_mahasiswa','npm','fk_id_jurusan');
        $crud->order_by('id_permohonan_riset', 'DESC');
        // displayed columns on edit operation
        $crud->edit_fields('nomor_surat','lampiran','kepada','di','nama_mahasiswa','npm','fk_id_jurusan','judul_penelitian','bulan','tahun');
        // displayed columns on add operation
        $crud->add_fields('nomor_surat','lampiran','kepada','di','nama_mahasiswa','npm','fk_id_jurusan','judul_penelitian','bulan','tahun');
        
        //Add Action
        $crud->add_action('Download', base_url().'/assets/icon/word.png', base_url().'sim_penyuratan/Manage_Permohonan_Riset/generate/');

        // caption of each columns
        $crud->display_as('nomor_surat','Nomor Surat');
        $crud->display_as('lampiran','Lampiran');
        $crud->display_as('kepada','Kepada');
        $crud->display_as('di','Di');
        $crud->display_as('nama_mahasiswa','Nama Mahasiswa');
        $crud->display_as('npm','NPM');
        $crud->display_as('fk_id_jurusan','Jurusan');
        $crud->display_as('judul_penelitian','Judul Penelitian');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/required_fields)
        // eg:
        //      $crud->required_fields( $field1, $field2, $field3, ... );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->required_fields('kepada','di','nama_mahasiswa','npm','fk_id_jurusan','judul_penelitian');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put required field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/unique_fields)
        // eg:
        //      $crud->unique_fields( $field1, $field2, $field3, ... );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put field validation codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_rules)
        // eg:
        //      $crud->set_rules( $field_name , $caption, $filter );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation (lookup) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation)
        // eg:
        //      $crud->set_relation( $field_name , $related_table, $related_title_field , $where , $order_by );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$crud->set_relation('fk_id_jurusan', $this->cms_complete_table_name('mas_jurusan'), 'nama_jurusan');

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put set relation_n_n (detail many to many) codes here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/set_relation_n_n)
        // eg:
        //      $crud->set_relation_n_n( $field_name, $relation_table, $selection_table, $primary_key_alias_to_this_table,
        //          $primary_key_alias_to_selection_table , $title_field_selection_table, $priority_field_relation );
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put custom field type here
        // (documentation: http://www.grocerycrud.com/documentation/options_functions/field_type)
        // eg:
        $crud->field_type('bulan','hidden',date('m'));
        $crud->field_type('tahun','hidden',date('Y'));
        $crud->field_type('nomor_surat','invisible');
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////




        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put callback here
        // (documentation: httm://www.grocerycrud.com/documentation/options_functions)
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $crud->callback_before_insert(array($this,'before_insert_update'));
        $crud->callback_before_update(array($this,'before_insert_update'));
        $crud->callback_before_delete(array($this,'before_delete'));
        $crud->callback_after_insert(array($this,'after_insert_update'));
        $crud->callback_after_update(array($this,'after_insert_update'));
        $crud->callback_after_delete(array($this,'after_delete'));



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // HINT: Put custom error message here
        // (documentation: httm://www.grocerycrud.com/documentation/set_lang_string)
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $crud->set_lang_string('delete_error_message', 'Cannot delete the record');
        // $crud->set_lang_string('update_error',         'Cannot edit the record'  );
        // $crud->set_lang_string('insert_error',         'Cannot add the record'   );

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // render
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $output = $crud->render();
        $this->view($this->cms_module_path().'/manage_permohonan_riset_view', $output,
            $this->cms_complete_navigation_name('manage_permohonan_riset'));

    }

    public function before_insert_update($post_array){
        $bulan = date('m');
        $tahun = date('Y');
        $data = $this->db->select('MAX(nomor_surat) max, MIN(nomor_surat) min, bulan, tahun')
                         ->where('bulan',$bulan)
                         ->where('tahun',$tahun)
                         ->get($this->cms_complete_table_name('permohonan_riset'))->row();
        if($data->min != 1){
            $post_array['nomor_surat'] = 1;
        }else{
            $post_array['nomor_surat'] = $data->max+1;
        }
        return $post_array;
    }

    public function before_delete($primary_key){

        return TRUE;
    }

    public function after_delete($primary_key){
        return TRUE;
    }

    public function after_insert_update($post_array, $primary_key){

        return TRUE;
    }

    public function generate($id=0){
        $this->load->library('PHPWord');
        $this->load->helper('util');
        $date = date('Y-m-d_H-i-s');
        //if ($id==0);
        $data = $this->db->where('id_permohonan_riset',$id)
                         ->join($this->cms_complete_table_name('mas_jurusan'), 'id_jurusan=fk_id_jurusan')
                         ->get($this->cms_complete_table_name('permohonan_riset'))->result();
        $PHPWord = new PHPWord();

        //Generate Document
        foreach($data as $d):
        $document = $PHPWord->loadTemplate('assets/surat_keluar/Permohonan Penelitian/permohonan_riset.docx');
        $document->setValue('nomor_surat', $d->nomor_surat);
        $document->setValue('lampiran', $d->lampiran);
        $document->setValue('kepada', $d->kepada);
        $document->setValue('di', $d->di);
        $document->setValue('nama_mahasiswa', $d->nama_mahasiswa);
        $document->setValue('npm', $d->npm);
        $document->setValue('jurusan', $d->nama_jurusan);
        $document->setValue('judul_penelitian', $d->judul_penelitian);
        $document->setValue('ketua_jurusan', $d->ketua_jurusan);
        $document->setValue('tanggal', tanggal(date('d-m-Y')));
        $document->setValue('bulan', bulan_romawi(date('m')));
        $document->setValue('tahun', date('Y'));

        ////open file////
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $filename = $d->npm.'-Surat_Permohonan_Riset.docx';
        $document->save($filename);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
        unlink($filename); // deletes the temporary file
        exit;
        endforeach;
        ////end of open file////

        ///save file////
        //$document->save('assets/docs/'.$d->nama_kar.'-'.$d->id_pegawai.'.docx');
        //redirect(base_url().'assets/docs/'.$d->nama_kar.'-'.$d->id_pegawai.'.docx', 'assets/manage_pegawai');
        ///end of save file////
    }



}