<?php

class Easy_page_links_acc
{
	var $name			=	'Easy Page Links';
	var $id				=	'easy_page_links';
	var $version		=	'1.0';
	var $description	=	'Displays a modal window when inserting a link to allow the user to easily insert links to Pages or other URLs.';
	var $sections		=	array();
	var $vars			=	array();
	
	function __construct()
	{
		$this->EE =& get_instance();
		$this->EE->load->library('table');
		$this->vars['id'] = $this->id;
	}
	
	function update_extension() { return TRUE; }
	
	function set_sections()
	{
		$this->vars[] = $this->pages_array();
		$this->sections['Easy Page Links'] = $this->EE->load->view('view', $this->vars, TRUE);
	}
	
	// Taken from Pages module and modified slightly (mainly removing unrequired data)
	function pages_array()
	{
		$pages = $this->EE->config->item('site_pages');

		if ($pages === FALSE OR count($pages[$this->EE->config->item('site_id')]['uris']) == 0)
			return;

		natcasesort($pages[$this->EE->config->item('site_id')]['uris']);
		$vars['pages'] = array();

		$i = 0;
		$previous = array();
		$spcr = '<img src="'.PATH_CP_GBL_IMG.'clear.gif" border="0"  width="24" height="14" alt="" title="" />';
		$indent = $spcr.'<img src="'.PATH_CP_GBL_IMG.'cat_marker.gif" border="0"  width="18" height="14" alt="" title="" />';

		foreach($pages[$this->EE->config->item('site_id')]['uris'] as $entry_id => $url)
		{
			$url = ($url == '/') ? '/' : '/'.trim($url, '/').'/';

			$vars['pages'][$entry_id]['entry_id'] = $entry_id;
			$vars['pages'][$entry_id]['page'] = $url;
			$vars['pages'][$entry_id]['title'] = $this->page_name($entry_id);
			$vars['pages'][$entry_id]['indent'] = '';

			if ($url != '/')
            {
            	$x = explode('/', trim($url, '/'));

            	for($i=0, $s=count($x); $i < $s; ++$i)
            	{
            		if (isset($previous[$i]) && $previous[$i] == $x[$i])
            			continue;

					$this_indent = ($i == 0) ? '' : str_repeat($spcr, $i-1).$indent;
					$vars['pages'][$entry_id]['indent'] = $this_indent;
            	}

            	$previous = $x;
            }
		}
		return $vars;
	}
	
	// Returns page title for display purposes
	function page_name($entry_id)
	{
		$query = $this->EE->db->query("SELECT title FROM exp_channel_titles WHERE entry_id = '" . $this->EE->db->escape_str($entry_id) . "'");
		return $query->row('title');
	}
}