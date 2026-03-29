#include "BedrockSlimeFinder.h"

uint64_t BedrockSlimeFinder::umul32_lo(uint64_t a, uint64_t b) {
    uint64_t a_low = a & 0xFFFFLL;
    uint64_t b_low = b & 0xFFFFLL;
    uint64_t result = a_low * b_low;
    uint64_t carry = result >> 16;

    carry += (a >> 16) * b_low;
    carry &= 0xFFFFLL;

    carry += a_low * (b >> 16);

    return ((carry & 0xFFFFLL) << 16 | (result & 0xFFFFLL)) & 0xFFFFFFFFLL;
}
void BedrockSlimeFinder::MersenneTwister::initSeed(uint32_t s) {
    mt[0] = s & 0xFFFFFFFFU;
    for (mti = 1; mti < N; mti++) {
        uint32_t t = mt[mti - 1] ^ (mt[mti - 1] >> 30);
        mt[mti] = (((1812433253U * ((t & 0xFFFF0000U) >> 16)) << 16) +
                   (1812433253U * (t & 0xFFFFU)) + mti) & 0xFFFFFFFFU;
    }
}

BedrockSlimeFinder::MersenneTwister::MersenneTwister(uint32_t seed) {
    mti = N + 1;
    initSeed(seed);
};

uint32_t BedrockSlimeFinder::MersenneTwister::randomInt() {
    uint32_t mag01[2] = {0x0U, MATRIX_A};

    if (mti >= N) {
        int kk;
        if (mti == N + 1) initSeed(5489U);

        for (kk = 0; kk < N - M; kk++) {
            uint32_t y = (mt[kk] & UPPER_MASK) | (mt[kk + 1] & LOWER_MASK);
            mt[kk] = mt[kk + M] ^ (y >> 1) ^ mag01[y & 0x1U];
        }
        for (; kk < N - 1; kk++) {
            uint32_t y = (mt[kk] & UPPER_MASK) | (mt[kk + 1] & LOWER_MASK);
            mt[kk] = mt[kk + (M - N)] ^ (y >> 1) ^ mag01[y & 0x1U];
        }
        uint32_t y = (mt[N - 1] & UPPER_MASK) | (mt[0] & LOWER_MASK);
        mt[N - 1] = mt[M - 1] ^ (y >> 1) ^ mag01[y & 0x1U];
        mti = 0;
    }

    uint32_t y = mt[mti++];
    y ^= y >> 11;
    y ^= (y << 7) & 0x9D2C5680U;
    y ^= (y << 15) & 0xEFC60000U;
    y ^= y >> 18;

    return y & 0xFFFFFFFFU;
}


uint32_t BedrockSlimeFinder::getSlimeRandomSeed(int x, int z) {
    uint64_t seed = umul32_lo(static_cast<uint32_t>(x), 522133279U) ^ static_cast<uint32_t>(z);
    MersenneTwister rng(static_cast<uint32_t>(seed));
    return rng.randomInt();
}

bool BedrockSlimeFinder::isSlimeChunk(int x, int z) {
    uint32_t randomVal = getSlimeRandomSeed(x, z);
    return (randomVal % 10) == 0;
}