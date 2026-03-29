#include <cstdint>

class JavaSlimeFinder {
public:
    static int nextInt(int64_t &seed, int n);
    static int64_t getSlimeRandomSeed(int64_t seed, int x, int z);
    static bool isSlimeChunk(int64_t seed, int x, int z);
};