#include <cstdint>

class BedrockSlimeFinder {
private:
    static uint64_t umul32_lo(uint64_t a, uint64_t b);
public:
    class MersenneTwister {
    private:
        static const int N = 624;
        static const int M = 397;
        static const uint32_t MATRIX_A = 0x9908B0DFU;
        static const uint32_t UPPER_MASK = 0x80000000U;
        static const uint32_t LOWER_MASK = 0x7FFFFFFFU;

        uint32_t mt[N]{};
        int mti;

        void initSeed(uint32_t s);
    public:
        MersenneTwister(uint32_t seed);
        uint32_t randomInt();
    };
    static uint32_t getSlimeRandomSeed(int x, int z);
    static bool isSlimeChunk(int x, int z);
};