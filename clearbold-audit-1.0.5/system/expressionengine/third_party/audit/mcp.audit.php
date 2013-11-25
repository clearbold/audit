<?php if (! defined('BASEPATH')) exit('Invalid file request');

/**
 * Audit Module CP Class for EE2
 *
 * @package   Audit
 * @author    Mark J. Reeves <mjr@clearbold.com>
 * @copyright Copyright (c) 2013 Clearbold, LLC
 */
class Audit_mcp {

    function __construct()
    {
        $this->EE =& get_instance();
    }

    function index()
    {
        $this->EE->cp->set_variable('cp_page_title', lang('audit_module_name'));


        $this->EE->load->library('table');
        $this->EE->table->set_columns(array(
            'site_id' => array('header' => '<span style="white-space: nowrap;">Site ID</span>'),
            'member_id' => array('header' => '<span style="white-space: nowrap;">Member ID</span>'),
            'username' => array('header' => '<span style="white-space: nowrap;">Username</span>'),
            'group_name' => array('header' => '<span style="white-space: nowrap;">Member Group</span>'),
            'item_type' => array('header' => '<span style="white-space: nowrap;">Item Type</span>'),
            'item_id' => array('header' => '<span style="white-space: nowrap;">Item ID</span>'),
            'item_title' => array('header' => '<span style="white-space: nowrap;">Item Name</span>'),
            'ip_address' => array('header' => '<span style="white-space: nowrap;">IP Address</span>'),
            'timestamp' => array('header' => '<span style="white-space: nowrap;">Timestamp</span>'),
            'user_agent' => array('header' => '<span style="white-space: nowrap;">User Agent</span>')
        ));
        $this->EE->table->set_base_url('C=addons_modules&M=show_module_cp&module=audit');
        $data = $this->EE->table->datasource('_datasource');
        return $data['table_html'] . $data['pagination_html'];
    }

    function _datasource($state)
    {
        //var_dump($state['offset']);
        $offset = 0;
        if ($state['offset'] != 0)
            $offset = (int)$state['offset'];
        $results = $this->EE->db->query("SELECT * FROM exp_audit_log ORDER BY timestamp desc LIMIT ?,?",array($offset,20));
        $count_results = $this->EE->db->query("SELECT * FROM exp_audit_log ORDER BY timestamp desc");

        $total_rows = $count_results->num_rows();

        $rows = array();
        foreach($results->result_array() as $row)
        {
            $site_id = $row['site_id'];
            $member_id = $row['member_id'];
            $username = $row['username'];
            $group_name = $row['group_name'];
            $item_type = '';
            switch ($row['item_type'])
            {
                case 'cp_login':
                    $item_type = 'Control Panel Login';
                    break;
                case 'cp_logout':
                    $item_type = 'Control Panel Logout';
                    break;
                case 'login':
                    $item_type = 'Front-end Login';
                    break;
                case 'logout':
                    $item_type = 'Front-end Logout';
                    break;
                case 'entry_delete':
                    $item_type = 'Entry Deleted';
                    break;
                case 'entry_update':
                    $item_type = 'Entry Updated';
                    break;
                case 'new_entry':
                    $item_type = 'Entry Created';
                    break;
                case 'template_edit':
                    $item_type = 'Template Edited';
                    break;
                case 'member_create':
                    $item_type = 'Member Created';
                    break;
                case 'member_delete':
                    $item_type = 'Member Deleted';
                    break;
                case 'member_edit':
                    $item_type = 'Member Edited';
                    break;
            }
            $item_id = $row['item_id'];
            $item_title = $row['item_title'];
            $ip_address = $row['ip_address'];
            $timestamp = $this->EE->localize->set_human_time($row['timestamp']);
            $user_agent = $row['user_agent'];

            $rows[] = array(
                'site_id' => $site_id,
                'member_id' => $member_id,
                'username' => $username,
                'group_name' => $group_name,
                'item_type' => $item_type,
                'item_id' => $item_id,
                'item_title' => $item_title,
                'ip_address' => $ip_address,
                'timestamp' => $timestamp,
                'user_agent' => $user_agent
            );
        }
        return array(
            'rows' => $rows,
            'pagination' => array(
                'page_query_string' => TRUE,
                'base_url'    => $this->_full_url(),
                'per_page'   => 20,
                'total_rows' => $total_rows
            )
        );
    }

    function _full_url()
    {
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
        $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);

        $non_offset_uri = explode('&tbl_offset=',$_SERVER['REQUEST_URI']);
        $non_offset = $non_offset_uri[0];

        return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $non_offset;
    }
}
// END CLASS

/* End of file mcp.module_name.php */
/* Location: ./system/expressionengine/third_party/modules/module_name/mcp.module_name.php */