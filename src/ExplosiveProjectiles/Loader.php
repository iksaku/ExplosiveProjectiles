<?php

namespace ExplosiveProjectiles;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Loader extends PluginBase{
    /** @var Config */
    private $data;

    public function onEnable(){
        if(!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler($this), $this);
        $this->getServer()->getCommandMap()->register("explosiveprojectiles", new ExplosiveProjectilesCommand($this));
        $this->saveDefaultConfig();
        $this->data = new Config($this->getDataFolder() . "Data.json", Config::JSON);
    }

    /**
     * @param Player $player
     */
    public function createData(Player $player){
        if(!is_array($this->data->get($player->getName()))){
            $this->data->setNested($player->getName() . ".arrows", false);
            $this->data->setNested($player->getName() . ".snowballs", false);
        }
        $this->data->save();
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function areExplosiveArrows(Player $player){
        return $this->data->getNested($player->getName() . ".arrows");
    }

    /**
     * @param Player $player
     * @param bool $mode
     */
    public function setExplosiveArrows(Player $player, $mode){
        if($player->hasPermission("explosiveprojectiles.arrows")){
            $this->data->setNested($player->getName() . ".arrows", $mode);
        }
        $this->data->save();
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function areExplosiveSnowballs(Player $player){
        return $this->data->getNested($player->getName() . ".snowballs");
    }

    /**
     * @param Player $player
     * @param bool $mode
     */
    public function setExplosiveSnowballs(Player $player, $mode){
        if($player->hasPermission("explosiveprojectiles.snowballs")){
            $this->data->setNested($player->getName() . ".snowballs", $mode);
        }
        $this->data->save();
    }
}