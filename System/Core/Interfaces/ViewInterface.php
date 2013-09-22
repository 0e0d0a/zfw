<?php

/**
 * @author Alexander German (zerro)
 */
interface ViewInterface {
    /**
	 * @access public
	 * @param string $name module name
	 * @return controller
	 */
	public function loadController($name);

	/**
	 * @access public
	 * @param array $vars (optional) tpl variables
	 * @return string
	 */
	public function parse($vars=array());

    /**
     * @return layoutI18n
     */
    public function i18n();

    /**
     * @return layoutDraw
     */
    public function draw();

    /**
     * @return layoutInput
     */
    public function input();

    /**
     * Draw asterisk if field is mandatory
     *
     * @param string $field		field name
     * @param string $name		optional, rulset name
     * @return string			HTML code
     */
    public function asterisk($field, $name='');

    /**
     * @access public
     * @param bool $isPage redirect to 403 page required
     * @return string denied msg if !$isPage
     */
    public function denied($isPage=true);
}