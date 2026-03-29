#include "slime_finder.h"

static uint64_t bedrock_umul32_lo(uint64_t a, uint64_t b) {
    uint64_t a_low = a & 0xFFFFLL;
    uint64_t b_low = b & 0xFFFFLL;
    uint64_t result = a_low * b_low;
    uint64_t carry = result >> 16;
    carry += (a >> 16) * b_low;
    carry &= 0xFFFFLL;
    carry += a_low * (b >> 16);
    return ((carry & 0xFFFFLL) << 16 | (result & 0xFFFFLL)) & 0xFFFFFFFFLL;
}

void mt_init(MersenneTwister* ctx, uint32_t s) {
    ctx->mt[0] = s & 0xFFFFFFFFU;
    for (ctx->mti = 1; ctx->mti < MT_N; ctx->mti++) {
        uint32_t t = ctx->mt[ctx->mti - 1] ^ (ctx->mt[ctx->mti - 1] >> 30);
        ctx->mt[ctx->mti] = (((1812433253U * ((t & 0xFFFF0000U) >> 16)) << 16) +
                             (1812433253U * (t & 0xFFFFU)) + ctx->mti) & 0xFFFFFFFFU;
    }
}

uint32_t mt_random_int(MersenneTwister* ctx) {
    uint32_t mag01[2] = {0x0U, MT_MATRIX_A};
    if (ctx->mti >= MT_N) {
        int kk;
        for (kk = 0; kk < MT_N - MT_M; kk++) {
            uint32_t y = (ctx->mt[kk] & MT_UPPER_MASK) | (ctx->mt[kk + 1] & MT_LOWER_MASK);
            ctx->mt[kk] = ctx->mt[kk + MT_M] ^ (y >> 1) ^ mag01[y & 0x1U];
        }
        for (; kk < MT_N - 1; kk++) {
            uint32_t y = (ctx->mt[kk] & MT_UPPER_MASK) | (ctx->mt[kk + 1] & MT_LOWER_MASK);
            ctx->mt[kk] = ctx->mt[kk + (MT_M - MT_N)] ^ (y >> 1) ^ mag01[y & 0x1U];
        }
        uint32_t y = (ctx->mt[MT_N - 1] & MT_UPPER_MASK) | (ctx->mt[0] & MT_LOWER_MASK);
        ctx->mt[MT_N - 1] = ctx->mt[MT_M - 1] ^ (y >> 1) ^ mag01[y & 0x1U];
        ctx->mti = 0;
    }
    uint32_t y = ctx->mt[ctx->mti++];
    y ^= y >> 11;
    y ^= (y << 7) & 0x9D2C5680U;
    y ^= (y << 15) & 0xEFC60000U;
    y ^= y >> 18;
    return y & 0xFFFFFFFFU;
}

uint32_t bedrock_get_slime_random_seed(int x, int z) {
    uint64_t seed = bedrock_umul32_lo((uint32_t)x, 522133279U) ^ (uint32_t)z;
    MersenneTwister rng;
    mt_init(&rng, (uint32_t)seed);
    return mt_random_int(&rng);
}

bool bedrock_is_slime_chunk(int x, int z) {
    return (bedrock_get_slime_random_seed(x, z) % 10) == 0;
}

static int java_next(int64_t *seed, int bits) {
    *seed = (*seed * 0x5DEECE66DLL + 0xBLL) & ((1LL << 48) - 1);
    return (int)(*seed >> (48 - bits));
}

int java_next_int(int64_t *seed, int n) {
    if ((n & -n) == n) {
        return (int)((n * (int64_t)java_next(seed, 31)) >> 31);
    }
    int bits, val;
    do {
        bits = java_next(seed, 31);
        val = bits % n;
    } while (bits - val + (n - 1) < 0);
    return val;
}

int64_t java_get_slime_random_seed(int64_t seed, int x, int z) {
    return seed + (int)(x * x * 0x4C1906) + (int)(x * 0x5AC0DB) +
           (int64_t)((int)(z * z)) * 0x4307A7LL + (int)(z * 0x5F24F) ^ 0x3AD8025FLL;
}

bool java_is_slime_chunk(int64_t seed, int x, int z) {
    int64_t internal_seed = java_get_slime_random_seed(seed, x, z);
    int64_t java_random_state = (internal_seed ^ 0x5DEECE66DLL) & ((1LL << 48) - 1);
    return java_next_int(&java_random_state, 10) == 0;
}