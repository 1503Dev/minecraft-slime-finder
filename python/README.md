# Minecraft Slime Finder for Python

## 使用
### 引入类  
```python
from bedrock_slime_finder import BedrockSlimeFinder
from java_slime_finder import JavaSlimeFinder
```

### 计算是否是史莱姆区块  
```python
# 基岩版
BedrockSlimeFinder.is_slime_chunk(x, z)
# Java 版
JavaSlimeFinder.is_slime_chunk(seed, x, z)
```
这里的 `x` 和 `z` 是区块坐标，而不是世界坐标，函数返回 `true` 或 `false`  
如果你需要把世界坐标转换为区块坐标，可以这样计算  
```python
chunkX = worldX // 16
chunkZ = worldZ // 16;
```
如果你需要获取史莱姆区块的随机种子，则运行  
```python
# 基岩版
BedrockSlimeFinder.get_slime_random_seed(x, z)
# Java 版
JavaSlimeFinder.get_slime_random_seed(seed, x, z)
```
这里的 `x` 和 `z` 同样是区块坐标
