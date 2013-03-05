<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Requires jquery in head of view
 *
 */
class MY_Profiler extends CI_Profiler {

 	function __construct($config = array())
 	{
 		parent::__construct($config);
 	}

	// --------------------------------------------------------------------

	/**
	 * Run the Profiler
	 *
	 * @access	private
	 * @return	string
	 */
	function run()
	{
		$output = <<<ENDJS
<script type="text/javascript" language="javascript">
// < ![CDATA[
    $(function() {
		var profiler_html = $('#codeigniter_profiler').clone();
		var profiler_bar_html = $('#profiler_bar').clone();

        $('#codeigniter_profiler, #profiler_bar').remove();

		$('body').prepend(profiler_bar_html);
		$('body').append(profiler_html);

		$('#codeigniter_profiler').hide();
		$('#profiler_btn').click(function(){
			$('#codeigniter_profiler').toggle();

			if($('#codeigniter_profiler').is(':visible')) {
				$('#profiler_btn').text('HIDE PROFILER');
			} else {
				$('#profiler_btn').text('SHOW PROFILER');
			}

			return false;
		});
		$('#profiler_close_btn').click(function(){
			$('#profiler_bar, #codeigniter_profiler').hide();
			return false;
		});
    });
// ]]>
</script>
ENDJS;

		$output .= "<div id='profiler_bar' style='background-color: green; width: 150px; height: 20px; padding: 2px; position: absolute; top: 0; left: 20px; text-align: center; color: #FFF; font-family: Verdana, Geneva, sans-serif; filter: alpha(opacity=30); -moz-opacity: 0.30; opacity: 0.30;'><a href='#' id='profiler_btn' style='color: #FFF; text-decoration: none;'>SHOW PROFILER</a><a href='#' id='profiler_close_btn' style='color: #FFF; text-decoration: none; margin-left:10px'>X</a></div>";

		$output .= "<div id='codeigniter_profiler' style='clear:both;background-color:#fff;padding:10px;'>";
		$fields_displayed = 0;

		foreach ($this->_available_sections as $section)
		{
			if ($this->_compile_{$section} !== FALSE)
			{
				$func = "_compile_{$section}";
				$output .= $this->{$func}();
				$fields_displayed++;
			}
		}

		if ($fields_displayed == 0)
		{
			$output .= '<p style="border:1px solid #5a0099;padding:10px;margin:20px 0;background-color:#eee">'.$this->CI->lang->line('profiler_no_profiles').'</p>';
		}

		$output .= '</div>';

		return $output;
	}

}

// END MY_Profiler class

/* End of file MY_Profiler.php */
/* Location: ./application/libraries/MY_Profiler.php */
