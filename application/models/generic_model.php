<?php

class Generic_model extends CI_Model {

    //makes this to work with columns and without where,limit and offset
    function getData($tablename,$columns_arr, $where_arr, $limit, $offset, $simple = false) {
        if(!empty($tablename))
        {
            if($simple){
                $query = $this->db->get($tablename);
            }else{
                if(empty($columns_arr)) //then all the columns will be selected
                {
                    if(empty($where_arr)) //then the query without where
                    {
                        $query = $this->db->get($tablename,$limit, $offset);
                    }
                    else //where array is there
                    {
                        $query = $this->db->get_where($tablename, $where_arr, $limit, $offset);
                    }
                }
                else //selected columns will be returned
                {
                    $this->db->select(implode(',', $columns_arr));
                    if(empty($where_arr)) //then the query without where
                    {
                        $query = $this->db->get($tablename,$limit, $offset);
                    }
                    else //where array is there
                    {
                        $query = $this->db->get_where($tablename, $where_arr, $limit, $offset);
                    }
                }
            }
        }
        return $query->result();
    }
    function insertData($tablename, $data_arr) {
        try {
            $this->db->insert_batch($tablename, $data_arr);
            return $this->db->insert_id();
        } catch (Exception $e) {
                return $e->getMessage();
            }
    }
    function updateData($tablename,$data_arr,$where_arr)
    {
        //var_dump($where_arr);die;
        return $this->db->update($tablename, $data_arr, $where_arr);
    }

    function deleteData($tablename,$where_arr)
    {
        //echo ($where_arr);die;
        try{
             $this->db->where($where_arr, NULL, FALSE);
            //$this->db->where("id = '32' OR id = '35'", NULL, FALSE);
            $result = $this->db->delete($tablename);
        }catch(Exception $ex){
            $result = $ex->getMessage();
        }
        return $result;
    }
}
?>
