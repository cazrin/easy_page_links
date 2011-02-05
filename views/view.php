<script type="text/javascript">
	$("#accessoryTabs a.<?=$id?>").parent().hide();
	
	$(window).load(function() {
		$('.btn_a').unbind().addClass('epl_link');

		$('.epl_link').click(function(e) {
			e.preventDefault();
			show_epl_dialog($(this).parents('div.html').find('textarea'));
		});
		
		$('textarea.markItUpEditor').keydown(function(e) {
			if(e.ctrlKey && e.which == 65) // Keydown event for access key Ctrl + A
				show_epl_dialog($(this));
		});
	});

	function insert_link(textarea, open, close)
	{
		var start = textarea[0].selectionStart;
		var end = textarea[0].selectionEnd;
		var selectedText = textarea.val().substring(start, end);
		var replacement = open + selectedText + close;

		textarea.val(textarea.val().substring(0, start) + replacement + textarea.val().substring(end, textarea.val().length));
	}
	
	function show_epl_dialog(pressed)
	{
		$('#epl_url').val(''); // Clear the value

		$('#epl_pages').dialog({
			buttons: {
				'Insert Link': function() {
					$(this).dialog('close');
					insert_link(pressed, '<a href="' + $('#epl_url').val() + '">', "</a>");
				}
			},
			dialogClass: 'epl_dialog',
			modal: true,
			title: 'Insert Link',
			zIndex: 10000 // Will make dialog display on top of Write Mode
		});

		$('#epl_pages a').click(function(e) {
			e.preventDefault();
			$('#epl_url').val($(this).attr('href'));
		});

		$('.epl_dialog button').addClass('submit'); // CP style for insert button
	}
</script>

<style type="text/css">

.publish_field { background: none; border: 0; margin: 1em 0 0; padding: 0; }
.mainTable a:link { text-decoration: none; }

</style>

<div id="epl_pages">

<?php

if(isset($pages) && count($pages) != 0)
{
	$this->table->set_template(array(
		'table_open'	=> '<table class="mainTable" cellspacing="0" cellpadding="0" border="0" style="width: 100%;">',
		'row_start'		=> '<tr class="even">',
		'row_alt_start'	=> '<tr class="odd">'
	));

	$this->table->set_heading(lang('page'));

	foreach($pages as $page)
	{
		$this->table->add_row($page['indent'] . '<a href="' . $page['page'] . '">' . $page['title'] . '</a>');
	}

	echo $this->table->generate();
}

?>

	<div class="publish_field">

		<label class="label" for="epl_url"><URL><?php echo lang('url'); ?>:</label>
		<input id="epl_url" type="text">
	
	</div>

</div>