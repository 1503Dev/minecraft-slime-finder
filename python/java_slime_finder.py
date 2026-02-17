class JavaSlimeFinder:
    _MULTIPLIER = 0x5DEECE66D
    _MASK_48 = (1 << 48) - 1
    _ADDEND = 0xB

    @staticmethod
    def _to_int32(value: int) -> int:
        value &= 0xFFFFFFFF
        if value & 0x80000000:
            return value - 0x100000000
        return value

    @staticmethod
    def _next(seed: int, bits: int) -> tuple[int, int]:
        seed = ((seed * JavaSlimeFinder._MULTIPLIER) + JavaSlimeFinder._ADDEND) & JavaSlimeFinder._MASK_48
        return seed, seed >> (48 - bits)

    @staticmethod
    def _next_int(seed: int, bound: int) -> tuple[int, int]:
        if bound <= 0:
            raise ValueError("bound must be positive")

        if (bound & (bound - 1)) == 0:
            seed, res = JavaSlimeFinder._next(seed, 31)
            return seed, (bound * res) >> 31

        while True:
            seed, u = JavaSlimeFinder._next(seed, 31)
            v = u % bound
            if u - v + (bound - 1) >= 0:
                return seed, v

    @classmethod
    def get_slime_random_seed(cls, world_seed: int, x: int, z: int) -> int:
        ws = world_seed & 0xFFFFFFFFFFFFFFFF
        if ws & 0x8000000000000000:
            ws -= 0x10000000000000000

        cx = x
        cz = z

        term1 = cls._to_int32(cx * cx * 0x4C1906)
        term2 = cls._to_int32(cx * 0x5AC0DB)
        term3 = cls._to_int32(cz * cz) * 0x4307A7
        term4 = cls._to_int32(cz * 0x5F24F)
        xor_val = 0x3AD8025F

        seed = ws + term1 + term2 + term3 + term4
        seed ^= xor_val

        return seed & 0xFFFFFFFFFFFFFFFF

    @classmethod
    def is_slime_chunk(cls, world_seed: int, x: int, z: int) -> bool:
        seed = cls.get_slime_random_seed(world_seed, x, z)

        if seed & 0x8000000000000000:
            seed -= 0x10000000000000000

        current_seed = (seed ^ cls._MULTIPLIER) & cls._MASK_48

        _, result = cls._next_int(current_seed, 10)

        return result == 0