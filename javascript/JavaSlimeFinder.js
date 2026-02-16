const JavaSlimeFinder = (function() {
    const MULTIPLIER = 0x5DEECE66Dn;
    const MASK_48 = (1n << 48n) - 1n;
    const ADDEND = 0xBn;

    function toInt32(value) {
        return BigInt.asIntN(32, value);
    }

    function next(seed, bits) {
        seed = (seed * MULTIPLIER + ADDEND) & MASK_48;
        return [seed, seed >> (48n - BigInt(bits))];
    }

    function nextInt(seed, bound) {
        if (bound <= 0n) throw new Error("bound must be positive");
        
        const boundBig = BigInt(bound);

        if ((boundBig & (boundBig - 1n)) === 0n) {
            const res = next(seed, 31);
            return [res[0], (boundBig * res[1]) >> 31n];
        }

        let u, v;
        do {
            const res = next(seed, 31);
            seed = res[0];
            u = res[1];
            v = u % boundBig;
        } while (u - v + (boundBig - 1n) < 0n);

        return [seed, v];
    }

    return {
        getSlimeRandomSeed: function(worldSeed, x, z) {
            const ws = BigInt.asIntN(64, BigInt(worldSeed));
            const cx = BigInt(x);
            const cz = BigInt(z);
            
            const term1 = toInt32(cx * cx * 0x4c1906n);
            const term2 = toInt32(cx * 0x5ac0dbn);
            const term3 = toInt32(cz * cz) * 0x4307a7n;
            const term4 = toInt32(cz * 0x5f24fn);
            const xorVal = 0x3ad8025fn;

            let seed = ws + term1 + term2 + term3 + term4;
            seed = seed ^ xorVal;
            
            return seed;
        },

        isSlimeChunk: function(worldSeed, x, z) {
            let seed = this.getSlimeRandomSeed(worldSeed, x, z);
            seed = BigInt.asIntN(64, seed);

            let currentSeed = BigInt.asIntN(48, seed ^ MULTIPLIER);
            
            const res = nextInt(currentSeed, 10);
            
            return res[1] === 0n;
        }
    };
})();