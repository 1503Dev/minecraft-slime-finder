# Minecraft Slime Finder for Java

## 使用
### 将类添加到项目中并导入
```java
import BedrockSlimeFinder;
import JavaSlimeFinder;
```

### 计算是否是史莱姆区块  
```java
// 基岩版
BedrockSlimeFinder.isSlimeChunk(x, z);
// Java 版
JavaSlimeFinder.isSlimeChunk(seed, x, z);
```
这里的 `x` 和 `z` 是区块坐标，而不是世界坐标，方法返回 `true` 或 `false`  
如果你需要把世界坐标转换为区块坐标，可以这样计算  
```java
int chunkX = (int)(worldX >= 0 ? worldX / 16 : (worldX - 15) / 16);
int chunkZ = (int)(worldZ >= 0 ? worldZ / 16 : (worldZ - 15) / 16);
```
如果你需要获取史莱姆区块的随机生成器/随机种子，则运行  
```java
// 基岩版
BedrockSlimeFinder.getSlimeRandomSeed(x, z);
// Java 版
JavaSlimeFinder.getSlimeRandom(seed, x, z);
```
这里的 `x` 和 `z` 同样是区块坐标