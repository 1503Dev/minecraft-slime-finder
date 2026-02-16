object BedrockSlimeFinder {

    private fun umul32_lo(a: Long, b: Long): Long {
        val a_low = a and 0xFFFFL
        val b_low = b and 0xFFFFL
        val result = a_low * b_low
        var carry = result ushr 16

        carry += (a ushr 16) * b_low
        carry = carry and 0xFFFFL

        carry += a_low * (b ushr 16)

        return ((carry and 0xFFFFL) shl 16 or (result and 0xFFFFL)) and 0xFFFFFFFFL
    }

    class MersenneTwister(seed: Long) {
        companion object {
            private const val N = 624
            private const val M = 397
            private const val MATRIX_A = 0x9908B0DFL
            private const val UPPER_MASK = 0x80000000L
            private const val LOWER_MASK = 0x7FFFFFFFL
        }

        private val mt = LongArray(N)
        private var mti = N + 1

        init {
            initSeed(seed)
        }

        private fun initSeed(s: Long) {
            mt[0] = s and 0xFFFFFFFFL
            for (i in 1 until N) {
                val t = mt[i - 1] xor (mt[i - 1] ushr 30)
                mt[i] = (((1812433253L * ((t and 0xFFFF0000L) ushr 16)) shl 16) +
                        (1812433253L * (t and 0xFFFFL)) + i) and 0xFFFFFFFFL
            }
            mti = N
        }

        fun randomInt(): Long {
            val mag01 = longArrayOf(0L, MATRIX_A)

            if (mti >= N) {
                if (mti == N + 1) initSeed(5489L)

                var kk = 0
                while (kk < N - M) {
                    val y = (mt[kk] and UPPER_MASK) or (mt[kk + 1] and LOWER_MASK)
                    mt[kk] = mt[kk + M] xor (y ushr 1) xor mag01[(y and 0x1L).toInt()]
                    kk++
                }
                while (kk < N - 1) {
                    val y = (mt[kk] and UPPER_MASK) or (mt[kk + 1] and LOWER_MASK)
                    mt[kk] = mt[kk + (M - N)] xor (y ushr 1) xor mag01[(y and 0x1L).toInt()]
                    kk++
                }
                val y = (mt[N - 1] and UPPER_MASK) or (mt[0] and LOWER_MASK)
                mt[N - 1] = mt[M - 1] xor (y ushr 1) xor mag01[(y and 0x1L).toInt()]
                mti = 0
            }

            var y = mt[mti++]
            y = y xor (y ushr 11)
            y = y xor ((y shl 7) and 0x9D2C5680L)
            y = y xor ((y shl 15) and 0xEFC60000L)
            y = y xor (y ushr 18)

            return y and 0xFFFFFFFFL
        }
    }

    fun getSlimeRandomSeed(x: Int, z: Int): Long {
        val seed = umul32_lo(x.toLong() and 0xFFFFFFFFL, 522133279L) xor (z.toLong() and 0xFFFFFFFFL)
        val rng = MersenneTwister(seed)
        return rng.randomInt()
    }

    fun isSlimeChunk(x: Int, z: Int): Boolean {
        val randomVal = getSlimeRandomSeed(x, z)
        return randomVal % 10 == 0L
    }
}