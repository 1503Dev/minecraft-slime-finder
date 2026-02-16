public class BedrockSlimeFinder {
    
    private static long umul32_lo(long a, long b) {
        long a_low = a & 0xFFFFL;
        long b_low = b & 0xFFFFL;
        long result = a_low * b_low;
        long carry = result >>> 16;
        
        carry += (a >>> 16) * b_low;
        carry &= 0xFFFFL;
        
        carry += a_low * (b >>> 16);
        
        return ((carry & 0xFFFFL) << 16 | (result & 0xFFFFL)) & 0xFFFFFFFFL;
    }

    public static class MersenneTwister {
        private static final int N = 624;
        private static final int M = 397;
        private static final long MATRIX_A = 0x9908B0DFL;
        private static final long UPPER_MASK = 0x80000000L;
        private static final long LOWER_MASK = 0x7FFFFFFFL;
        
        private long[] mt;
        private int mti;
        
        public MersenneTwister(long seed) {
            mt = new long[624];
            mti = 625;
            initSeed(seed);
        }
        
        private void initSeed(long s) {
            mt[0] = s & 0xFFFFFFFFL;
            for (mti = 1; mti < N; mti++) {
                long t = mt[mti - 1] ^ (mt[mti - 1] >>> 30);
                mt[mti] = (((1812433253L * ((t & 0xFFFF0000L) >>> 16)) << 16) + 
                          (1812433253L * (t & 0xFFFFL)) + mti) & 0xFFFFFFFFL;
            }
        }
        
        public long randomInt() {
            long[] mag01 = {0x0L, MATRIX_A};
            
            if (mti >= N) {
                int kk;
                if (mti == N + 1) initSeed(5489L);

                for (kk = 0; kk < N - M; kk++) {
                    long y = (mt[kk] & UPPER_MASK) | (mt[kk + 1] & LOWER_MASK);
                    mt[kk] = mt[kk + M] ^ (y >>> 1) ^ mag01[(int)(y & 0x1L)];
                }
                for (; kk < N - 1; kk++) {
                    long y = (mt[kk] & UPPER_MASK) | (mt[kk + 1] & LOWER_MASK);
                    mt[kk] = mt[kk + (M - N)] ^ (y >>> 1) ^ mag01[(int)(y & 0x1L)];
                }
                long y = (mt[N - 1] & UPPER_MASK) | (mt[0] & LOWER_MASK);
                mt[N - 1] = mt[M - 1] ^ (y >>> 1) ^ mag01[(int)(y & 0x1L)];
                mti = 0;
            }

            long y = mt[mti++];
            y ^= y >>> 11;
            y ^= (y << 7) & 0x9D2C5680L;
            y ^= (y << 15) & 0xEFC60000L;
            y ^= y >>> 18;
            
            return y & 0xFFFFFFFFL;
        }
    }
    
    public static long getSlimeRandomSeed(int x, int z) {
        long seed = umul32_lo(Integer.toUnsignedLong(x), 522133279L) ^ Integer.toUnsignedLong(z);
        MersenneTwister rng = new MersenneTwister(seed);
        return rng.randomInt();
    }
    
    public static boolean isSlimeChunk(int x, int z) {
        long randomVal = getSlimeRandomSeed(x, z);
        return (randomVal % 10) == 0;
    }
}