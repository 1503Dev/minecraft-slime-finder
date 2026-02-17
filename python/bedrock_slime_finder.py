class BedrockSlimeFinder:
    _N = 624
    _M = 397

    @staticmethod
    def _umul32_lo(a: int, b: int) -> int:
        a_low = a & 0xFFFF
        b_low = b & 0xFFFF
        result = a_low * b_low
        carry = result >> 16

        carry += (a >> 16) * b_low
        carry &= 0xFFFF

        carry += a_low * (b >> 16)

        return ((carry & 0xFFFF) << 16 | (result & 0xFFFF)) & 0xFFFFFFFF

    class _MersenneTwister:
        def __init__(self, seed: int):
            self._LOWER_MASK = 0x7FFFFFFF
            self._UPPER_MASK = 0x80000000
            self._MATRIX_A = 0x9908B0DF
            self.mt = [0] * 624
            self.mti = 625
            self._init_seed(seed)

        def _init_seed(self, s: int):
            self.mt[0] = s & 0xFFFFFFFF
            for i in range(1, 624):
                t = self.mt[i - 1] ^ (self.mt[i - 1] >> 30)
                val = (1812433253 * ((t & 0xFFFF0000) >> 16)) << 16
                val += 1812433253 * (t & 0xFFFF)
                val += i
                self.mt[i] = val & 0xFFFFFFFF
            self.mti = 624

        def random_int(self) -> int:
            mag01 = [0x0, self._MATRIX_A]

            if self.mti >= 624:
                if self.mti == 625:
                    self._init_seed(5489)

                kk = 0
                while kk < 624 - 397:
                    y = (self.mt[kk] & self._UPPER_MASK) | (self.mt[kk + 1] & self._LOWER_MASK)
                    self.mt[kk] = self.mt[kk + 397] ^ (y >> 1) ^ mag01[y & 0x1]
                    kk += 1

                while kk < 623:
                    y = (self.mt[kk] & self._UPPER_MASK) | (self.mt[kk + 1] & self._LOWER_MASK)
                    self.mt[kk] = self.mt[kk + (397 - 624)] ^ (y >> 1) ^ mag01[y & 0x1]
                    kk += 1

                y = (self.mt[623] & self._UPPER_MASK) | (self.mt[0] & self._LOWER_MASK)
                self.mt[623] = self.mt[396] ^ (y >> 1) ^ mag01[y & 0x1]
                self.mti = 0

            y = self.mt[self.mti]
            self.mti += 1

            y ^= y >> 11
            y ^= (y << 7) & 0x9D2C5680
            y ^= (y << 15) & 0xEFC60000
            y ^= y >> 18

            return y & 0xFFFFFFFF

    @classmethod
    def get_slime_random_seed(cls, x: int, z: int) -> int:
        seed = cls._umul32_lo(x & 0xFFFFFFFF, 522133279) ^ (z & 0xFFFFFFFF)
        rng = cls._MersenneTwister(seed)
        return rng.random_int()

    @classmethod
    def is_slime_chunk(cls, x: int, z: int) -> bool:
        random_val = cls.get_slime_random_seed(x, z)
        return (random_val % 10) == 0