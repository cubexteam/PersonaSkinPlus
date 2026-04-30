<?php

/**
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @author  SantianDev
 * @link    https://github.com/PresentKim
 * @link    https://github.com/SantianDev
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\personaskin;

use pocketmine\network\mcpe\convert\SkinAdapter;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\plugin\PluginBase;
use Throwable;

class Main extends PluginBase{

	private ?SkinAdapter $originalAdapter = null;

	protected function onEnable() : void{
		$dataFolder = $this->getDataFolder();
		if(is_dir($dataFolder) && count(scandir($dataFolder)) <= 2){
			rmdir($dataFolder);
		}

		$typeConverter = TypeConverter::getInstance();

		$this->originalAdapter = $typeConverter->getSkinAdapter();
		$this->getLogger()->debug("Replacing " . get_class($this->originalAdapter) . " with PersonaSkinAdapter");

		$typeConverter->setSkinAdapter(new PersonaSkinAdapter());
	}

	protected function onDisable() : void{
		if($this->originalAdapter === null){
			return;
		}

		try{
			$converter = TypeConverter::getInstance();

			if($converter->getSkinAdapter() instanceof PersonaSkinAdapter){
				$this->getLogger()->debug("Restoring original adapter: " . get_class($this->originalAdapter));
				$converter->setSkinAdapter($this->originalAdapter);
			}
		}catch(Throwable $e){
			$this->getLogger()->warning("Failed to restore skin adapter: " . $e->getMessage());
		}
	}
}
