<?php

namespace ExplosiveProjectiles;

use pocketmine\entity\Arrow;
use pocketmine\entity\Snowball;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\level\Explosion;
use pocketmine\Player;

class EventHandler implements Listener{
    /** @var Loader */
    private $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onPLayerJoin(PlayerJoinEvent $event){
        $this->plugin->createData($event->getPlayer());
    }

    private $canExplode = [];

    /**
     * @param ProjectileLaunchEvent $event
     */
    public function onProjectileLaunch(ProjectileLaunchEvent $event){
        $player = $event->getEntity()->shootingEntity;
        if($player instanceof Player && $player->hasPermission("explosiveprojectiles.arrows")){
            if(($event->getEntity() instanceof Arrow && $this->plugin->areExplosiveArrows($player)) || ($event->getEntity() instanceof Snowball && $this->plugin->areExplosiveSnowballs($player))){
                $this->canExplode[] = $event->getEntity()->getId();
            }
        }
    }

    /**
     * @param ProjectileHitEvent $event
     */
    public function onProjectileHit(ProjectileHitEvent $event){
        if(in_array($event->getEntity()->getId(), $this->canExplode) && $event->getEntity()->isCollided){
            unset($this->canExplode[$event->getEntity()->getId()]);
            $explosion = new Explosion($event->getEntity()->getPosition(), $this->plugin->getConfig()->get("explosionsize", 20));
            if($this->plugin->getConfig()->get("breakBlocks")){
                $explosion->explodeA();
            }
            $explosion->explodeB();
            $event->getEntity()->kill();
        }
    }
}