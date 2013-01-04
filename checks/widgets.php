<?php

class WidgetsCheck implements themecheck {
	protected $error = array();

	function check( $php_files, $css_files, $other_files ) {

		$ret = true;

		// combine all the php files into one string to make it easier to search
		$php = implode( ' ', $php_files );
		checkcount();
		
		// no widgets registered or used...
		if ( strpos( $php, 'register_sidebar' ) === false && strpos( $php, 'dynamic_sidebar' ) === false ) {
			$this->error[] = __( "<span class='tc-lead tc-recommended'>RECOMMENDED</span>: This theme contains no sidebars/widget areas. See <a href='http://codex.wordpress.org/Widgets_API'>Widgets API</a>", "themecheck" );
			$ret = true;
		}
		
		if ( strpos( $php, 'register_sidebar' ) !== false && strpos( $php, 'dynamic_sidebar' ) === false ) {
			$this->error[] = __( "<span class='tc-lead tc-required'>REQUIRED</span>: The theme appears to use <strong>register_sidebar()</strong> but no <strong>dynamic_sidebar()</strong> was found. See: <a href='http://codex.wordpress.org/Function_Reference/dynamic_sidebar'>dynamic_sidebar</a><pre> &lt;?php dynamic_sidebar( \$index ); ?&gt;</pre>", "themecheck" );
			$ret = false;			
		}
		
		if ( strpos( $php, 'register_sidebar' ) === false && strpos( $php, 'dynamic_sidebar' ) !== false ) {			
			$this->error[] = __( "<span class='tc-lead tc-required'>REQUIRED</span>: The theme appears to use <strong>dynamic_sidebars()</strong> but no <strong>register_sidebar()</strong> was found. See: <a href='http://codex.wordpress.org/Function_Reference/register_sidebar'>register_sidebar</a><pre> &lt;?php register_sidebar( \$args ); ?&gt;</pre>", "themecheck" );
			$ret = false;			
		}
		return $ret;
	}

	function getError() { return $this->error; }
}
$themechecks[] = new WidgetsCheck;