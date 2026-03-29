# Minecraft Slime Finder for C++

## 使用
### 将类和头文件添加到项目中并导入
```cpp
#include "BedrockSlimeFinder.h"
#include "JavaSlimeFinder.h"
```

### 计算是否是史莱姆区块  
```cpp
// 基岩版
BedrockSlimeFinder::isSlimeChunk(x, z);
// Java 版
JavaSlimeFinder::isSlimeChunk(seed, x, z);
```
这里的 `x` 和 `z` 是区块坐标，而不是世界坐标，方法返回 `true` 或 `false`  
如果你需要把世界坐标转换为区块坐标，可以这样计算  
```cpp
int chunkX = worldX >> 4;
int chunkZ = worldZ >> 4;
```
如果你需要获取史莱姆区块的随机种子，则运行  
```cpp
// 基岩版
BedrockSlimeFinder::getSlimeRandomSeed(x, z);
// Java 版
JavaSlimeFinder::getSlimeRandomSeed(seed, x, z);
```
这里的 `x` 和 `z` 同样是区块坐标