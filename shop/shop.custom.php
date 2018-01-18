<?php
/**
* @package Недавно просмотренные товары
* @author Dmitry Petukhov (https://user.diafan.ru/user/weissfl)
* @copyright Copyright (c) 2018 by Dmitry Petukhov
* @license MIT License (https://en.wikipedia.org/wiki/MIT_License)
*/
class Shop extends Controller
{
	/**
	 * Шаблонная функция: выводит недавно просмотренные товары
	 * 
	 * @param array $attributes атрибуты шаблонного тега
	 * template - шаблон тега (файл modules/shop/views/shop.view.show_block_recent_**template**.php; по умолчанию шаблон modules/shop/views/shop.view.show_block_recent.php)
	 * images_variation - размер изображения для вывода в блоке; по умолчанию preview
	 * images - количество изображений для товара; по умолчанию 1
	 * count - количество товаров в блоке, по умолчанию 3
	 * days - сколько дней помнить просмотренные товары, по умолчанию 3
	 * only_module - показывать блок только на странице модуля
	 * 
	 * @return void
	 */
	new public function show_recent($attributes)
	{
		$attributes = $this->get_attributes($attributes, 'template', 'images_variation', 'images', 'days', 'only_module', 'count');
		if($attributes['only_module'] && $this->diafan->_site->module != 'shop')
		{
			return;
		}
		$images_config['variation'] = $attributes['images_variation'] ? $attributes['images_variation'] : 'preview';
		$images_config['count'] = $attributes['images'] ? $attributes['images'] : 1;
		$days = $attributes['days'] ? $attributes['days'] : 3;
		$count = $attributes['count'] ? $attributes['count'] : 3;
		$this->model->show_recent($images_config, $count, $days);
		$this->model->result();
		
		echo $this->diafan->_tpl->get('show_block_recent', 'shop', $this->model->result, $attributes['template']);
	}
}