<?php
/**
 * Aloha Editor widget
 *
 * @author Vincent Gabriel
 * v 1.0
 */
class AlohaEditor extends CInputWidget {
	/**
	 * Name attribute for the text area if we use one
	 */
	public $name = '';
	/**
	 * Can be basic, advanced, full
	 */
	public $toolbar = 'basic';
	/**
	 * Html options that will be assigned to the text area
	 */
	public $htmlOptions = array();
	/**
	 * Aloha editor settings
	 */
	public $alohaSettings = array('baseUrl' => null);
	/**
	 * Array of plugins you'd like to include
	 */
	public $plugins = array('common/format');
	/**
	 * Debug mode
	 * Will enable the aloha editor profiler
	 */
	public $debug = false;
	/**
	 * Show sidebar
	 */
	public $showSidebar = false;
	/**
	 * Editor width
	 */
	public $width = '100px';
	/**
	 * Editor height
	 */
	public $height = '100px';
	/**
	 * Selector to change content into editable aloha editors
	 */
	public $selector = '';
	/**
	 * Show an editor when widget is called
	 */
	public $showTextArea = false;
	/**
	 * Display editor
	 */
    public function run() {
	
		// Resolve name and id
		// when this is being used with a text area
		list($name, $id) = $this->resolveNameID();

		// Get assets dir
        $baseDir = dirname(__FILE__);
        $assets = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'assets');

		// Publish required assets
		$cs = Yii::app()->getClientScript();
		
		$jsFile = 'aloha.js';
		$filePath = $assets.'/lib/' . $jsFile;
		$plugins = array('common/format' => 'common/format');
		
		// Basic Toolbar
		$basicToolbar = array(
			'common/abbr', 
			'common/align',
			'common/block',
			'common/characterpicker',
			'common/commands',
			'common/contenthandler',
			'common/dom-to-xhtml',
			'common/highlighteditables',
			'common/horizontalruler',
			'common/image',
			'common/link',
			'common/list',
			'common/paste',
			'common/table',
			'common/undo',
		);
		
		// Advanced Toolbar
		// Some throw errors so they are commented out
		$advanceToolbar = array(
			'extra/attributes',
			'extra/browser',
			'extra/cite',
			//'extra/comments',
			'extra/draganddropfiles',
			'extra/flag-icons',
			'extra/formatlesspaste',
			'extra/googletranslate',
			'extra/headerids',
			//'extra/hints',
			//'extra/linkbrowser',
			//'extra/linkchecker',
			//'extra/listenforcer',
			//'extra/metaview',
			//'extra/numerated-headers',
			//'extra/profiler',
			'extra/ribbon',
			//'extra/sourceview',
			//'extra/speak',
			//'extra/toc',
			//'extra/vie',
			//'extra/wai-lang',
			//'extra/zemanta', 
		);
		
		// Based on toolbar we will add more plugins
		switch($this->toolbar) {
			case 'basic':
				$plugins = array_merge($plugins, $basicToolbar);
			break;
			
			case 'advanced':
				$plugins = array_merge($plugins, $basicToolbar, $advanceToolbar);
			break;
		}
		
		// Debug mode?
		if($this->debug) {
			$plugins['extra/profiler'] = 'extra/profiler';
		}
		
		// Include all the plugins
		foreach($this->plugins as $val) {
			$plugins[$val] = $val;
		}
		// We post this like this since we need to include data-aloha-plugins
		// which cclientscript does not support currently
		echo '<script type="text/javascript" src="'.$filePath.'" data-aloha-plugins="'.implode(',', $plugins).'"></script>';
		// Register the css through the aloha cdn network as they update it often
		$cs->registerCSSFile('http://cdn.aloha-editor.org/current/css/aloha.css');
		
		// Include the id to the html text area if we will be using it
        $this->htmlOptions['id'] = $id;

		// Set the width and height of the text area
        if (!array_key_exists('style', $this->htmlOptions)) {
            $this->htmlOptions['style'] = "width:{$this->width};height:{$this->height};";
        }
		
		// Base url for aloha settings
		if(!isset($this->alohaSettings['baseUrl']) || !$this->alohaSettings['baseUrl']) {
			$this->alohaSettings['baseUrl'] = $assets . '/';
		}

		// Show or hide sidebar
		if(!$this->showSidebar) {
			$this->alohaSettings['sidebar'] = array('disabled' => true);
		}

		// Aloha editor settings
		$alohaSettings = count($this->alohaSettings) ? $this->alohaSettings : array();
		$_aSettings = '';
		foreach($alohaSettings as $key => $value) {
			$_aSettings .= "Aloha.settings.$key = ".CJSON::encode($value).";\n";
		}
		// The aloha editor selector
		$selector = $this->selector ? $this->selector : ('#'.$id);

		        $js =<<<EOP
		Aloha.ready( function() {
			
EOP;
	
		if($_aSettings) {

			$js .= <<<EOF
				$_aSettings
EOF;
		}
		
		$js .= "Aloha.jQuery('{$selector}').aloha();\n";
		
		$js .= "\n});";
		
		// The only way to get the sidebar to disappear
		if(!$this->showSidebar) {
			$js .= "\nAloha.ready( function() {setTimeout('Aloha.Sidebar.right.hide();', 10);});\n";
		}
		
		// Register js code
		$cs->registerScript('Yii.'.get_class($this).'#'.$selector, $js, CClientScript::POS_END);
	
		// Print text area if we want to
		$html = '';
		if($this->showTextArea) {
			// Do we have a model
			if($this->hasModel()) {
	            $html = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
	        } else {
	            $html = CHtml::textArea($name, $this->value, $this->htmlOptions);
	        }
		}
		
		echo $html;
    }
}