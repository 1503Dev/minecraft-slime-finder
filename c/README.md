# Minecraft Slime Finder for C

## 使用
### 将类和头文件添加到项目中并导入
```c
#include "slime_finder.h"
```

### 计算是否是史莱姆区块  
```c
// 基岩版
bedrock_is_slime_chunk(x, z);
// Java 版
java_is_slime_chunk(seed, x, z);
```
这里的 `x` 和 `z` 是区块坐标，而不是世界坐标，方法返回 `1` 或 `0`  
如果你需要把世界坐标转换为区块坐标，可以这样计算  
```c
int chunkX = worldX >> 4;
int chunkZ = worldZ >> 4;
```
如果你需要获取史莱姆区块的随机种子，则运行  
```c
// 基岩版
bedrock_get_slime_random_seed(x, z);
// Java 版
java_get_slime_random_seed(seed, x, z);
```
这里的 `x` 和 `z` 同样是区块坐标