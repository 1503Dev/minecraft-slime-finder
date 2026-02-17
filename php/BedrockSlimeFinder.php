<?
class MersenneTwister {
    const N = 624;
    const M = 397;
    const MATRIX_A = 0x9908B0DF;
    const UPPER_MASK = 0x80000000;
    const LOWER_MASK = 0x7FFFFFFF;
    
    private $mt;
    private $mti;
    
    public function __construct($seed) {
        $this->mt = array_fill(0, self::N, 0);
        $this->mti = self::N + 1;
        $this->initSeed($seed);
    }
    
    private function initSeed($s) {
        $this->mt[0] = $s & 0xFFFFFFFF;
        for ($i = 1; $i < self::N; $i++) {
            $t = $this->mt[$i - 1] ^ (($this->mt[$i - 1] >> 30) & 0xFFFFFFFF);
            $this->mt[$i] = ((1812433253 * ((($t & 0xFFFF0000) >> 16) & 0xFFFF)) << 16) + 
                            (1812433253 * ($t & 0xFFFF)) + $i;
            $this->mt[$i] = $this->mt[$i] & 0xFFFFFFFF;
        }
        $this->mti = self::N;
    }
    
    public function randomInt() {
        $mag01 = [0x0, self::MATRIX_A];
        
        if ($this->mti >= self::N) {
            if ($this->mti == self::N + 1) {
                $this->initSeed(5489);
            }
            
            $kk = 0;
            while ($kk < self::N - self::M) {
                $y = ($this->mt[$kk] & self::UPPER_MASK) | ($this->mt[$kk + 1] & self::LOWER_MASK);
                $y_shifted = ($y >> 1) & 0x7FFFFFFF;
                $this->mt[$kk] = $this->mt[$kk + self::M] ^ $y_shifted ^ $mag01[$y & 0x1];
                $kk++;
            }
            while ($kk < self::N - 1) {
                $y = ($this->mt[$kk] & self::UPPER_MASK) | ($this->mt[$kk + 1] & self::LOWER_MASK);
                $y_shifted = ($y >> 1) & 0x7FFFFFFF;
                $this->mt[$kk] = $this->mt[$kk + (self::M - self::N)] ^ $y_shifted ^ $mag01[$y & 0x1];
                $kk++;
            }
            $y = ($this->mt[self::N - 1] & self::UPPER_MASK) | ($this->mt[0] & self::LOWER_MASK);
            $y_shifted = ($y >> 1) & 0x7FFFFFFF;
            $this->mt[self::N - 1] = $this->mt[self::M - 1] ^ $y_shifted ^ $mag01[$y & 0x1];
            $this->mti = 0;
        }
        
        $y = $this->mt[$this->mti++];
        $y = $y ^ (($y >> 11) & 0xFFFFFFFF);
        $y = $y ^ ((($y << 7) & 0xFFFFFFFF) & 0x9D2C5680);
        $y = $y ^ ((($y << 15) & 0xFFFFFFFF) & 0xEFC60000);
        $y = $y ^ (($y >> 18) & 0xFFFFFFFF);
        
        return $y & 0xFFFFFFFF;
    }
}

class BedrockSlimeFinder {
    private static function umul32_lo($a, $b) {
        $a = $a & 0xFFFFFFFF;
        $b = $b & 0xFFFFFFFF;
        
        $a_low = $a & 0xFFFF;
        $b_low = $b & 0xFFFF;
        $result = $a_low * $b_low;
        $carry = ($result >> 16) & 0xFFFF;
        
        $carry += ($a >> 16) * $b_low;
        $carry &= 0xFFFF;
        
        $carry += $a_low * ($b >> 16);
        
        return ((($carry & 0xFFFF) << 16) | ($result & 0xFFFF)) & 0xFFFFFFFF;
    }
    
    public static function getSlimeRandomSeed($x, $z) {
        $seed = self::umul32_lo($x & 0xFFFFFFFF, 522133279) ^ ($z & 0xFFFFFFFF);
        $rng = new MersenneTwister($seed);
        return $rng->randomInt();
    }
    
    public static function isSlimeChunk($x, $z) {
        $randomVal = self::getSlimeRandomSeed($x, $z);
        return ($randomVal % 10) === 0;
    }
}