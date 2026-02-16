# Minecraft Slime Finder for JavaScript

## 使用
### 引入 JS 文件  
  ```html
  <script src="./BedrockSlimeFinder.js"></script>
  <script src="./JavaSlimeFinder.js"></script>
  ```

### 计算是否是史莱姆区块  
```javascript
// 基岩版
BedrockSlimeFinder.isSlimeChunk(x, z)
// Java 版
JavaSlimeFinder.isSlimeChunk(seed, x, z)
// Java 版的种子是必须的，并且最好是字符串或 BigInt，否则计算会不精确
```
这里的 `x` 和 `z` 是区块坐标，而不是世界坐标，函数返回 `true` 或 `false`  
如果你需要把世界坐标转换为区块坐标，可以这样计算  
```javascript
const chunkX = Math.floor(worldX / 16);
const chunkZ = Math.floor(worldZ / 16);
```
如果你需要获取史莱姆区块的随机种子，则运行  
```javascript
// 基岩版
BedrockSlimeFinder.getSlimeRandomSeed(x, z)
// Java 版
JavaSlimeFinder.getSlimeRandomSeed(seed, x, z)
// Java 版的种子是必须的，并且最好是字符串或 BigInt，否则计算会不精确
```
这里的 `x` 和 `z` 同样是区块坐标