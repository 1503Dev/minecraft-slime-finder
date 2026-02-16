const BedrockSlimeFinder = (function() {
    function umul32_lo(a, b) {
        const a_low = a & 0xFFFF;
        const b_low = b & 0xFFFF;
        let result = a_low * b_low;
        let carry = result >>> 16;
        
        carry += (a >>> 16) * b_low;
        carry &= 0xFFFF;
        
        carry += a_low * (b >>> 16);
        
        return ((carry & 0xFFFF) << 16 | (result & 0xFFFF)) >>> 0;
    }

    class MersenneTwister {
        constructor(seed) {
            this.N = 624;
            this.M = 397;
            this.MATRIX_A = 0x9908B0DF;
            this.UPPER_MASK = 0x80000000;
            this.LOWER_MASK = 0x7FFFFFFF;
            this.mt = new Array(624);
            this.mti = 625;
            this.initSeed(seed);
        }

        initSeed(s) {
            this.mt[0] = s >>> 0;
            for (this.mti = 1; this.mti < this.N; this.mti++) {
                let t = this.mt[this.mti - 1] ^ (this.mt[this.mti - 1] >>> 30);
                this.mt[this.mti] = (((1812433253 * ((t & 0xFFFF0000) >>> 16)) << 16) + 
                                    (1812433253 * (t & 0xFFFF)) + this.mti) >>> 0;
            }
        }

        randomInt() {
            const mag01 = [0x0, this.MATRIX_A];
            
            if (this.mti >= this.N) {
                let kk;
                if (this.mti === this.N + 1) this.initSeed(5489);

                for (kk = 0; kk < this.N - this.M; kk++) {
                    let y = (this.mt[kk] & this.UPPER_MASK) | (this.mt[kk + 1] & this.LOWER_MASK);
                    this.mt[kk] = this.mt[kk + this.M] ^ (y >>> 1) ^ mag01[y & 0x1];
                }
                for (; kk < this.N - 1; kk++) {
                    let y = (this.mt[kk] & this.UPPER_MASK) | (this.mt[kk + 1] & this.LOWER_MASK);
                    this.mt[kk] = this.mt[kk + (this.M - this.N)] ^ (y >>> 1) ^ mag01[y & 0x1];
                }
                let y = (this.mt[this.N - 1] & this.UPPER_MASK) | (this.mt[0] & this.LOWER_MASK);
                this.mt[this.N - 1] = this.mt[this.M - 1] ^ (y >>> 1) ^ mag01[y & 0x1];
                this.mti = 0;
            }

            let y = this.mt[this.mti++];
            y ^= y >>> 11;
            y ^= (y << 7) & 0x9D2C5680;
            y ^= (y << 15) & 0xEFC60000;
            y ^= y >>> 18;
            
            return y >>> 0;
        }
    }

    return {
        getSlimeRandomSeed: function(x, z) {
            const seed = umul32_lo(x >>> 0, 522133279) ^ (z >>> 0);
            const rng = new MersenneTwister(seed);
            return rng.randomInt();
        },

        isSlimeChunk: function(x, z) {
            const randomVal = this.getSlimeRandomSeed(x, z);
            return (randomVal % 10) === 0;
        }
    };
})();