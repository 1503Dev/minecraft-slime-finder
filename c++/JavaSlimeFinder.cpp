#include "JavaSlimeFinder.h"

int JavaSlimeFinder::nextInt(int64_t &seed, int n) {
    auto next = [&](int bits) -> int {
        seed = (seed * 0x5DEECE66DLL + 0xBLL) & ((1LL << 48) - 1);
        return static_cast<int>(seed >> (48 - bits));
    };

    if ((n & -n) == n) {
        return static_cast<int>((n * static_cast<int64_t>(next(31))) >> 31);
    }

    int bits, val;
    do {
        bits = next(31);
        val = bits % n;
    } while (bits - val + (n - 1) < 0);
    return val;
}

int64_t JavaSlimeFinder::getSlimeRandomSeed(int64_t seed, int x, int z) {
    int64_t combinedSeed = seed +
                           (int)(x * x * 0x4C1906) +
                           (int)(x * 0x5AC0DB) +
                           (int64_t)((int)(z * z)) * 0x4307A7LL +
                           (int)(z * 0x5F24F) ^ 0x3AD8025FLL;
    return combinedSeed;
}

bool JavaSlimeFinder::isSlimeChunk(int64_t seed, int x, int z) {
    int64_t internalSeed = getSlimeRandomSeed(seed, x, z);
    int64_t javaRandomState = (internalSeed ^ 0x5DEECE66DLL) & ((1LL << 48) - 1);

    return nextInt(javaRandomState, 10) == 0;
}