<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

//delayed function must return a string
if(empty($arResult))
	return "";

// Short on screen, complete for search engines: every link stays in the markup
// (schema.org BreadcrumbList), the middle of a long path is folded behind "…",
// phones show only the link one level up.
$items = array();
foreach ($arResult as $index => $item)
{
	$items[] = array(
		'TITLE' => htmlspecialcharsex($item["TITLE"]),
		'LINK' => ($item["LINK"] <> "" && $index != count($arResult) - 1) ? $item["LINK"] : '',
	);
}
$itemSize = count($items);
$lastLink = -1;
foreach ($items as $index => $item)
{
	if ($item['LINK'] !== '')
		$lastLink = $index;
}
// fold everything between "Главная" and the last two links
$foldFrom = 1;
$foldTo = $lastLink - 2;
$hasFold = $foldTo >= $foldFrom;

$strReturn = '<nav class="mk-crumbs" aria-label="Навигационная цепочка"><ol class="mk-crumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">';

foreach ($items as $index => $item)
{
	$classes = array('mk-crumbs__item');
	if ($index === 0)
		$classes[] = 'mk-crumbs__item--home';
	if ($hasFold && $index >= $foldFrom && $index <= $foldTo)
		$classes[] = 'mk-crumbs__item--folded';
	if ($index === $lastLink)
		$classes[] = 'mk-crumbs__item--parent';

	if ($hasFold && $index === $foldFrom)
	{
		$strReturn .= '<li class="mk-crumbs__item mk-crumbs__item--more"><button type="button" class="mk-crumbs__more" aria-expanded="false" aria-label="Показать весь путь">…</button></li>';
	}

	if ($item['LINK'] !== '')
	{
		$name = $index === 0
			? '<svg class="mk-crumbs__home" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 10.5 12 4l8 6.5V20h-5.5v-5.5h-5V20H4z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg><span class="mk-crumbs__name" itemprop="name">'.$item['TITLE'].'</span>'
			: '<span class="mk-crumbs__name" itemprop="name">'.$item['TITLE'].'</span>';
		$strReturn .= '<li class="'.implode(' ', $classes).'" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'
			.'<a href="'.$item['LINK'].'" itemprop="item">'.$name.'</a>'
			.'<meta itemprop="position" content="'.($index + 1).'"></li>';
	}
	else
	{
		$classes[] = 'mk-crumbs__item--current';
		$strReturn .= '<li class="'.implode(' ', $classes).'" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">'
			.'<span class="mk-crumbs__name" itemprop="name">'.$item['TITLE'].'</span>'
			.'<meta itemprop="position" content="'.($index + 1).'"></li>';
	}
}

$strReturn .= '</ol></nav>';

return $strReturn;
