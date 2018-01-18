<?php
/**
* @package Недавно просмотренные товары
* @author Dmitry Petukhov (https://user.diafan.ru/user/weissfl)
* @copyright Copyright (c) 2018 by Dmitry Petukhov
* @license MIT License (https://en.wikipedia.org/wiki/MIT_License)
*/
class Shop_model extends Model
{
	/**
	 * Получает список недавно просмотренных товаров
	 * 
	 * @param array $images_config настройки показа изображения
	 *      ['variation'] - размер изображения для вывода в блоке
	 *      ['count'] - количество изображений для товара
	 * @param integer $count - количество товаров для показа
	 * @param integer $days - сколько дней помнить показанный товар
	 * 
	 * @return void
	 */
	new public function show_recent($images_config, $count, $days)
	{
		if(isset($_COOKIE['seen_goods']))
		{
			$ids = explode(',',$_COOKIE['seen_goods']);
			if(!is_array($ids))
			{
				$ids = array($ids);
			}
			$ids = array_map('intval',$ids);
			if(($i = array_search($this->diafan->_route->show,$ids)) !== false)
			{
				 unset($ids[$i]);
				 if(empty($ids))
				 {
					 return;
				 }
			}
			array_splice($ids, $count);
			$goods = DB::query_fetch_key('SELECT s.id, s.[name], s.timeedit, s.[anons], s.site_id, s.brand_id, s.no_buy, s.article, s.hit, s.new, s.action, s.is_file FROM {shop} AS s where s.id in ('.implode(',',$ids).')','id');
			$this->elements($goods,'block',$images_config);
			$this->result['rows'] = array();
			foreach($ids as $id)
			{
				$this->prepare_data_element($goods[$id]);
				$this->format_data_element($goods[$id]);
				$this->result['rows'][] = $goods[$id];
			}
		}
		
		if($this->diafan->_site->module == 'shop' && $this->diafan->_route->show)
		{
			if(!empty($ids))
			{
				array_unshift($ids,$this->diafan->_route->show);
			}
			else
			{
				$ids = array($this->diafan->_route->show);
			}
			setcookie('seen_goods',implode(',',$ids),time()+$days*86400,'/');
		}
	}
}