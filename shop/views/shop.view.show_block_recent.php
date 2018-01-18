<?php
/**
* @package Недавно просмотренные товары
* @author Dmitry Petukhov (https://user.diafan.ru/user/weissfl)
* @copyright Copyright (c) 2018 by Dmitry Petukhov
* @license MIT License (https://en.wikipedia.org/wiki/MIT_License)
*
* Шаблон блока недавно просмотренных товаров
*
* Шаблонный тег <insert name="show_recent" module="shop" [count="количество выводимых товаров (по умолчанию 3)"]
* [images="количество_изображений (по умолчанию 1)"] [images_variation="тег_размера_изображений"]
* [days="сколько дней помнить о просмотренных товарах (по умолчанию 3)"]
* [only_module="только_на_странице_модуля"] [template="шаблон"]>:
* 
*/
if (!defined('DIAFAN'))
{
    include dirname(dirname(dirname(__FILE__))).'/includes/404.php';
}

if (empty($result["rows"]))
{
	return false;
}

//заголовок блока
echo '<h2>'.$this->diafan->_('Недавно просмотренные товары').'</h2>';

//товары в разделе
if (!empty($result["rows"]))
{	
	echo '<div class="shop-pane">';	

	foreach ($result["rows"] as $row)
	{		      		
		echo '<div class="shop-col">';
		echo '<div class="js_shop shop-item shop">';

		//изображения товара
		if (!empty($row["img"]))
		{
			echo '<div class="shop_img shop-photo">';
			foreach ($row["img"] as $img)
			{
				switch ($img["type"])
				{
					case 'animation':
						echo '<a href="'.BASE_PATH.$img["link"].'" rel="prettyPhoto[gallery'.$row["id"].'shop]">';
						break;
					case 'large_image':
						echo '<a href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'">';
						break;
					default:
						echo '<a href="'.BASE_PATH_HREF.$img["link"].'">';
						break;
				}
				echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'" image_id="'.$img["id"].'" class="js_shop_img">';
					echo '<span class="shop-photo-labels">';
						if (!empty($row['hit']))
						{
							echo '<img src="' . BASE_PATH . Custom::path('img/label_hot.png').'"/>';
						}
						if (!empty($row['action']))
						{
							echo '<img src="' . BASE_PATH . Custom::path('img/label_special.png').'"/>';
						}
						if (!empty($row['new']))
						{
							echo '<img src="' . BASE_PATH . Custom::path('img/label_new.png').'"/>';					
						}
					echo '</span>';				
				echo '</a> ';

				//кнопка "Отложить"				
				echo '<span class="js_shop_wishlist shop_wishlist shop-like'.(! empty($row["wish"]) ? ' active' : '').'">&nbsp;</span>';
			}
			echo '</div>';
		}

		//название и ссылка товара
		echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="shop-item-title">'.$row["name"].'</a>';

		//вывод производителя
		if (!empty($row["brand"]))
		{
			echo '<div class="shop_brand">';
			echo $this->diafan->_('Производитель').': ';
			echo '<a href="'.BASE_PATH_HREF.$row["brand"]["link"].'">'.$row["brand"]["name"].'</a>';
			echo '</div>';
		}		

		//артикул
		if (!empty($row["article"]))
		{
			echo '<div class="shop_article">'.$this->diafan->_('Артикул').': <span class="shop_article_value">'.$row["article"].'</span></div>';
		}

		//краткое описание товара
		if (!empty($row["anons"]))
		{
			echo '<p>'.$row['anons'].'</p>';
		}

		// параметры товара
		if (!empty($row["param"]))
		{
			echo '<p>';
				echo $this->get('param', 'shop', array("rows" => $row["param"], "id" => $row["id"]));
			echo '</p>';
		}

		//кнопка "Купить"
		echo $this->get('buy_form', 'shop', array("row" => $row, "result" => $result));		

		//теги товара
		if (!empty($row["tags"]))
		{
			echo $row["tags"];
		}

		//скидка на товар
		if (!empty($row["discount"]))
		{
			echo '<div class="shop_discount">'.$this->diafan->_('Скидка').': <span class="shop_discount_value">'.$row["discount"].' '.$row["discount_currency"].($row["discount_finish"] ? ' ('.$this->diafan->_('до').' '.$row["discount_finish"].')' : '').'</span></div>';
		}

		//сравнение товаров
		if(empty($result["hide_compare"]))
	  	{	 	
	   		echo $this->get('compare_form', 'shop', $row);
	  	}
		
		echo '</div>';

		echo '</div>';
	}	

	echo '</div>';
}
