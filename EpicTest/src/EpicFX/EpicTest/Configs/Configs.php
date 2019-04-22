<?php
namespace EpicFX\EpicTest\Configs;

use pocketmine\utils\Config;
use EpicFX\EpicTest\EpicTest;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;

class Configs
{

    private $plugin;

    /**
     * 玩家默认配置内容
     */
    public static function getDPlayer(): array
    {
        return array(
            "初次进入" => true
        );
    }

    public static function getDC(): array
    {
        return array(
            "更新检查" => true,
            "初次进入提示" => true,
            "点击打开GUI" => array(
                "点击打开GUI" => true,
                "手持物品ID" => "280:0",
                "撤回事件检测" => true,
                "被点击物品ID" => "41:0"
            )
        );
    }

    public function __construct(EpicTest $plugin)
    {
        $this->plugin = $plugin;
        @mkdir($plugin->getDataFolder());
        $plugin->Config = new Config($plugin->getDataFolder() . "Config.yml", Config::YAML, Configs::getDC());
    }
}