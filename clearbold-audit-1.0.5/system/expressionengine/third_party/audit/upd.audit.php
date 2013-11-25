<?php if (! defined('BASEPATH')) exit('Invalid file request');

/**
 * Audit Update Class
 *
 * @package   Audit
 * @author    Mark J. Reeves <mjr@clearbold.com>
 * @copyright Copyright (c) 2013 Clearbold, LLC
 */
class Audit_upd {

    var $version = '1.0.5';

    /**
     * Constructor
     */
    function __construct()
    {
        $this->EE =& get_instance();
    }

    // --------------------------------------------------------------------

    /**
     * Install
     */
    function install()
    {
        $this->EE->db->insert('modules', array(
            'module_name'        => 'Audit',
            'module_version'     => $this->version,
            'has_cp_backend'     => 'y',
            'has_publish_fields' => 'n'
        ));

        return TRUE;
    }

    /**
     * Uninstall
     */
    function uninstall()
    {
        $this->EE->db->where('module_name', 'Audit')->delete('modules');
        $this->EE->db->where('class', 'Audit_mcp')->delete('actions');

        return TRUE;
    }

    /**
     * Update
     */
    function update($current = '')
    {
        // necessary to get EE to update the version number
        return TRUE;
    }

}