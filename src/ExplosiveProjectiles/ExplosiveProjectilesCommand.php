<?php

namespace ExplosiveProjectiles;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ExplosiveProjectilesCommand extends Command implements PluginIdentifiableCommand{
    /** @var Loader */
    private $plugin;

    public function __construct(Loader $plugin){
        parent::__construct("explosiveprojectiles", "Enable or disable explosive projectiles just for you!", "/explosiveprojectiles <arrows> <on|off>", ["explosivep", "eprojectiles", "expr"]);
        $this->setPermission("explosiveprojectiles.command");
        $this->plugin = $plugin;
    }

    /**
     * @return Loader
     */
    public function getPlugin(){
        return $this->plugin;
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        switch(count($args)){
            case 2:
                switch(strtolower($args[1])){
                    case "true":
                    case "on":
                        $args[1] = true;
                        break;
                    case "false":
                    case "off":
                    $args[1] = false;
                        break;
                    default:
                        $sender->sendMessage(TextFormat::RED . $this->getUsage());
                        return false;
                        break;
                }
                switch(strtolower($args[0])){
                    case "a":
                    case "arrow":
                    case "arrows":
                        $this->getPlugin()->setExplosiveArrows($sender, $args[1]);
                        $sender->sendMessage(TextFormat::GREEN . "Successfully " . ($args[1] ? "enabled" : "disabled") . " explosive arrows!");
                        break;
                    case "s":
                    case "snowball":
                    case "snowballs":
                        $this->getPlugin()->setExplosiveSnowballs($sender, $args[1]);
                        $sender->sendMessage(TextFormat::GREEN . "Successfully " . ($args[1] ? "enabled" : "disabled") . " explosive snowballs!");
                        break;
                    default:
                        $sender->sendMessage(TextFormat::RED . $this->getUsage());
                        return false;
                        break;
                }
                break;
            default:
                $sender->sendMessage(TextFormat::RED . $this->getUsage());
                return false;
                break;
        }
        return true;
    }
}