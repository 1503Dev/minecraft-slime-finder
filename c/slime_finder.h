#ifndef SLIME_FINDER_H
#define SLIME_FINDER_H

#include <stdint.h>
#include <stdbool.h>

#define MT_N 624
#define MT_M 397
#define MT_MATRIX_A 0x9908B0DFU
#define MT_UPPER_MASK 0x80000000U
#define MT_LOWER_MASK 0x7FFFFFFFU

#ifdef __cplusplus
extern "C" {
#endif

typedef struct {
    uint32_t mt[MT_N];
    int mti;
} MersenneTwister;

void mt_init(MersenneTwister* ctx, uint32_t seed);
uint32_t mt_random_int(MersenneTwister* ctx);

uint32_t bedrock_get_slime_random_seed(int x, int z);
bool bedrock_is_slime_chunk(int x, int z);

int java_next_int(int64_t *seed, int n);
int64_t java_get_slime_random_seed(int64_t seed, int x, int z);
bool java_is_slime_chunk(int64_t seed, int x, int z);

#ifdef __cplusplus
}
#endif

#endif