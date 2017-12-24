<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$city = 'Самара';

function takePropFilter($iblock_id, $tag_prop, $region_prop, $site_region = '', $filter = ''){
	$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
	$arFilter = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();

	    $VALUES = array();
	    $res_prop_tags = CIBlockElement::GetProperty($iblock_id, $arFields['ID'], "sort", "asc", array("CODE" => $tag_prop));
	    while ($ob_prop_tags = $res_prop_tags->GetNext())
	    {
	        $VALUES['TAGS']['IDS'][] = $ob_prop_tags['VALUE'];
	    }	
	    $res_prop_region = CIBlockElement::GetProperty($iblock_id, $arFields['ID'], "sort", "asc", array("CODE" => $region_prop));
	    while ($ob_prop_regions = $res_prop_region->GetNext())
	    {
	        $VALUES['REGIONS']['IDS'][] = $ob_prop_regions['VALUE'];
	    }
	    if(!empty($VALUES['TAGS']['IDS'])){    
		    foreach($VALUES['TAGS']['IDS'] as $VALUE){
				$prop_name = CIBlockElement::GetByID($VALUE);
				if($ar_res = $prop_name->GetNext()){
					$VALUES['TAGS']['NAMES'][] = $ar_res['NAME'];
				}   	
		    }
		}
	    if(!empty($VALUES['REGIONS']['IDS'])){  		
		    foreach($VALUES['REGIONS']['IDS'] as $VALUE){
				$prop_name = CIBlockElement::GetByID($VALUE);
				if($ar_res = $prop_name->GetNext()){
					$VALUES['REGIONS']['NAMES'][] = $ar_res['NAME'];
				}   	
		    }
	    }    
	    $arFullInfo[] = 	[
	    					'ID' => $arFields['ID'],
	    					'NAME' => $arFields['NAME'],
	    					'TAGS_NAMES' => $VALUES['TAGS']['NAMES'],
	    					'TAGS_IDS' => $VALUES['TAGS']['IDS'],
	    					'REGION_IDS' => $VALUES['REGIONS']['IDS'],
	    					'REGION_NAMES' => $VALUES['REGIONS']['NAMES'],
	    				];
	}
	$arrTrueIDs;
	if(!empty($arFullInfo)){
		foreach($arFullInfo as $fl){
			if(!empty($fl['TAGS_IDS']) && !empty($fl['REGION_NAMES'])){
				if(in_array($site_region, $fl['REGION_NAMES'])){
					foreach($fl['TAGS_IDS'] as $tn){
						$arrTrueIDs['TAGS'][] = $tn;
					}
				}
			}
			if($filter){
				if(in_array($filter, $fl['TAGS_NAMES']) && in_array($site_region, $fl['REGION_NAMES'])){
					$arrTrueIDs['ARTICLES'][] = $fl['ID'];
				}
			}

		}
	}
	$arrFilterResult['TAGS'] = array("=ID" => array_unique($arrTrueIDs['TAGS']));
	$arrFilterResult['ARTICLES'] = array("=ID" => $arrTrueIDs['ARTICLES']);
	return $arrFilterResult;
}


$arrFilterTags = takePropFilter(10,'ATT_TAGS','ATT_REGION','','');
$artFill = $arrFilterTags['ARTICLES'];
$tagFill = $arrFilterTags['TAGS'];?>




<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("",""),
		"FILTER_NAME" => "tagFill",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "12",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array("",""),
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?><br>
 <br>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("",""),
		"FILTER_NAME" => "artFill",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "10",
		"IBLOCK_TYPE" => "products",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array("",""),
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>