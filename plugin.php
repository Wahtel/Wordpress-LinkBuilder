<?php
/*
 * Plugin Name: WordPress Link Builder
 * Plugin Uri: 
 * Description: WordPress Link Builder is a easy way to create links, just in a few seconds
 * Version: 1.0
 * Author: Wahtel
 * Author URI:
 */
  
if (!class_exists('FestiPlugin')) {
	require_once dirname(__FILE__).'/common/FestiPlugin.php';
}

class WordPressLinkBuilderFestiPLugin extends FestiPLugin
{
	public $postTypes = array(
		'post'
	);
	
	protected function onFrontendInit()
	{
		$this->addFilterListener('the_content', 'editContentFilter');
		
	} // end onFrontendInit
	
	
	public function getKeyWords()
	{
		$options = $this->getOptions('linkBuilderOptions');

		$keyWords = array();

		foreach ($options['linkbuilder'] as $key => $item) {

			$keyWords[$key] = array(
			
				'key' => $item['word'],
				'url' => $item['link']
			);
		}
		return $keyWords;
	} // end getKeyWords
	
	
	private function _isAllowedPostType()
	{
		$postId = get_the_ID();
		
		$postType = get_post_type($postId);
		
		return in_array($postType, $this->postTypes);
	} // end _isAllowedPostType
	
	public function editContentFilter($content)
	{
		if (!$this->_isAllowedPostType()) {
			return $content;
		}
		
		$keyWords = $this->getKeyWords();
		
		foreach ($keyWords as $item) {
			
			$vars = array(
				'key' => $item['key'],
				'url' => $item['url']
			);
			
			$replaceLink = $this->fetch('link_builder_content.phtml', $vars);
			
			$content = str_ireplace($item['key'], $replaceLink, $content); 
			
			}
		return $content;
	} // end editContentFilter
	
	
	
	protected function onBackendInit()
	{
		$this->addActionListener('admin_menu', 'themeOptionsPanelAction');
		$this->addActionListener('admin_print_scripts', 'onInitJsAction');
		$this->addActionListener('admin_print_styles', 'onInitCssAction');
	} // end onBackendInit
	
	public function onInitJsAction()
	{
		$this->onEnqueueJsFileAction('jquery');
		
		wp_enqueue_script(
			'deleteDiv',
	        plugins_url('static/js/delete.js', __FILE__),
			array('jquery')
		);
		
		wp_enqueue_script(
			'addInput',
	        plugins_url('/static/js/addInputs.js', __FILE__),
			array('jquery')
		);
		
	} // end onInitJsAction
	
	public function onInitCssAction()
	{
		wp_enqueue_style(
			'festi-link-builder-styles',
			plugins_url('/static/styles/style.css', __FILE__)
		);
	} // end onInitCssAction
	
	public function themeOptionsPanelAction()
	{
		add_submenu_page(
			'edit.php',
			'Keyword',
			'Link Builder',
			'manage_options',
  	 		'Keyword',
  	 		 array(&$this, 'displayPageSettings'));
	} // end themeOptionsPanelAction
	
	public function displayPageSettings()
	{
		if ($this->hasOptionsInRequest()) {
			$this->updateOptions('linkBuilderOptions', $_POST);
		}
		
		$options = $this->getOptions('linkBuilderOptions');

		//var_dump($options);
		
		if (!$options) {
			$options = array(
				'linkbuilder' => array(
					0 => array(
						'word' => '',
						'link' => ''
					)
				)
			);
		}

		$vars = array(
			'url' => $this->getUrl(),
			'options' => $options
		);
		
		$form = $this->fetch('form.phtml', $vars);
	
		echo $form;

	} // end displayPageSettings
	
	public function hasOptionsInRequest()
	{
		return array_key_exists('linkbuilder', $_POST);
	} // end hasOptionsInRequest
	
}

$name = new WordPressLinkBuilderFestiPLugin(__FILE__);