<?php

namespace FRashkar\TopKill\Events;

use pocketmine\event\Listener;

use FRashkar\TopKill\Main;

class PlayerDeath implements Listener {

    private $plugin;

    public function __construct(Main $plugin){

        $this->plugin = $plugin;
    }

    public function PlayerDeath(PlayerDeathEvent $event){

        $player = $event->getPlayer();
        $name = $player->getName();
        $cause = $player->getLastDamageCause()->getCause();

        if($cause == EntityDamageEvent::CAUSE_ENTITY_ATTACK){

        $damager = $player->getLastDamageCause()->getDamager();
            if($damager instanceof Player){
                $dname = $damager->getName();
                $this->plugin->kill->set($dname, $this->plugin->kill->get($dname)+1);
                $this->plugin->death->set($dname, $this->plugin->death->get($dname)+1);
            }
        }
    }
}