<?php

declare(strict_types=1);

namespace NhanAZ\DumpIdItem;

use pocketmine\item\Item;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\item\StringToItemParser;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	protected function onEnable(): void {
		$arr1 = [];
		$out1Path = $this->getDataFolder() . "out1.txt";
		$out2Path = $this->getDataFolder() . "out2.txt";
		if (file_exists($out1Path)) {
			unlink($out1Path);
		}
		if (file_exists($out2Path)) {
			unlink($out2Path);
		}
		$out1File = fopen($out1Path, "w");
		$out2File = fopen($out2Path, "w");
		for ($id = -214; $id <= 511; $id++) {
			for ($meta = 0; $meta <= 50; $meta++) {

				$item = $this->stringToItem($id . ":" . $meta);

				if ($item instanceof Item) {

					$format1 = "(" . $item->getTypeId() . ":" . $item->computeTypeData() . ")";
					if (!in_array($format1, $arr1)) {
						fwrite($out1File, $id . ":" . $meta . " " . $item->__toString() . "\n");
						array_push($arr1, $format1);
					}

					$format2 = $item->__toString();
					if (!in_array($format2, $arr1)) {
						fwrite($out2File, $id . ":" . $meta . " " . $item->__toString() . "\n");
						array_push($arr1, $format2);
					}
				}
			}
		}
		fclose($out1File);
		fclose($out2File);
	}

	public function stringToItem(string $string) {
		try {
			$item = StringToItemParser::getInstance()->parse($string) ?? LegacyStringToItemParser::getInstance()->parse($string);
		} catch (LegacyStringToItemParserException $e) {
			$item = "Unknown";
		}
		return $item;
	}
}
