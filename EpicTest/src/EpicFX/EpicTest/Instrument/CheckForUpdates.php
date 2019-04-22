<?php
namespace EpicFX\EpicTest\Instrument;

use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;

class CheckForUpdates
{

    private $plugin;

    public function __construct(PluginBase $plugin)
    {
        $this->plugin = $plugin;
        $path = $plugin->getDataFolder() . "NewVersion.yml";
        if (is_file($path) or is_dir($path)) {
            @unlink($path);
        }
        $plugin->getServer()
            ->getLogger()
            ->warning(TextFormat::YELLOW . "[" . TextFormat::GREEN . $plugin->getName() . TextFormat::YELLOW . "]" . SmallTools::getFontColor('正在联网检查更新！这可能会需要一点时间.....若您不需要该功能可以关闭，该功能使用CURL请求，若您未安装启用该功能或出现其他异常请尝试关闭更新检查，您可以将"Config.yml"文件中的"更新检查"选项设置为"false"；来禁用它。'));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://xiaokai.gotoip11.com/other/pm-plugin/index.php?name=" . $plugin->getName() . "&v=" . $plugin->getDescription()->getVersion());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if ($output != null and SmallTools::is_serialized($output)) {
            $s = unserialize($output);
            if (is_array($s)) {
                if ($s["new"] and isset($s["v"]) and isset($s["http"])) {
                    $msg = "";
                    if (isset($s["msg"]) and $s["msg"] !== NULL and $s["msg"] != "") {
                        $msg = "\n\n更新内容：" . $s["msg"];
                    }
                    $plugin->getServer()
                        ->getLogger()
                        ->warning(TextFormat::YELLOW . "[" . TextFormat::GREEN . $plugin->getName() . TextFormat::YELLOW . "]" . TextFormat::GOLD . "已有可用新版(版本：" . $s["v"] . "，当前版本：" . $plugin->getDescription()
                        ->getVersion() . ")！请使用任意浏览器访问" . $s["http"] . "下载！下载信息以储存于\"NewVersion.yml\"文件，相关信息如有需要请打开查看。" . $msg);
                    $config = new Config($path, Config::YAML, $s);
                    $config->setAll($s);
                    $config->save();
                    if (isset($s["text"]) and strlen($s["text"]) > 1) {
                        $plugin->getServer()
                            ->getLogger()
                            ->info(TextFormat::YELLOW . $s["text"]);
                    }
                    return;
                } else {
                    $plugin->getServer()
                        ->getLogger()
                        ->info(SmallTools::getFontColor("暂无新版"));
                }
            } else {
                $this->upError();
            }
        } else {
            $this->upError();
        }
    }

    public function upError()
    {
        $this->plugin->getServer()
            ->getLogger()
            ->warning(TextFormat::YELLOW . "[" . TextFormat::GREEN . $this->plugin->getName() . TextFormat::YELLOW . "]" . TextFormat::RED . "更新失败！请联系作者检查更新，QQ：2508543202");
    }
}