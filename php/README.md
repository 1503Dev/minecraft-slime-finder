# Minecraft Slime Finder for PHP

## 使用
### 引入 PHP 文件  
```php
require_once './BedrockSlimeFinder.php';
require_once './JavaSlimeFinder.php';
```

### 计算是否是史莱姆区块  
```php
// 基岩版
BedrockSlimeFinder::isSlimeChunk($x, $z)
// Java 版
JavaSlimeFinder::isSlimeChunk($seed, $x, $z)
```
这里的 `$x` 和 `$z` 是区块坐标，而不是世界坐标，函数返回 `true` 或 `false`  
如果你需要把世界坐标转换为区块坐标，可以这样计算  
```php
$chunkX = floor($worldX / 16);
$chunkZ = floor($worldZ / 16);
```
如果你需要获取史莱姆区块的随机种子，则运行  
```php
// 基岩版
BedrockSlimeFinder::getSlimeRandomSeed($x, $z)
// Java 版
JavaSlimeFinder::getSlimeRandomSeed($seed, $x, $z)
```
这里的 `$x` 和 `$z` 同样是区块坐标